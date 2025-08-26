<!-- Refined Hero Section with Video Background -->
<section class="relative bg-black min-h-[75vh] flex items-center overflow-hidden" style="margin-top: -80px; padding-top: 80px;">
    <!-- Video Background -->
    <video 
        autoplay 
        muted 
        loop 
        playsinline
        class="absolute inset-0 w-full h-full object-cover"
    >
        <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
        <!-- Fallback gradient for browsers that don't support video -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-black to-gray-800"></div>
    </video>
    
    <!-- Cinematic Overlay System -->
    <div class="absolute inset-0 cinematic-overlay"></div>
    <div class="absolute inset-0 vignette-overlay"></div>
    
    <!-- Subtle Gold Accent Lines -->
    <div class="absolute top-20 left-1/2 transform -translate-x-1/2 w-24 h-px bg-gradient-to-r from-transparent via-secondary/60 to-transparent"></div>
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 w-24 h-px bg-gradient-to-r from-transparent via-secondary/60 to-transparent"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 z-10">
        <div class="text-center text-white mb-16">
            <!-- Refined Premium Badge -->
            <div class="inline-flex items-center mb-12 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-black/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Premium Properties</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
            </div>
            
            <!-- Refined Main Headline with Mixed Typography -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h1 class="text-5xl md:text-6xl lg:text-7xl xl:text-8xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Find Your</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Favorite Place
                    </span>
                </h1>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto mb-12 opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/90 font-light leading-relaxed mb-6 font-sans tracking-wide">
                    Discover exceptional hunting land, farms, ranches, and rural properties across the region
                </p>
                
                <!-- Refined Trust Indicators -->
                <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-4 text-secondary/70 text-xs font-light tracking-widest uppercase">
                    <span class="flex items-center">
                        <div class="w-1 h-1 bg-secondary rounded-full mr-3"></div>
                        Premium Listings
                    </span>
                    <span class="flex items-center">
                        <div class="w-1 h-1 bg-secondary rounded-full mr-3"></div>
                        Expert Guidance
                    </span>
                    <span class="flex items-center">
                        <div class="w-1 h-1 bg-secondary rounded-full mr-3"></div>
                        Proven Results
                    </span>
                </div>
            </div>
        </div>

        <!-- Property Search - Livewire Component -->
        @livewire('property-search')

        <!-- Refined Horizontal Stats Bar -->
        <div class="mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
            <!-- Elegant Separator -->
            <div class="flex items-center justify-center mb-12">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                <div class="mx-8">
                    <div class="w-2 h-2 bg-secondary rounded-full"></div>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
            </div>
            
            <!-- Sleek Horizontal Stats Bar -->
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-1 bg-black/20 backdrop-blur-sm border border-secondary/10 rounded-2xl p-1 overflow-hidden">
                    <div x-data="{ visible: false }" 
                         x-intersect="visible = true"
                         class="group relative bg-black/20 hover:bg-black/40 rounded-xl p-8 text-center transition-all duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-secondary/60 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                <span class="text-3xl lg:text-4xl font-serif text-secondary counter-animate" 
                                      x-text="visible ? '500+' : '0'">500+</span>
                            </div>
                            <div class="text-xs text-white/70 font-light tracking-widest uppercase">Properties</div>
                        </div>
                    </div>
                    
                    <div x-data="{ visible: false }" 
                         x-intersect="visible = true"
                         class="group relative bg-black/20 hover:bg-black/40 rounded-xl p-8 text-center transition-all duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-secondary/60 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-3xl lg:text-4xl font-serif text-secondary counter-animate" 
                                      x-text="visible ? '25+' : '0'">25+</span>
                            </div>
                            <div class="text-xs text-white/70 font-light tracking-widest uppercase">Years</div>
                        </div>
                    </div>
                    
                    <div x-data="{ visible: false }" 
                         x-intersect="visible = true"
                         class="group relative bg-black/20 hover:bg-black/40 rounded-xl p-8 text-center transition-all duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-secondary/60 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-3xl lg:text-4xl font-serif text-secondary counter-animate" 
                                      x-text="visible ? '98%' : '0'">98%</span>
                            </div>
                            <div class="text-xs text-white/70 font-light tracking-widest uppercase">Satisfaction</div>
                        </div>
                    </div>
                    
                    <div x-data="{ visible: false }" 
                         x-intersect="visible = true"
                         class="group relative bg-black/20 hover:bg-black/40 rounded-xl p-8 text-center transition-all duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-secondary/60 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-3xl lg:text-4xl font-serif text-secondary counter-animate" 
                                      x-text="visible ? '$50M+' : '0'">$50M+</span>
                            </div>
                            <div class="text-xs text-white/70 font-light tracking-widest uppercase">Sales Volume</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-secondary/60 animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</section>
