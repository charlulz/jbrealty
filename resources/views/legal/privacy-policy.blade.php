@extends('components.layouts.guest')

@section('title', 'Privacy Policy - JB Land & Home Realty')

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
                    <span class="block text-white font-sans font-light tracking-wide">Privacy</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Policy
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

<!-- Privacy Policy Content -->
<section class="py-16 bg-gradient-to-b from-black to-primary/20 relative">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 lg:p-12">
            
            <div class="prose prose-invert prose-lg max-w-none">
                
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Introduction</h2>
                    <p class="text-white/80 leading-relaxed">
                        JB Land & Home Realty ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services. This policy applies to all information collected by JB Land & Home Realty in connection with our real estate services in Kentucky.
                    </p>
                </div>

                <!-- Information We Collect -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Information We Collect</h2>
                    
                    <h3 class="text-xl font-medium text-white mb-4">Personal Information</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We may collect personal information that you voluntarily provide when you:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Contact us through our website or forms</li>
                        <li>Request property information or schedule viewings</li>
                        <li>Sign up for our newsletter or property alerts</li>
                        <li>Engage our real estate services</li>
                        <li>Provide feedback or testimonials</li>
                    </ul>

                    <p class="text-white/80 leading-relaxed mb-6">
                        This may include your name, email address, phone number, mailing address, property preferences, and any other information you choose to provide.
                    </p>

                    <h3 class="text-xl font-medium text-white mb-4">Automatic Information Collection</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We may automatically collect certain information about your visit to our website, including:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>IP address and browser information</li>
                        <li>Pages visited and time spent on our site</li>
                        <li>Referring websites and search terms</li>
                        <li>Device and operating system information</li>
                    </ul>
                </div>

                <!-- How We Use Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">How We Use Your Information</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Provide and improve our real estate services</li>
                        <li>Respond to your inquiries and property requests</li>
                        <li>Send you property listings and market updates (with your consent)</li>
                        <li>Schedule property viewings and appointments</li>
                        <li>Comply with legal and regulatory requirements</li>
                        <li>Protect against fraud and ensure website security</li>
                        <li>Analyze website usage to improve user experience</li>
                    </ul>
                </div>

                <!-- Information Sharing -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Information Sharing</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li><strong>With your consent:</strong> When you explicitly authorize us to share information</li>
                        <li><strong>Professional services:</strong> With attorneys, lenders, inspectors, and other professionals involved in your real estate transaction</li>
                        <li><strong>MLS and co-operating brokers:</strong> As required for property listings and transactions</li>
                        <li><strong>Legal compliance:</strong> When required by law or legal process</li>
                        <li><strong>Business operations:</strong> With trusted service providers who assist in our operations (subject to confidentiality agreements)</li>
                    </ul>
                </div>

                <!-- MLS and Property Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">MLS and Property Information</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Property information displayed on our website may come from Multiple Listing Service (MLS) databases. This information is provided for consumers' personal, non-commercial use and may not be used for any purpose other than to identify prospective properties. MLS information is subject to change without notice and should be independently verified.
                    </p>
                </div>

                <!-- Data Security -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Data Security</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no internet transmission is completely secure, and we cannot guarantee absolute security.
                    </p>
                </div>

                <!-- Your Rights -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Your Rights</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Access and review the personal information we have about you</li>
                        <li>Request corrections to inaccurate information</li>
                        <li>Request deletion of your personal information (subject to legal retention requirements)</li>
                        <li>Opt out of marketing communications at any time</li>
                        <li>Withdraw consent for information processing where applicable</li>
                    </ul>
                </div>

                <!-- Cookies and Tracking -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Cookies and Tracking Technologies</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Our website may use cookies and similar technologies to enhance your browsing experience and analyze website usage. You can control cookie settings through your browser preferences. For more information, please see our <a href="{{ route('cookie-policy') }}" class="text-secondary hover:text-secondary/80 underline">Cookie Policy</a>.
                    </p>
                </div>

                <!-- Changes to Privacy Policy -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Changes to This Privacy Policy</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. We encourage you to review this Privacy Policy periodically.
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Contact Us</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        If you have questions about this Privacy Policy or our privacy practices, please contact us:
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
