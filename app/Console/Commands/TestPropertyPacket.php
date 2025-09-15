<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PropertyPacketService;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;

class TestPropertyPacket extends Command
{
    protected $signature = 'test:property-packet 
                           {property-id? : Property ID to test with}
                           {--pdf-only : Only test PDF generation}';
    
    protected $description = 'Test property packet generation system';

    public function handle()
    {
        $this->info('📄 Testing Property Packet System');
        
        // Get property for testing
        $propertyId = $this->argument('property-id');
        $property = $propertyId ? Property::find($propertyId) : Property::first();
        
        if (!$property) {
            $this->error('❌ No property found for testing');
            return 1;
        }
        
        $this->info("Testing with property: {$property->title}");
        $this->info("Property ID: {$property->id}");
        $this->info("Property Price: \${$property->price}");
        $this->info("Images: {$property->images->count()}");
        
        // Test data
        $testContactData = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'phone' => '(555) 123-4567',
            'deliveryMethod' => 'email',
            'interestLevel' => 'very_interested'
        ];
        
        $packetService = new PropertyPacketService();
        
        $this->info("\n🔧 Testing PDF Generation...");
        
        try {
            $pdfResult = $packetService->generatePropertyPDF($property, $testContactData);
            
            if ($pdfResult['success']) {
                $this->info('✅ PDF generated successfully!');
                $this->line("Filename: {$pdfResult['filename']}");
                $this->line("Path: {$pdfResult['pdf_path']}");
                $this->line("Size: " . number_format($pdfResult['size']) . ' bytes');
                $this->line("URL: {$pdfResult['pdf_url']}");
                
                // Check if file actually exists
                if (Storage::disk('public')->exists($pdfResult['pdf_path'])) {
                    $this->info('✅ PDF file confirmed on disk');
                } else {
                    $this->error('❌ PDF file not found on disk');
                }
                
            } else {
                $this->error('❌ PDF generation failed: ' . $pdfResult['message']);
                if (isset($pdfResult['error'])) {
                    $this->line('Error: ' . $pdfResult['error']);
                }
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ PDF generation exception: ' . $e->getMessage());
            $this->line('File: ' . $e->getFile() . ':' . $e->getLine());
            return 1;
        }
        
        if ($this->option('pdf-only')) {
            $this->info("\n🎉 PDF-only test completed successfully!");
            return 0;
        }
        
        $this->info("\n📧 Testing Full Packet Service...");
        
        try {
            $fullResult = $packetService->generateAndSend($property, $testContactData, 'test-contact-id');
            
            if ($fullResult['success']) {
                $this->info('✅ Full packet service completed successfully!');
                $this->line("Delivery method: {$fullResult['delivery_method']}");
                $this->line("Message: {$fullResult['message']}");
            } else {
                $this->error('❌ Full packet service failed: ' . $fullResult['message']);
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Full packet service exception: ' . $e->getMessage());
            return 1;
        }
        
        $this->info("\n🎉 Property packet system test completed successfully!");
        $this->line("\nNext steps:");
        $this->line("1. Test the form on the website: http://jeremiahbrown.test/properties/{$property->slug}");
        $this->line("2. Check GoHighLevel for test contacts");
        $this->line("3. Verify PDF delivery via email/SMS");
        
        return 0;
    }
}
