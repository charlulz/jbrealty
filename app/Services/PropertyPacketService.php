<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PropertyPacketService
{
    /**
     * Generate and send property packet
     */
    public function generateAndSend(Property $property, array $contactData, ?string $ghlContactId = null): array
    {
        try {
            Log::info('Generating property packet', [
                'property_id' => $property->id,
                'delivery_method' => $contactData['deliveryMethod'],
                'email' => $contactData['email']
            ]);

            // Generate PDF
            $pdfResult = $this->generatePropertyPDF($property, $contactData);
            
            if (!$pdfResult['success']) {
                return $pdfResult;
            }

            // Send via chosen delivery method
            if ($contactData['deliveryMethod'] === 'email') {
                return $this->sendViaEmail($property, $contactData, $pdfResult['pdf_path']);
            } else {
                return $this->sendViaSMS($property, $contactData, $pdfResult['pdf_path'], $ghlContactId);
            }

        } catch (\Exception $e) {
            Log::error('Property packet generation failed', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to generate property packet',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate property PDF
     */
    public function generatePropertyPDF(Property $property, array $contactData): array
    {
        try {
            $data = [
                'property' => $property,
                'contactData' => $contactData,
                'images' => $property->images()->orderBy('sort_order')->limit(6)->get(),
                'primaryImage' => $property->images()->where('is_primary', true)->first() ?? $property->images()->first(),
                'generatedAt' => now()->format('M j, Y \a\t g:i A'),
                'agentInfo' => [
                    'name' => 'Jeremiah Brown',
                    'title' => 'Principal Broker',
                    'phone' => '(859) 473-2259',
                    'email' => 'jblandandhomerealty@gmail.com',
                    'address' => '4629 Maysville Road, Carlisle, KY 40311',
                    'license' => '294658'
                ]
            ];

            // Generate PDF using a blade template
            $pdf = Pdf::loadView('property-packet.pdf', $data);
            $pdf->setPaper('letter', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'margin-top' => 10,
                'margin-right' => 10,
                'margin-bottom' => 10,
                'margin-left' => 10,
            ]);

            // Generate filename
            $filename = 'property-packet-' . $property->id . '-' . time() . '.pdf';
            $directory = 'property-packets/' . date('Y/m');
            $fullPath = $directory . '/' . $filename;

            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);

            // Save PDF
            $pdfContent = $pdf->output();
            Storage::disk('public')->put($fullPath, $pdfContent);

            Log::info('Property PDF generated successfully', [
                'property_id' => $property->id,
                'filename' => $filename,
                'size' => strlen($pdfContent)
            ]);

            return [
                'success' => true,
                'pdf_path' => $fullPath,
                'pdf_url' => Storage::disk('public')->url($fullPath),
                'filename' => $filename,
                'size' => strlen($pdfContent)
            ];

        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send packet via email
     */
    private function sendViaEmail(Property $property, array $contactData, string $pdfPath): array
    {
        try {
            $pdfFullPath = Storage::disk('public')->path($pdfPath);
            
            if (!file_exists($pdfFullPath)) {
                throw new \Exception('PDF file not found: ' . $pdfFullPath);
            }

            // Send email with PDF attachment
            Mail::send('emails.property-packet', [
                'property' => $property,
                'contactName' => $contactData['firstName'] . ' ' . $contactData['lastName'],
                'contactData' => $contactData
            ], function ($message) use ($contactData, $property, $pdfFullPath) {
                $message->to($contactData['email'], $contactData['firstName'] . ' ' . $contactData['lastName'])
                       ->subject('Your Property Information Packet - ' . $property->title)
                       ->from('jblandandhomerealty@gmail.com', 'Jeremiah Brown - JB Land & Home Realty')
                       ->attach($pdfFullPath, [
                           'as' => 'Property-Packet-' . $property->id . '.pdf',
                           'mime' => 'application/pdf'
                       ]);
            });

            Log::info('Property packet sent via email', [
                'property_id' => $property->id,
                'email' => $contactData['email']
            ]);

            return [
                'success' => true,
                'message' => 'Property packet sent via email',
                'delivery_method' => 'email'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send packet via email', [
                'property_id' => $property->id,
                'email' => $contactData['email'],
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send packet via SMS (through GHL)
     */
    private function sendViaSMS(Property $property, array $contactData, string $pdfPath, ?string $ghlContactId = null): array
    {
        try {
            // For SMS delivery, we'll create a public link to the PDF
            $pdfUrl = Storage::disk('public')->url($pdfPath);
            $fullUrl = url($pdfUrl);

            // Create a shortened/branded link (could use bit.ly or custom shortener)
            $message = "Hi {$contactData['firstName']}! Here's your property information packet for {$property->title}: {$fullUrl} - Jeremiah Brown, JB Land & Home Realty (859) 473-2259";

            // Note: In a full implementation, you would send this through GHL's SMS API
            // For now, we'll log it and rely on GHL's automation workflow to send the SMS
            
            Log::info('Property packet SMS prepared', [
                'property_id' => $property->id,
                'phone' => $contactData['phone'],
                'pdf_url' => $fullUrl,
                'message_length' => strlen($message)
            ]);

            // In production, you would integrate with GHL's SMS sending API here
            // or trigger an automation workflow that includes the PDF link

            return [
                'success' => true,
                'message' => 'Property packet prepared for SMS delivery',
                'delivery_method' => 'sms',
                'pdf_url' => $fullUrl,
                'sms_message' => $message
            ];

        } catch (\Exception $e) {
            Log::error('Failed to prepare packet for SMS', [
                'property_id' => $property->id,
                'phone' => $contactData['phone'],
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to prepare SMS delivery',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean up old packet files
     */
    public function cleanupOldPackets(int $daysOld = 30): array
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            $packetDirectory = 'property-packets';
            
            $files = Storage::disk('public')->allFiles($packetDirectory);
            $deletedFiles = 0;

            foreach ($files as $file) {
                $lastModified = Storage::disk('public')->lastModified($file);
                
                if ($lastModified < $cutoffDate->timestamp) {
                    Storage::disk('public')->delete($file);
                    $deletedFiles++;
                }
            }

            Log::info('Property packet cleanup completed', [
                'days_old' => $daysOld,
                'files_deleted' => $deletedFiles
            ]);

            return [
                'success' => true,
                'message' => "Deleted {$deletedFiles} old packet files",
                'files_deleted' => $deletedFiles
            ];

        } catch (\Exception $e) {
            Log::error('Property packet cleanup failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Cleanup failed',
                'error' => $e->getMessage()
            ];
        }
    }
}
