@extends('components.layouts.guest')
@section('content')

<!-- Premium About Hero Section -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Video -->
    <div class="absolute inset-0 z-0">
        <video 
            autoplay 
            loop 
            muted 
            playsinline 
            class="w-full h-full object-cover"
        >
            <source src="{{ asset('videos/hero_alt.mp4') }}" type="video/mp4">
        </video>
        <!-- Cinematic Overlay -->
        <div class="absolute inset-0 cinematic-overlay"></div>
        <div class="absolute inset-0 vignette-overlay"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 text-center px-6 lg:px-8 max-w-6xl mx-auto">
        <!-- Premium Badge -->
        <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">About JB Land & Home</span>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
        </div>

        <!-- Mixed Typography Headline -->
        <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
            <h1 class="text-5xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                <span class="block text-white font-sans font-light tracking-wide">Your Land & Home</span>
                <span class="block text-secondary font-serif font-medium italic tracking-tight">
                    Specialist
                </span>
            </h1>
        </div>

        <!-- Elegant Subtitle -->
        <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
            <p class="text-xl md:text-2xl text-white/80 font-light leading-relaxed font-sans tracking-wide mb-12">
                Where deep local expertise meets unmatched dedication to finding your perfect property
            </p>
        </div>

        <!-- Premium CTA Button -->
        <div class="opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="#story" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <span>Learn More</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- The JB Story Section -->
<section id="story" class="py-32 bg-gradient-to-br from-black via-primary to-black relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Content -->
            <div>
                <!-- Premium Badge -->
                <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                    <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                        <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">The JB Difference</span>
                    </div>
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                </div>
                
                <!-- Mixed Typography Headline -->
                <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                    <h2 class="text-4xl md:text-5xl mb-4 leading-tight">
                        <span class="block text-white font-sans font-light tracking-wide">From</span>
                        <span class="block text-secondary font-serif font-medium italic tracking-tight">
                            Carlisle, Kentucky
                        </span>
                    </h2>
                </div>
                
                <!-- Story Content -->
                <div class="space-y-6 opacity-0 fade-in-up fade-in-up-delay-2">
                    <p class="text-lg text-white/80 font-light leading-relaxed">
                        Jeremiah Brown isn't just another real estate agent – he's a true local expert from Carlisle, Kentucky who understands the unique character of Kentucky. As Principal Broker of JB Land & Home Realty, he brings deep regional knowledge and genuine passion for helping people find their perfect property.
                    </p>
                    
                    <p class="text-lg text-white/80 font-light leading-relaxed">
                        Jeremiah has built his reputation on understanding that buying land is about more than just a transaction – it's about finding the right fit for your lifestyle, dreams, and future. Whether you're seeking recreational hunting land, a productive farm, or the perfect homesite, he takes the time to truly understand your vision.
                    </p>
                    
                    <p class="text-lg text-white/80 font-light leading-relaxed">
                        What sets Jeremiah apart is his commitment to personalized service and his extensive network throughout the region. He doesn't just list properties – he matches people with land that will become part of their story for generations to come.
                    </p>
                </div>
            </div>
            
            <!-- Image -->
            <div class="opacity-0 fade-in-up fade-in-up-delay-1">
                <div class="relative">
                    <img 
                        src="{{ asset('images/Jeremiah_Headshot.JPEG') }}" 
                        alt="Jeremiah Brown - Principal Broker" 
                        class="w-full h-96 lg:h-[500px] object-cover rounded-3xl shadow-2xl"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-3xl"></div>
                    
                    <!-- Floating Stats -->
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="bg-black/60 backdrop-blur-xl border border-white/20 rounded-2xl p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-serif text-secondary mb-1">Licensed</div>
                                    <div class="text-xs text-white/70 uppercase tracking-wide">Principal Broker</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-serif text-secondary mb-1">Expert</div>
                                    <div class="text-xs text-white/70 uppercase tracking-wide">Land and Home Specialist</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose JB Section - Subtly incorporating marketing principles -->
