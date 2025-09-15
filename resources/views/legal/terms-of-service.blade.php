@extends('components.layouts.guest')

@section('title', 'Terms of Service - JB Land & Home Realty')

@section('content')

<!-- Legal Page Hero Section -->
<section class="py-24 bg-black relative overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Navigation -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="group inline-flex items-center text-white/80 hover:text-secondary transition-colors duration-300">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Section Header -->
        <div class="text-center mb-16">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Legal Information</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h1 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Terms of</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Service
                    </span>
                </h1>
            </div>
            
            <!-- Last Updated -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg text-white/60 font-light">
                    Last updated: {{ date('F j, Y') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Terms of Service Content -->
<section class="py-16 bg-gradient-to-b from-black to-primary/20 relative">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 lg:p-12">
            
            <div class="prose prose-invert prose-lg max-w-none">
                
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Agreement to Terms</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        These Terms of Service ("Terms") constitute a legally binding agreement between you and JB Land & Home Realty ("we," "our," or "us") regarding your use of our website and real estate services. By accessing our website or engaging our services, you agree to be bound by these Terms.
                    </p>
                    <p class="text-white/80 leading-relaxed">
                        If you do not agree with these Terms, please do not use our website or services.
                    </p>
                </div>

                <!-- Services Overview -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Our Services</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        JB Land & Home Realty provides real estate services in Kentucky, including:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Property listing and marketing services</li>
                        <li>Buyer representation and property search assistance</li>
                        <li>Market analysis and property valuations</li>
                        <li>Real estate transaction coordination</li>
                        <li>Property management referrals and consultation</li>
                        <li>Investment property guidance</li>
                    </ul>
                    <p class="text-white/80 leading-relaxed">
                        Jeremiah Brown is a licensed Principal Broker in Kentucky (License #294658) and all services are provided in accordance with Kentucky real estate laws and regulations.
                    </p>
                </div>

                <!-- Website Use -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Website Use</h2>
                    
                    <h3 class="text-xl font-medium text-white mb-4">Permitted Use</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        You may use our website for personal, non-commercial purposes to:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Browse property listings and information</li>
                        <li>Contact us regarding real estate services</li>
                        <li>Request property information and schedule viewings</li>
                        <li>Sign up for newsletters and property alerts</li>
                    </ul>

                    <h3 class="text-xl font-medium text-white mb-4">Prohibited Activities</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        You agree not to:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Use automated systems to scrape or harvest data from our website</li>
                        <li>Reproduce, distribute, or commercially use our content without permission</li>
                        <li>Attempt to gain unauthorized access to our systems</li>
                        <li>Post false, misleading, or defamatory information</li>
                        <li>Interfere with the proper functioning of our website</li>
                        <li>Use our website for any illegal purposes</li>
                    </ul>
                </div>

                <!-- Property Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Property Information Disclaimer</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        All property information on this website is provided for informational purposes only and should not be relied upon without independent verification. We make no warranties or representations regarding:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Accuracy or completeness of property information</li>
                        <li>Property availability or current status</li>
                        <li>Square footage, acreage, or boundary measurements</li>
                        <li>Property condition or required repairs</li>
                        <li>Zoning restrictions or permitted uses</li>
                        <li>School districts or tax information</li>
                    </ul>
                    <p class="text-white/80 leading-relaxed">
                        <strong>All information is deemed reliable but not guaranteed.</strong> Buyers should verify all information independently and conduct proper due diligence before making any purchasing decisions.
                    </p>
                </div>

                <!-- MLS Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">MLS Information</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Property listings may be provided through Multiple Listing Service (MLS) databases. This information is:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>For consumers' personal, non-commercial use only</li>
                        <li>Subject to errors, omissions, and changes without notice</li>
                        <li>Not to be used for any commercial data compilation or redistribution</li>
                        <li>Protected by copyright and other intellectual property laws</li>
                    </ul>
                </div>

                <!-- Fair Housing -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Fair Housing Commitment</h2>
                    <div class="bg-white/5 rounded-2xl p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L3 7v2h18V7l-9-5zM5 11v8h4v-6h6v6h4v-8H5z"/>
                                    <rect x="7" y="13" width="2" height="2"/>
                                    <rect x="15" y="13" width="2" height="2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-medium text-white">Equal Housing Opportunity</h3>
                        </div>
                        <p class="text-white/80 leading-relaxed">
                            JB Land & Home Realty is committed to providing equal professional service to all clients without regard to race, color, religion, sex, handicap, familial status, or national origin. We comply with all applicable fair housing laws and regulations.
                        </p>
                    </div>
                </div>

                <!-- Client Relationships -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Client Relationships</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Real estate services are provided subject to execution of appropriate service agreements. Our relationship with you may be as:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li><strong>Client:</strong> When you have signed a representation agreement with us</li>
                        <li><strong>Customer:</strong> When we assist you without a representation agreement</li>
                        <li><strong>Third Party:</strong> When we represent the other party in a transaction</li>
                    </ul>
                    <p class="text-white/80 leading-relaxed">
                        Different duties and obligations apply to each relationship type, as defined by Kentucky real estate law and any written agreements.
                    </p>
                </div>

                <!-- Limitation of Liability -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Limitation of Liability</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        To the fullest extent permitted by law, JB Land & Home Realty and Jeremiah Brown shall not be liable for any indirect, incidental, special, or consequential damages arising from:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Use of our website or services</li>
                        <li>Reliance on property information provided</li>
                        <li>Technical issues or website unavailability</li>
                        <li>Third-party actions or services</li>
                    </ul>
                    <p class="text-white/80 leading-relaxed">
                        Our total liability shall not exceed the amount of fees paid to us for services, if any.
                    </p>
                </div>

                <!-- Indemnification -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Indemnification</h2>
                    <p class="text-white/80 leading-relaxed">
                        You agree to indemnify and hold harmless JB Land & Home Realty and Jeremiah Brown from any claims, damages, or expenses arising from your use of our website or services, violation of these Terms, or violation of any rights of another party.
                    </p>
                </div>

                <!-- Governing Law -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Governing Law</h2>
                    <p class="text-white/80 leading-relaxed">
                        These Terms are governed by Kentucky state law. Any disputes will be resolved in the appropriate courts of Kentucky. If any provision of these Terms is found invalid, the remaining provisions shall remain in effect.
                    </p>
                </div>

                <!-- Changes to Terms -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Changes to Terms</h2>
                    <p class="text-white/80 leading-relaxed">
                        We reserve the right to modify these Terms at any time. Changes will be posted on this page with an updated "Last Updated" date. Continued use of our website or services after changes constitutes acceptance of the modified Terms.
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Contact Information</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Questions about these Terms of Service should be directed to:
                    </p>
                    <div class="bg-white/5 rounded-2xl p-6">
                        <p class="text-white/90 mb-2"><strong>JB Land & Home Realty</strong></p>
                        <p class="text-white/80 mb-2">Jeremiah Brown, Principal Broker</p>
                        <p class="text-white/80 mb-2">Kentucky License #: 294658</p>
                        <p class="text-white/80 mb-2">4629 Maysville Road, Carlisle, KY 40311</p>
                        <p class="text-white/80 mb-2">Phone: <a href="tel:+18594732259" class="text-secondary hover:text-secondary/80">859.473.2259</a></p>
                        <p class="text-white/80">Email: <a href="mailto:jeremiahbbrown1997@gmail.com" class="text-secondary hover:text-secondary/80">jeremiahbbrown1997@gmail.com</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
