<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Information Packet - {{ $property->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .header {
            background: linear-gradient(135deg, #231e20 0%, #1a1516 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 0;
        }

        .logo-section {
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #fcce00;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 14px;
            color: #ffffff;
            opacity: 0.9;
        }

        .property-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }

        .property-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .hero-section {
            position: relative;
            height: 300px;
            background: #f5f5f5;
            margin-bottom: 30px;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .price-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 30px;
        }

        .price {
            font-size: 32px;
            font-weight: bold;
            color: #fcce00;
            margin-bottom: 5px;
        }

        .price-details {
            font-size: 14px;
            opacity: 0.9;
        }

        .content-section {
            padding: 0 30px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #231e20;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #fcce00;
        }

        .property-overview {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #fcce00;
        }

        .quick-facts {
            margin-bottom: 20px;
        }

        .fact-row {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
            overflow: hidden;
        }

        .fact-label {
            font-weight: bold;
            color: #231e20;
            float: left;
            width: 40%;
            margin-right: 10px;
        }

        .fact-value {
            color: #333;
            float: right;
            width: 50%;
        }

        .features-grid {
            width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .features-row {
            overflow: hidden;
        }

        .features-cell {
            float: left;
            width: 48%;
            padding: 5px 15px 5px 0;
            margin-right: 2%;
        }

        .feature-item {
            margin-bottom: 8px;
            color: #333;
        }

        .feature-item::before {
            content: "✓ ";
            color: #fcce00;
            font-weight: bold;
            margin-right: 5px;
        }

        .images-grid {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .images-row {
            overflow: hidden;
            margin-bottom: 10px;
        }

        .image-cell {
            float: left;
            width: 31%;
            margin-right: 3.5%;
            padding: 5px;
        }

        .image-cell:nth-child(3n) {
            margin-right: 0;
        }

        .property-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
        }

        .map-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .coordinates {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solid #ddd;
        }

        .coordinates strong {
            color: #231e20;
            display: block;
            margin-bottom: 5px;
        }

        .agent-section {
            background: #231e20;
            color: white;
            padding: 25px 30px;
            margin: 30px 0;
        }

        .agent-info {
            width: 100%;
            overflow: hidden;
        }

        .agent-details {
            width: 100%;
        }

        .agent-name {
            font-size: 20px;
            font-weight: bold;
            color: #fcce00;
            margin-bottom: 5px;
        }

        .agent-title {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .contact-info {
            font-size: 13px;
            line-height: 1.6;
        }

        .contact-info strong {
            color: #fcce00;
        }

        .mortgage-section {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e6ed;
        }

        .payment-estimate {
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border: 2px solid #fcce00;
        }

        .payment-amount {
            font-size: 24px;
            font-weight: bold;
            color: #231e20;
            margin-bottom: 5px;
        }

        .payment-details {
            font-size: 11px;
            color: #666;
        }

        .footer {
            background: #f5f5f5;
            padding: 20px 30px;
            margin-top: 30px;
            text-align: center;
            border-top: 3px solid #fcce00;
        }

        .footer-text {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }

        .disclaimer {
            background: #fff9e6;
            border: 1px solid #fcce00;
            padding: 15px;
            margin: 20px 30px;
            font-size: 10px;
            color: #666;
            border-radius: 4px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body { 
                font-size: 11px; 
            }
            .hero-section { 
                height: 250px; 
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="company-name">JB LAND & HOME REALTY</div>
            <div class="company-tagline">Find Your Favorite Place</div>
        </div>
        <div class="property-title">{{ $property->title }}</div>
        <div class="property-subtitle">Property Information Packet</div>
    </div>

    <!-- Hero Image with Price Overlay -->
    <div class="hero-section">
        @if($primaryImage)
            <img src="{{ public_path('storage/' . ltrim($primaryImage->url, '/')) }}" alt="{{ $property->title }}" class="hero-image">
        @endif
        <div class="price-overlay">
            <div class="price">${{ number_format($property->price) }}</div>
            <div class="price-details">
                {{ number_format($property->total_acres) }}± Acres
                @if($property->price && $property->total_acres > 0)
                    • ${{ number_format($property->price / $property->total_acres) }}/acre
                @endif
            </div>
        </div>
    </div>

    <!-- Property Overview -->
    <div class="content-section">
        <h2 class="section-title">Property Overview</h2>
        <div class="property-overview">
            @if($property->description)
                {!! nl2br(e(Str::limit($property->description, 500))) !!}
            @else
                <p>This exceptional property offers {{ number_format($property->total_acres) }}± acres of prime land in {{ $property->county }}, Kentucky. Contact our agent for detailed information about this unique opportunity.</p>
            @endif
        </div>
    </div>

    <!-- Quick Facts -->
    <div class="content-section">
        <h2 class="section-title">Property Details</h2>
        <div class="quick-facts">
            <div class="fact-row">
                <div class="fact-label">Property Type:</div>
                <div class="fact-value">{{ ucwords(str_replace('_', ' ', $property->property_type ?? 'Land')) }}</div>
            </div>
            <div class="fact-row">
                <div class="fact-label">Total Acreage:</div>
                <div class="fact-value">{{ number_format($property->total_acres) }}± acres</div>
            </div>
            <div class="fact-row">
                <div class="fact-label">County:</div>
                <div class="fact-value">{{ $property->county }}, Kentucky</div>
            </div>
            @if($property->tillable_acres)
            <div class="fact-row">
                <div class="fact-label">Tillable Acres:</div>
                <div class="fact-value">{{ number_format($property->tillable_acres) }} acres</div>
            </div>
            @endif
            @if($property->wooded_acres)
            <div class="fact-row">
                <div class="fact-label">Wooded Acres:</div>
                <div class="fact-value">{{ number_format($property->wooded_acres) }} acres</div>
            </div>
            @endif
            @if($property->mls_number)
            <div class="fact-row">
                <div class="fact-label">MLS Number:</div>
                <div class="fact-value">{{ $property->mls_number }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Property Rights & Features -->
    @if($property->water_access || $property->mineral_rights || $property->timber_rights || $property->hunting_rights)
    <div class="content-section">
        <h2 class="section-title">Property Rights & Features</h2>
        <div class="features-grid">
            <div class="features-row">
                <div class="features-cell">
                    @if($property->water_access)
                        <div class="feature-item">Water Access</div>
                    @endif
                    @if($property->mineral_rights)
                        <div class="feature-item">Mineral Rights Included</div>
                    @endif
                    @if($property->timber_rights)
                        <div class="feature-item">Timber Rights Included</div>
                    @endif
                </div>
                <div class="features-cell">
                    @if($property->hunting_rights)
                        <div class="feature-item">Hunting Rights</div>
                    @endif
                    @if($property->fishing_rights)
                        <div class="feature-item">Fishing Rights</div>
                    @endif
                    @if($property->has_home)
                        <div class="feature-item">Home on Property</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Estimate -->
    <div class="content-section">
        <h2 class="section-title">Payment Estimate</h2>
        <div class="mortgage-section">
            <p style="text-align: center; margin-bottom: 15px;">
                <strong>Estimated Monthly Payment</strong><br>
                <small>Based on 20% down, 7.5% interest rate, 30-year term</small>
            </p>
            
            @php
                $downPayment = $property->price * 0.20;
                $loanAmount = $property->price - $downPayment;
                $monthlyRate = 0.075 / 12;
                $numPayments = 30 * 12;
                $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $numPayments)) / (pow(1 + $monthlyRate, $numPayments) - 1);
            @endphp
            
            <div class="payment-estimate">
                <div class="payment-amount">${{ number_format($monthlyPayment, 0) }}/month</div>
                <div class="payment-details">
                    Principal & Interest Only<br>
                    Down Payment: ${{ number_format($downPayment) }} (20%)<br>
                    Loan Amount: ${{ number_format($loanAmount) }}
                </div>
            </div>
            
            <p style="font-size: 10px; text-align: center; color: #666; margin-top: 10px;">
                *Estimate only. Actual payments may vary based on credit, taxes, insurance, and other factors.
                Contact our agent for detailed financing information.
            </p>
        </div>
    </div>

    <!-- Property Images -->
    @if($images->count() > 1)
    <div class="content-section">
        <h2 class="section-title">Property Photos</h2>
        <div class="images-grid">
            @foreach($images->skip(1)->take(6) as $index => $image)
                @if($index % 3 === 0)
                    <div class="images-row">
                @endif
                    <div class="image-cell">
                        <img src="{{ public_path('storage/' . ltrim($image->url, '/')) }}" alt="Property Photo" class="property-image">
                    </div>
                @if($index % 3 === 2 || $loop->last)
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Location & GPS -->
    @if($property->latitude && $property->longitude)
    <div class="content-section">
        <h2 class="section-title">Location & GPS Coordinates</h2>
        <div class="map-section">
            <p style="margin-bottom: 15px; font-weight: bold;">Property Location</p>
            
            <div class="coordinates">
                <strong>GPS Coordinates:</strong>
                Latitude: {{ number_format($property->latitude, 6) }}<br>
                Longitude: {{ number_format($property->longitude, 6) }}
            </div>
            
            <p style="font-size: 11px; color: #666;">
                Use these coordinates in your GPS device or mapping app to navigate directly to the property.
                For Google Maps, search: "{{ number_format($property->latitude, 6) }}, {{ number_format($property->longitude, 6) }}"
            </p>
        </div>
    </div>
    @endif

    <!-- Agent Information -->
    <div class="agent-section">
        <h2 style="color: #fcce00; font-size: 18px; margin-bottom: 20px;">Your Real Estate Professional</h2>
        <div class="agent-info">
            <div class="agent-details">
                <div class="agent-name">{{ $agentInfo['name'] }}</div>
                <div class="agent-title">{{ $agentInfo['title'] }}</div>
                <div class="contact-info">
                    <strong>Phone:</strong> {{ $agentInfo['phone'] }}<br>
                    <strong>Email:</strong> {{ $agentInfo['email'] }}<br>
                    <strong>Office:</strong> {{ $agentInfo['address'] }}<br>
                    <strong>License #:</strong> {{ $agentInfo['license'] }}
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: rgba(252, 206, 0, 0.1); border-radius: 6px; text-align: center;">
            <p style="font-size: 14px; margin-bottom: 10px;"><strong>Ready to Schedule a Property Tour?</strong></p>
            <p style="font-size: 12px;">Call {{ $agentInfo['phone'] }} or visit our website to book your personal showing today!</p>
        </div>
    </div>

    <!-- Legal Disclaimer -->
    <div class="disclaimer">
        <strong>Important Disclaimers:</strong><br>
        This information is believed to be accurate but is not guaranteed. Property details, acreage, and features should be verified independently. All measurements are approximate. This material is based upon information from the Kentucky MLS, which may not reflect all real estate activity in the market. Jeremiah Brown, Principal Broker, License #{{ $agentInfo['license'] }}. Equal Housing Opportunity.
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            <strong>JB Land & Home Realty</strong><br>
            {{ $agentInfo['address'] }}<br>
            Phone: {{ $agentInfo['phone'] }} | Email: {{ $agentInfo['email'] }}<br><br>
            
            Generated on {{ $generatedAt }} for {{ $contactData['firstName'] }} {{ $contactData['lastName'] }}<br>
            Property ID: {{ $property->id }} | MLS: {{ $property->mls_number ?? 'N/A' }}
        </div>
    </div>
</body>
</html>