<section class="py-32 bg-black relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 60px 60px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Why Choose JB</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Three Pillars of</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Excellence
                    </span>
                </h2>
            </div>
        </div>

        <!-- Three Pillars Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Pillar 1: Expertise & Service (Product) -->
            <div class="group relative opacity-0 fade-in-up">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105 h-full">
                    <!-- Icon -->
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="text-2xl font-serif font-medium text-white mb-6">Unmatched Expertise</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-6">
                        Jeremiah doesn't just know the market – he lives and breathes Kentucky real estate. With deep knowledge of land values, property potential, and local opportunities, you get insights that come from years of dedicated focus on rural and recreational properties.
                    </p>
                    <ul class="space-y-3 text-white/60 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Principal Broker License
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Kentucky Expertise
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Personalized Service
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Pillar 2: Fair Value & Honest Pricing (Price) -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105 h-full">
                    <!-- Icon -->
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="text-2xl font-serif font-medium text-white mb-6">Honest Value</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-6">
                        No games, no inflated prices, no hidden surprises. Jeremiah believes in transparent, fair pricing that reflects true market value. You'll always know exactly what you're getting and why it's priced the way it is.
                    </p>
                    <ul class="space-y-3 text-white/60 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Transparent Market Analysis
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            No Hidden Fees
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Maximum Value Delivered
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Pillar 3: Local Presence & Wide Reach (Place/Promotion) -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105 h-full">
                    <!-- Icon -->
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="text-2xl font-serif font-medium text-white mb-6">Connected Community</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-6">
                        Deeply rooted locally but connected regionally. Jeremiah leverages both his community relationships and modern marketing to ensure your property gets maximum exposure to the right buyers, wherever they may be.
                    </p>
                    <ul class="space-y-3 text-white/60 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Deep Local Network
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Multi-Platform Marketing
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-secondary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Regional & National Reach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Personal Touch Section -->
<section class="py-32 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 80px 80px;"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Image -->
            <div class="opacity-0 fade-in-up">
                <img 
                    src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=600&h=400&auto=format&fit=crop&ixlib=rb-4.0.3" 
                    alt="Carter County Kentucky Landscape" 
                    class="w-full h-80 object-cover rounded-3xl shadow-2xl"
                >
            </div>
            
            <!-- Content -->
            <div>
                <!-- Premium Badge -->
                <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                    <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                        <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Personal Commitment</span>
                    </div>
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                </div>
                
                <!-- Mixed Typography Headline -->
                <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                    <h2 class="text-4xl md:text-5xl mb-4 leading-tight">
                        <span class="block text-white font-sans font-light tracking-wide">More Than</span>
                        <span class="block text-secondary font-serif font-medium italic tracking-tight">
                            Just Business
                        </span>
                    </h2>
                </div>
                
                <!-- Personal Content -->
                <div class="space-y-6 opacity-0 fade-in-up fade-in-up-delay-2">
                    <p class="text-lg text-white/80 font-light leading-relaxed">
                        When you work with Jeremiah, you're not just getting a real estate agent – you're getting a dedicated professional who genuinely cares about your success. He takes the time to understand not just what you want, but why you want it.
                    </p>
                    
                    <p class="text-lg text-white/80 font-light leading-relaxed">
                        Whether it's that perfect hunting spot where you'll make memories with your kids, the farm that will support your family's dreams, or the waterfront retreat where you'll find peace – Jeremiah understands that land isn't just an investment, it's the foundation of your future.
                    </p>
                    
                    <!-- Quote -->
                    <div class="border-l-4 border-secondary pl-6 my-8">
                        <blockquote class="text-xl text-white/90 font-light italic leading-relaxed">
                            "I don't just sell land – I help families find their place in this community. That's the difference between a transaction and a relationship."
                        </blockquote>
                        <cite class="text-secondary font-medium text-sm tracking-wide mt-3 block">— Jeremiah Brown</cite>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-32 bg-black relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 100px 100px;"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-6 lg:px-8 text-center">
        <!-- Section Header -->
        <div class="mb-16">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Ready to Get Started?</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Let's Find Your</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Perfect Property
                    </span>
                </h2>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                    Ready to experience the JB difference? Let's start the conversation about your property goals.
                </p>
            </div>
        </div>
        
        <!-- Premium CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="#" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <span>Contact Jeremiah Today</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('agents') }}" class="group inline-flex items-center bg-black/30 hover:bg-black/50 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm">
                <div class="relative flex items-center">
                    <span>View Properties</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Contact Information -->
        <div class="mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
            <div class="flex items-center justify-center mb-8">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                <div class="mx-8">
                    <div class="w-2 h-2 bg-secondary rounded-full"></div>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
            </div>
            <p class="text-white/80 text-lg font-light mb-2">Call Jeremiah directly</p>
            <p class="text-secondary text-xl font-light">
                <a href="tel:+18594732259" class="hover:text-secondary/80 transition-colors duration-300 underline decoration-secondary/30 hover:decoration-secondary/60">859.473.2259</a>
            </p>
        </div>
    </div>
</section>

@endsection
