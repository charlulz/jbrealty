<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Property Information Packet</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #231e20 0%, #1a1516 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #fcce00;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #231e20;
            margin-bottom: 20px;
        }
        
        .property-highlight {
            background: #f9f9f9;
            border-left: 4px solid #fcce00;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        
        .property-title {
            font-size: 20px;
            font-weight: bold;
            color: #231e20;
            margin-bottom: 10px;
        }
        
        .property-details {
            font-size: 16px;
            color: #666;
        }
        
        .cta-button {
            display: inline-block;
            background: #fcce00;
            color: #231e20;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }
        
        .agent-info {
            background: #231e20;
            color: white;
            padding: 25px;
            margin: 30px 0;
            border-radius: 8px;
        }
        
        .agent-name {
            font-size: 18px;
            color: #fcce00;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .contact-details {
            font-size: 14px;
            line-height: 1.8;
        }
        
        .footer {
            background: #f8f8f8;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        
        .disclaimer {
            font-size: 11px;
            color: #999;
            margin-top: 20px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">JB LAND & HOME REALTY</div>
            <div class="company-tagline">Find Your Favorite Place</div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $contactName }},
            </div>
            
            <p>Thank you for your interest in this exceptional property! As requested, I've attached a comprehensive information packet with all the details you need.</p>
            
            <!-- Property Highlight -->
            <div class="property-highlight">
                <div class="property-title">{{ $property->title }}</div>
                <div class="property-details">
                    <strong>${{ number_format($property->price) }}</strong> • 
                    {{ number_format($property->total_acres) }}± Acres • 
                    {{ $property->county }}, Kentucky
                    @if($property->price && $property->total_acres > 0)
                        <br><em>${{ number_format($property->price / $property->total_acres) }} per acre</em>
                    @endif
                </div>
            </div>
            
            <p><strong>Your property packet includes:</strong></p>
            <ul style="margin: 15px 0; padding-left: 20px; color: #555;">
                <li>High-resolution property photos</li>
                <li>Detailed property specifications and acreage breakdown</li>
                <li>GPS coordinates and location information</li>
                <li>Payment estimates and financing options</li>
                <li>Property rights and features summary</li>
                <li>Agent contact information</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <p style="font-size: 16px; color: #231e20; margin-bottom: 15px;">
                    <strong>Ready to see this property in person?</strong>
                </p>
                <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $agentInfo['phone'] ?? '8594732259') }}" class="cta-button">
                    Call {{ $agentInfo['phone'] ?? '(859) 473-2259' }} to Schedule Your Tour
                </a>
            </div>
            
            <p>I'm here to answer any questions you have about this property and help you through every step of the process. Don't hesitate to reach out!</p>
            
            <p style="margin-top: 25px;">
                Best regards,<br>
                <strong>Jeremiah Brown</strong>
            </p>
        </div>
        
        <!-- Agent Info -->
        <div class="agent-info">
            <div class="agent-name">{{ $agentInfo['name'] ?? 'Jeremiah Brown' }}</div>
            <div style="color: #fcce00; margin-bottom: 15px;">{{ $agentInfo['title'] ?? 'Principal Broker' }}</div>
            <div class="contact-details">
                <strong>Direct:</strong> {{ $agentInfo['phone'] ?? '(859) 473-2259' }}<br>
                <strong>Email:</strong> {{ $agentInfo['email'] ?? 'jblandandhomerealty@gmail.com' }}<br>
                <strong>Office:</strong> {{ $agentInfo['address'] ?? '4629 Maysville Road, Carlisle, KY 40311' }}<br>
                <strong>License:</strong> #{{ $agentInfo['license'] ?? '294658' }}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>JB Land & Home Realty</strong></p>
            <p>{{ $agentInfo['address'] ?? '4629 Maysville Road, Carlisle, KY 40311' }}</p>
            <p>{{ $agentInfo['phone'] ?? '(859) 473-2259' }} | {{ $agentInfo['email'] ?? 'jblandandhomerealty@gmail.com' }}</p>
            
            <div class="disclaimer">
                This information is believed to be accurate but is not guaranteed. Property details should be verified independently. 
                Equal Housing Opportunity. Kentucky Real Estate License #{{ $agentInfo['license'] ?? '294658' }}.
            </div>
        </div>
    </div>
</body>
</html>
