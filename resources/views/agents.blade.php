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
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Our Expert Team</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Meet Our</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Agents
                    </span>
                </h2>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                    Our experienced team combines decades of local expertise with a passion for exceptional service
                </p>
            </div>
        </div>

        <!-- Team Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <!-- Agent Card 1 - Principal Broker -->
            <div class="group relative opacity-0 fade-in-up">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="John Bennett - Principal Broker" 
                            class="w-full h-full object-cover"
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
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">Jeremiah Brown</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>jeremiah@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">License</div>
                                <div class="text-white/80 font-medium">Principal Broker</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">Land & Farms</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            As Principal Broker of JB Land & Home Realty, Jeremiah specializes in hunting land, farms, and rural properties throughout Eastern Kentucky. His dedication to personalized service and deep regional expertise make him a trusted advisor.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Card 2 - Senior Agent -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1494790108755-2616b612b786?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="Sarah Mitchell - Senior Agent" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-emerald-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                Senior Agent
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">Sarah Mitchell</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>sarah@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Experience</div>
                                <div class="text-white/80 font-medium">15+ Years</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">Residential</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            Sarah brings extensive experience in residential properties and waterfront homes. Her attention to detail and client-focused approach have earned her recognition as a top performer in the region.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Card 3 - Land Specialist -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="Mike Thompson - Land Specialist" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-blue-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                Land Specialist
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">Mike Thompson</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>mike@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Experience</div>
                                <div class="text-white/80 font-medium">12+ Years</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">Hunting Land</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            An avid outdoorsman himself, Mike understands what hunters and outdoor enthusiasts are looking for. He specializes in recreational land, timber properties, and hunting tracts throughout Eastern Kentucky.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Card 4 - Farm Specialist -->
            <div class="group relative opacity-0 fade-in-up">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="Lisa Carter - Farm Specialist" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-green-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                Farm Specialist
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">Lisa Carter</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>lisa@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Experience</div>
                                <div class="text-white/80 font-medium">18+ Years</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">Agricultural</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            Growing up on a family farm, Lisa has an intimate understanding of agricultural properties. She specializes in working farms, pastureland, and properties suitable for livestock operations.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Card 5 - Commercial Specialist -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="David Wilson - Commercial Specialist" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-purple-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                Commercial
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">David Wilson</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>david@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Experience</div>
                                <div class="text-white/80 font-medium">20+ Years</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">Commercial</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            David brings extensive commercial real estate experience to our team. He specializes in retail spaces, office buildings, and development opportunities throughout Eastern Kentucky and surrounding areas.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Card 6 - New Agent -->
            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Agent Photo -->
                    <div class="h-80 relative overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=400&h=600&auto=format&fit=crop&ixlib=rb-4.0.3" 
                            alt="Jennifer Adams - Associate Agent" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6">
                            <div class="px-4 py-1.5 bg-orange-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                Associate Agent
                            </div>
                        </div>
                        
                        <!-- Agent Name Overlay -->
                        <div class="absolute bottom-6 left-6 right-6">
                            <h3 class="text-2xl font-serif font-medium text-white mb-2">Jennifer Adams</h3>
                            <div class="flex items-center text-white/90 text-sm font-light">
                                <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>jennifer@jblandhome.com</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-8">
                        <!-- Experience & Specialization -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Experience</div>
                                <div class="text-white/80 font-medium">5+ Years</div>
                            </div>
                            <div class="text-right">
                                <div class="text-secondary/80 text-xs uppercase tracking-wide font-light">Specialization</div>
                                <div class="text-white/80 font-medium">First-Time Buyers</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                            Jennifer is passionate about helping first-time buyers find their dream property. Her patient approach and thorough market knowledge make the buying process smooth and stress-free for her clients.
                        </p>
                        
                        <!-- Contact Buttons -->
                        <div class="flex gap-3">
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Call</span>
                                </span>
                            </button>
                            <button class="group/btn flex-1 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-4 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 backdrop-blur-sm">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Email</span>
                                </span>
                            </button>
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
            <a href="#" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <span>Contact Our Team</span>
                    <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </a>
            
            <a href="#" class="group inline-flex items-center bg-black/30 hover:bg-black/50 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm">
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
            <p class="text-white/80 text-lg font-light mb-2">Or call us directly</p>
            <p class="text-secondary text-xl font-light">
                <a href="tel:+16065551234" class="hover:text-secondary/80 transition-colors duration-300 underline decoration-secondary/30 hover:decoration-secondary/60">(606) 555-1234</a>
            </p>
        </div>
    </div>
</section>

@endsection
