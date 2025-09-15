<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class GoHighLevelService
{
    protected $apiToken;
    protected $locationId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.gohighlevel.api_token');
        $this->locationId = config('services.gohighlevel.location_id', '7YwBmZCIpKXv2NPxltud');
        $this->baseUrl = 'https://rest.gohighlevel.com/v1';
    }

    /**
     * Create a new contact in GoHighLevel
     */
    public function createContact(array $contactData): array
    {
        try {
            $payload = [
                'firstName' => $contactData['firstName'],
                'lastName' => $contactData['lastName'],
                'email' => $contactData['email'],
                'phone' => $contactData['phone'],
                'source' => $contactData['source'] ?? 'JB Land & Home Realty Website',
                'tags' => $contactData['tags'] ?? [],
                'customFields' => $this->formatCustomFields($contactData['customFields'] ?? []),
            ];

            Log::info('Creating GHL contact', [
                'email' => $payload['email'],
                'source' => $payload['source']
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/contacts/', $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('GHL contact created successfully', [
                    'email' => $payload['email'],
                    'contact_id' => $responseData['contact']['id'] ?? null
                ]);

                return [
                    'success' => true,
                    'contact_id' => $responseData['contact']['id'] ?? null,
                    'message' => 'Contact created successfully',
                    'data' => $responseData
                ];
            }

            $errorData = $response->json();
            Log::error('GHL contact creation failed', [
                'status' => $response->status(),
                'error' => $errorData,
                'payload' => $payload
            ]);

            // Check if contact already exists
            if ($response->status() === 422 && isset($errorData['message']) && 
                str_contains($errorData['message'], 'already exists')) {
                
                // Try to update existing contact
                return $this->updateExistingContact($contactData);
            }

            return [
                'success' => false,
                'message' => $errorData['message'] ?? 'Failed to create contact',
                'error' => $errorData
            ];

        } catch (\Exception $e) {
            Log::error('GHL contact creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'API connection failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update existing contact
     */
    public function updateExistingContact(array $contactData): array
    {
        try {
            // First, find the contact by email
            $existingContact = $this->findContactByEmail($contactData['email']);
            
            if (!$existingContact['success']) {
                return $existingContact;
            }

            $contactId = $existingContact['contact_id'];

            // Update the contact with new information
            $payload = [
                'tags' => $contactData['tags'] ?? [],
                'customFields' => $this->formatCustomFields($contactData['customFields'] ?? []),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->put($this->baseUrl . '/contacts/' . $contactId, $payload);

            if ($response->successful()) {
                Log::info('GHL contact updated successfully', [
                    'email' => $contactData['email'],
                    'contact_id' => $contactId
                ]);

                return [
                    'success' => true,
                    'contact_id' => $contactId,
                    'message' => 'Contact updated successfully',
                    'data' => $response->json()
                ];
            }

            $errorData = $response->json();
            Log::error('GHL contact update failed', [
                'status' => $response->status(),
                'error' => $errorData,
                'contact_id' => $contactId
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update existing contact',
                'error' => $errorData
            ];

        } catch (\Exception $e) {
            Log::error('GHL contact update exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to update contact',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Find contact by email
     */
    public function findContactByEmail(string $email): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get($this->baseUrl . '/contacts/', [
                'email' => $email,
                'limit' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $contacts = $data['contacts'] ?? [];

                if (!empty($contacts)) {
                    return [
                        'success' => true,
                        'contact_id' => $contacts[0]['id'],
                        'contact' => $contacts[0]
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Contact not found'
            ];

        } catch (\Exception $e) {
            Log::error('GHL find contact exception', [
                'error' => $e->getMessage(),
                'email' => $email
            ]);

            return [
                'success' => false,
                'message' => 'Failed to find contact',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get contact status
     */
    public function getContactStatus(string $contactId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get($this->baseUrl . '/contacts/' . $contactId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Contact not found'
            ];

        } catch (\Exception $e) {
            Log::error('GHL get contact status exception', [
                'error' => $e->getMessage(),
                'contact_id' => $contactId
            ]);

            return [
                'success' => false,
                'message' => 'Failed to get contact status',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Add tags to contact
     */
    public function addTagsToContact(string $contactId, array $tags): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/contacts/' . $contactId . '/tags', [
                'tags' => $tags
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Tags added successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to add tags'
            ];

        } catch (\Exception $e) {
            Log::error('GHL add tags exception', [
                'error' => $e->getMessage(),
                'contact_id' => $contactId
            ]);

            return [
                'success' => false,
                'message' => 'Failed to add tags',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format custom fields for GHL API
     */
    private function formatCustomFields(array $fields): array
    {
        $formatted = [];
        
        foreach ($fields as $key => $value) {
            $formatted[] = [
                'key' => $key,
                'field_value' => (string) $value
            ];
        }

        return $formatted;
    }

    /**
     * Test API connection
     */
    public function testConnection(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get($this->baseUrl . '/locations/' . $this->locationId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Connection failed',
                'status' => $response->status(),
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection exception',
                'error' => $e->getMessage()
            ];
        }
    }
}
