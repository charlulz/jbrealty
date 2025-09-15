<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoHighLevelService;
use App\Services\PropertyPacketService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Property;

class PropertyPacketController extends Controller
{
    protected $ghlService;
    protected $packetService;

    public function __construct(GoHighLevelService $ghlService, PropertyPacketService $packetService)
    {
        $this->ghlService = $ghlService;
        $this->packetService = $packetService;
    }

    /**
     * Handle property packet request
     */
    public function requestPacket(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'deliveryMethod' => 'required|in:email,sms',
                'propertyId' => 'required|exists:properties,id',
                'propertyTitle' => 'required|string',
                'interestLevel' => 'nullable|string|in:just_browsing,somewhat_interested,very_interested,ready_to_purchase',
                'propertyUrl' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your information and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Get the property
            $property = Property::findOrFail($data['propertyId']);

            Log::info('Property packet request received', [
                'property_id' => $property->id,
                'email' => $data['email'],
                'delivery_method' => $data['deliveryMethod']
            ]);

            // Create contact in GoHighLevel
            $contactData = [
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'phone' => $this->formatPhone($data['phone']),
                'source' => 'Property Packet Request',
                'tags' => [
                    'Property Lead',
                    'Packet Request',
                    ucfirst($data['deliveryMethod']) . ' Delivery'
                ],
                'customFields' => [
                    'property_id' => $property->id,
                    'property_title' => $property->title,
                    'property_price' => $property->price,
                    'property_acres' => $property->total_acres,
                    'interest_level' => $data['interestLevel'] ?? 'not_specified',
                    'delivery_method' => $data['deliveryMethod'],
                    'property_url' => $data['propertyUrl'] ?? '',
                    'lead_source_detail' => 'Instant Property Packet - ' . $property->title
                ]
            ];

            // Submit to GoHighLevel
            $ghlResponse = $this->ghlService->createContact($contactData);

            if (!$ghlResponse['success']) {
                Log::error('GoHighLevel contact creation failed', [
                    'property_id' => $property->id,
                    'email' => $data['email'],
                    'ghl_response' => $ghlResponse
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to process your request at this time. Please try again or call us directly at (859) 473-2259.'
                ], 500);
            }

            // Generate and send the property packet
            $packetResult = $this->packetService->generateAndSend($property, $data, $ghlResponse['contact_id'] ?? null);

            if (!$packetResult['success']) {
                Log::error('Property packet generation failed', [
                    'property_id' => $property->id,
                    'email' => $data['email'],
                    'packet_result' => $packetResult
                ]);

                // Still return success since contact was created in GHL
                // The follow-up automation will handle delivery
                return response()->json([
                    'success' => true,
                    'message' => 'Your request has been received! You\'ll receive your property packet shortly via ' . 
                                ($data['deliveryMethod'] === 'email' ? 'email' : 'text message') . '.',
                    'contact_id' => $ghlResponse['contact_id'] ?? null
                ]);
            }

            Log::info('Property packet request completed successfully', [
                'property_id' => $property->id,
                'email' => $data['email'],
                'contact_id' => $ghlResponse['contact_id'] ?? null,
                'delivery_method' => $data['deliveryMethod']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your property packet has been sent! Check your ' . 
                            ($data['deliveryMethod'] === 'email' ? 'email' : 'text messages') . ' in the next few minutes.',
                'contact_id' => $ghlResponse['contact_id'] ?? null,
                'delivery_method' => $data['deliveryMethod']
            ]);

        } catch (\Exception $e) {
            Log::error('Property packet request failed with exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an unexpected error. Please try again or call us directly at (859) 473-2259.'
            ], 500);
        }
    }

    /**
     * Format phone number for consistency
     */
    private function formatPhone(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Format as (XXX) XXX-XXXX if it's a 10-digit US number
        if (strlen($phone) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($phone, 0, 3),
                substr($phone, 3, 3),
                substr($phone, 6, 4)
            );
        }
        
        // Return as-is if not a standard 10-digit number
        return $phone;
    }

    /**
     * Get packet status (for tracking/debugging)
     */
    public function getPacketStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 422);
        }

        try {
            $contactId = $request->input('contact_id');
            $status = $this->ghlService->getContactStatus($contactId);

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get packet status', [
                'contact_id' => $request->input('contact_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to check status'
            ], 500);
        }
    }
}
