@extends('components.layouts.guest')
@section('content')

<!-- Premium Agents Hero Section -->
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
            <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
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
                <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Meet Our Team</span>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
        </div>

        <!-- Mixed Typography Headline -->
        <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
            <h1 class="text-5xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                <span class="block text-white font-sans font-light tracking-wide">Expert</span>
                <span class="block text-secondary font-serif font-medium italic tracking-tight">
                    Land Specialists
                </span>
            </h1>
        </div>

        <!-- Elegant Subtitle -->
        <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
            <p class="text-xl md:text-2xl text-white/80 font-light leading-relaxed font-sans tracking-wide mb-12">
                Dedicated professionals with deep local knowledge and a passion for helping you find your perfect property
            </p>
        </div>

        <!-- Premium CTA Button -->
        <div class="opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="#team" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <span>Meet Our Team</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Decorative Separator -->
        <div class="flex items-center justify-center mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
            <div class="mx-8">
                <div class="w-2 h-2 bg-secondary rounded-full"></div>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
        </div>
    </div>
</section>

<!-- Premium Team Section -->
<section id="team" class="py-32 bg-black relative overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Principal Broker</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Meet Your</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Agent
                    </span>
                </h2>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                    Your dedicated partner in finding the perfect rural property with deep local knowledge and personalized service
                </p>
            </div>
        </div>

        <!-- Single Agent Feature -->
        <div class="max-w-2xl mx-auto">
            <!-- Agent Card - Jeremiah Brown -->
            <div class="group relative opacity-0 fade-in-up">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-96 relative overflow-hidden">
                        <img 
                            src="{{ asset('images/Jeremiah_Headshot.JPEG') }}" 
                            alt="Jeremiah Brown - Principal Broker" 
                            class="w-full h-full object-cover object-top"
                            style="object-position: center 20%;"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-secondary/90 backdrop-blur-sm text-black rounded-full text-xs font-medium tracking-wide uppercase">
                                Principal Broker
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-3xl font-serif font-medium text-white mb-3">Jeremiah Brown</h3>
                            <div class="space-y-2">
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                    <span>jeremiahbbrown1997@gmail.com</span>
                        </div>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012 3.5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>859-473-2259</span>
                        </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="text-center">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light mb-1">License</div>
                                <div class="text-white/90 font-medium">Principal Broker</div>
                            </div>
                            <div class="text-center">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light mb-1">Specialization</div>
                                <div class="text-white/90 font-medium">Land & Rural Properties</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium text-white mb-4">About Jeremiah</h4>
                            <p class="text-white/70 font-light leading-relaxed text-sm mb-4">
                                As the Principal Broker and founder of JB Land & Home Realty, Jeremiah Brown brings a deep passion for rural properties and extensive knowledge of Eastern Kentucky's real estate market. He specializes in hunting land, farms, recreational properties, and rural residential homes throughout the region.
                            </p>
                            <p class="text-white/70 font-light leading-relaxed text-sm">
                                Jeremiah's commitment to personalized service and his understanding of what makes rural properties special have made him a trusted advisor for buyers and sellers alike. Whether you're looking for your dream hunting retreat, a working farm, or a peaceful rural home, Jeremiah has the expertise to help you find exactly what you're looking for.
                            </p>
                        </div>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-4">
                            <a href="tel:859-473-2259" class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-6 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call Now</span>
                                </span>
                            </a>
                            <a href="mailto:jeremiahbbrown1997@gmail.com" class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-6 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Send Email</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Premium Contact CTA Section -->
<section class="py-32 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 60px 60px;"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-6 lg:px-8 text-center">
        <!-- Section Header -->
        <div class="mb-16">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Ready to Work Together?</span>
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
                    Our expert team is ready to help you navigate Eastern Kentucky's real estate market. 
                    Contact us today to get started on your property journey.
                </p>
            </div>
        </div>
        
        <!-- Premium CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="mailto:jeremiahbbrown1997@gmail.com" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <span>Contact Jeremiah</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('properties.index') }}" class="group inline-flex items-center bg-black/30 hover:bg-black/50 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm">
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
            <p class="text-white/80 text-lg font-light mb-2">Or call Jeremiah directly</p>
            <p class="text-secondary text-xl font-light">
                <a href="tel:8594732259" class="hover:text-secondary/80 transition-colors duration-300 underline decoration-secondary/30 hover:decoration-secondary/60">(859) 473-2259</a>
            </p>
        </div>
    </div>
</section>

@endsection
