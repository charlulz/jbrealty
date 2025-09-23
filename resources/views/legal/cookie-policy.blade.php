@extends('components.layouts.guest')

@section('title', 'Cookie Policy - JB Land & Home Realty')

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
                    <span class="block text-white font-sans font-light tracking-wide">Cookie</span>
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

<!-- Cookie Policy Content -->
<section class="py-16 bg-gradient-to-b from-black to-primary/20 relative">
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 lg:p-12">
            
            <div class="prose prose-invert prose-lg max-w-none">
                
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">What Are Cookies</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Cookies are small text files that are placed on your computer or mobile device when you visit our website. They are widely used to make websites work more efficiently and provide information to website owners about how visitors use their sites.
                    </p>
                    <p class="text-white/80 leading-relaxed">
                        This Cookie Policy explains how JB Land & Home Realty uses cookies and similar technologies on our website, and how you can control them.
                    </p>
                </div>

                <!-- Types of Cookies -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Types of Cookies We Use</h2>
                    
                    <h3 class="text-xl font-medium text-white mb-4">Essential Cookies</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        These cookies are necessary for our website to function properly. They enable core functionality such as security, network management, and accessibility. Without these cookies, our website cannot function properly.
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-8">
                        <li>Session management and user authentication</li>
                        <li>Security and fraud prevention</li>
                        <li>Load balancing and website performance</li>
                        <li>Accessibility features and preferences</li>
                    </ul>

                    <h3 class="text-xl font-medium text-white mb-4">Performance Cookies</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. This helps us improve our website's functionality and user experience.
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-8">
                        <li>Page views and navigation patterns</li>
                        <li>Time spent on different pages</li>
                        <li>Error reporting and website performance monitoring</li>
                        <li>Popular content and search terms</li>
                    </ul>

                    <h3 class="text-xl font-medium text-white mb-4">Functional Cookies</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        These cookies remember choices you make to improve your experience and personalization. They may be set by us or by third-party providers whose services we use on our website.
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-8">
                        <li>Language and region preferences</li>
                        <li>Property search criteria and saved searches</li>
                        <li>Contact form information (if you choose to save it)</li>
                        <li>Display preferences and accessibility settings</li>
                    </ul>

                    <h3 class="text-xl font-medium text-white mb-4">Targeting/Advertising Cookies</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        These cookies may be used to deliver advertisements that are relevant to you and your interests. They may also be used to limit the number of times you see an advertisement and help measure the effectiveness of advertising campaigns.
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-8">
                        <li>Interest-based advertising</li>
                        <li>Social media integration</li>
                        <li>Remarketing and retargeting</li>
                        <li>Conversion tracking</li>
                    </ul>
                </div>

                <!-- Third-Party Services -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Third-Party Services</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Our website may use third-party services that set their own cookies. These services include:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li><strong>Google Analytics:</strong> For website analytics and performance monitoring</li>
                        <li><strong>Social Media Platforms:</strong> For social sharing and integration features</li>
                        <li><strong>Map Services:</strong> For interactive property location maps</li>
                        <li><strong>Email Marketing:</strong> For newsletter subscription management</li>
                        <li><strong>Live Chat:</strong> For customer support and inquiries</li>
                    </ul>
                    <p class="text-white/80 leading-relaxed">
                        These third-party services have their own privacy policies and cookie policies. We recommend reviewing their policies to understand how they collect and use information.
                    </p>
                </div>

                <!-- Managing Cookies -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Managing Your Cookie Preferences</h2>
                    
                    <h3 class="text-xl font-medium text-white mb-4">Browser Settings</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Most web browsers allow you to control cookies through their settings. You can typically:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>View which cookies are stored on your device</li>
                        <li>Delete cookies that are already stored</li>
                        <li>Block cookies from being stored</li>
                        <li>Set preferences for specific websites</li>
                    </ul>

                    <h3 class="text-xl font-medium text-white mb-4">Browser-Specific Instructions</h3>
                    <div class="bg-white/5 rounded-2xl p-6 mb-6">
                        <ul class="list-disc list-inside text-white/80 space-y-2">
                            <li><strong>Chrome:</strong> Settings > Privacy and Security > Cookies and other site data</li>
                            <li><strong>Firefox:</strong> Preferences > Privacy & Security > Cookies and Site Data</li>
                            <li><strong>Safari:</strong> Preferences > Privacy > Manage Website Data</li>
                            <li><strong>Edge:</strong> Settings > Cookies and site permissions > Cookies and site data</li>
                        </ul>
                    </div>

                    <h3 class="text-xl font-medium text-white mb-4">Impact of Disabling Cookies</h3>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Please note that disabling cookies may affect your experience on our website:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li>Some website features may not function properly</li>
                        <li>Property search preferences may not be saved</li>
                        <li>Contact form information may need to be re-entered</li>
                        <li>We may not be able to provide personalized content</li>
                    </ul>
                </div>

                <!-- Local Storage -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Local Storage and Session Storage</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        In addition to cookies, our website may use local storage and session storage technologies to enhance functionality:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li><strong>Local Storage:</strong> Stores data locally on your device for improved performance</li>
                        <li><strong>Session Storage:</strong> Temporarily stores information during your browsing session</li>
                        <li><strong>Property Preferences:</strong> Remembers your search criteria and favorite properties</li>
                        <li><strong>Form Data:</strong> Saves partially completed forms to prevent data loss</li>
                    </ul>
                </div>

                <!-- Updates -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Updates to This Policy</h2>
                    <p class="text-white/80 leading-relaxed">
                        We may update this Cookie Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will post any changes on this page with an updated "Last Updated" date.
                    </p>
                </div>

                <!-- More Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">More Information</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        For more information about how we collect and use your personal information, please see our <a href="{{ route('privacy-policy') }}" class="text-secondary hover:text-secondary/80 underline">Privacy Policy</a>.
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        For general information about cookies and privacy, you may visit:
                    </p>
                    <ul class="list-disc list-inside text-white/80 space-y-2 mb-6">
                        <li><a href="https://www.allaboutcookies.org" target="_blank" class="text-secondary hover:text-secondary/80 underline">AllAboutCookies.org</a></li>
                        <li><a href="https://cookiepedia.co.uk" target="_blank" class="text-secondary hover:text-secondary/80 underline">Cookiepedia.co.uk</a></li>
                        <li><a href="https://www.youronlinechoices.com" target="_blank" class="text-secondary hover:text-secondary/80 underline">YourOnlineChoices.com</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-medium text-secondary mb-6">Contact Us</h2>
                    <p class="text-white/80 leading-relaxed mb-6">
                        If you have questions about our use of cookies or this Cookie Policy, please contact us:
                    </p>
                    <div class="bg-white/5 rounded-2xl p-6">
                        <p class="text-white/90 mb-2"><strong>JB Land & Home Realty</strong></p>
                        <p class="text-white/80 mb-2">Jeremiah Brown, Principal Broker</p>
                        <p class="text-white/80 mb-2">Kentucky License #: 294658</p>
                        <p class="text-white/80 mb-2">4629 Maysville Road, Carlisle, KY 40311</p>
                        <p class="text-white/80 mb-2">Phone: <a href="tel:+18594732259" class="text-secondary hover:text-secondary/80">859.473.2259</a></p>
                        <p class="text-white/80">Email: <a href="mailto:jblandandhomerealty@gmail.com" class="text-secondary hover:text-secondary/80">jblandandhomerealty@gmail.com</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
