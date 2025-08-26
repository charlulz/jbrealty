<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>JB Land & Home Realty - Find Your Favorite Place</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-black text-gray-900 antialiased overflow-x-hidden">
        
        <!-- Include Navigation Header -->
        @include('components.nav-header')

        <!-- Include Hero Section -->
        @include('components.hero-section')

        <!-- Premium Featured Properties Section -->
        <section class="py-32 bg-black relative overflow-hidden">
            <!-- Subtle Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Refined Section Header -->
                <div class="text-center mb-20">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Handpicked Properties</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-16"></div>
                    </div>
                    
                    <!-- Mixed Typography Headline -->
                    <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                        <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                            <span class="block text-white font-sans font-light tracking-wide">Featured</span>
                            <span class="block text-secondary font-serif font-medium italic tracking-tight">
                                Properties
                            </span>
                        </h2>
                    </div>
                    
                    <!-- Elegant Subtitle -->
                    <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                        <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                            Discover exceptional land and rural properties handpicked by our expert team
                        </p>
                    </div>
                    
                    <!-- Decorative Separator -->
                    <div class="flex items-center justify-center mt-12 opacity-0 fade-in-up fade-in-up-delay-3">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                        <div class="mx-8">
                            <div class="w-2 h-2 bg-secondary rounded-full"></div>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                    </div>
                </div>

                <!-- Premium Property Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <!-- Property Card 1 - Hunter's Paradise -->
                    <div class="group relative opacity-0 fade-in-up">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                            <!-- Property Image -->
                            <div class="h-80 bg-gradient-to-br from-green-500 to-green-700 relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-6 left-6">
                                    <div class="px-4 py-1.5 bg-secondary/90 backdrop-blur-sm text-black rounded-full text-xs font-medium tracking-wide uppercase">
                                        New Listing
                                    </div>
                                </div>
                                
                                <!-- Property Title Overlay -->
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-2xl font-serif font-medium text-white mb-2">Hunter's Paradise</h3>
                                    <div class="flex items-center text-white/90 text-sm font-light">
                                        <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>124 acres • Madison County, VA</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Content -->
                            <div class="p-8">
                                <!-- Price & Acreage -->
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-3xl font-serif text-secondary">$249,900</span>
                                    <div class="text-right">
                                        <div class="text-white/60 text-xs uppercase tracking-wide font-light">Starting at</div>
                                        <div class="text-white/80 font-medium">124± Acres</div>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                                    Prime hunting land with mature timber, multiple food plots, and excellent deer population. 
                                    Features include cabin, pond, and ATV trails throughout.
                                </p>
                                
                                <!-- Premium CTA Button -->
                                <button class="group/btn w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium tracking-wide transition-all duration-300 backdrop-blur-sm">
                                    <span class="flex items-center justify-center">
                                        <span>View Details</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Property Card 2 - Historic Farm -->
                    <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                            <!-- Property Image -->
                            <div class="h-80 bg-gradient-to-br from-amber-500 to-amber-700 relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-6 left-6">
                                    <div class="px-4 py-1.5 bg-red-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                        Price Reduced
                                    </div>
                                </div>
                                
                                <!-- Property Title Overlay -->
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-2xl font-serif font-medium text-white mb-2">Historic Farm</h3>
                                    <div class="flex items-center text-white/90 text-sm font-light">
                                        <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>89 acres • Fauquier County, VA</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Content -->
                            <div class="p-8">
                                <!-- Price & Acreage -->
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-3xl font-serif text-secondary">$425,000</span>
                                    <div class="text-right">
                                        <div class="text-white/60 text-xs uppercase tracking-wide font-light">Starting at</div>
                                        <div class="text-white/80 font-medium">89± Acres</div>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                                    Beautiful historic farmhouse on rolling pastures with mountain views. 
                                    Perfect for horses, cattle, or recreational use. Includes barn and outbuildings.
                                </p>
                                
                                <!-- Premium CTA Button -->
                                <button class="group/btn w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium tracking-wide transition-all duration-300 backdrop-blur-sm">
                                    <span class="flex items-center justify-center">
                                        <span>View Details</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Property Card 3 - Waterfront Estate -->
                    <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                            <!-- Property Image -->
                            <div class="h-80 bg-gradient-to-br from-blue-500 to-blue-700 relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
                                
                                <!-- Property Title Overlay -->
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-2xl font-serif font-medium text-white mb-2">Waterfront Estate</h3>
                                    <div class="flex items-center text-white/90 text-sm font-light">
                                        <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>45 acres • Rappahannock River</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Content -->
                            <div class="p-8">
                                <!-- Price & Acreage -->
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-3xl font-serif text-secondary">$875,000</span>
                                    <div class="text-right">
                                        <div class="text-white/60 text-xs uppercase tracking-wide font-light">Starting at</div>
                                        <div class="text-white/80 font-medium">45± Acres</div>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <p class="text-white/70 font-light leading-relaxed mb-8 text-sm">
                                    Stunning waterfront property with 1,200 feet of river frontage. 
                                    Custom home, private dock, and pristine natural setting perfect for fishing and boating.
                                </p>
                                
                                <!-- Premium CTA Button -->
                                <button class="group/btn w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium tracking-wide transition-all duration-300 backdrop-blur-sm">
                                    <span class="flex items-center justify-center">
                                        <span>View Details</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium View All CTA -->
                <div class="text-center mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
                    <a href="#" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative flex items-center">
                            <span>View All Properties</span>
                            <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Premium Call to Action Section -->
        <section class="py-32 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 60px 60px;"></div>
            </div>
            
            <div class="relative max-w-6xl mx-auto px-6 lg:px-8 text-center">
                <!-- Refined Section Header -->
                <div class="mb-16">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Get Started Today</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                    </div>
                    
                    <!-- Mixed Typography Headline -->
                    <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                        <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                            <span class="block text-white font-sans font-light tracking-wide">Ready to Find Your</span>
                            <span class="block text-secondary font-serif font-medium italic tracking-tight">
                                Perfect Property?
                            </span>
                        </h2>
                    </div>
                    
                    <!-- Elegant Subtitle -->
                    <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                        <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                            Let our experienced team help you discover the land of your dreams. Whether buying or selling, 
                            we're here to make it happen.
                        </p>
                    </div>
                </div>
                
                <!-- Premium CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center opacity-0 fade-in-up fade-in-up-delay-3">
                    <a href="#" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative flex items-center">
                            <span>Browse Properties</span>
                            <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </a>
                    
                    <a href="#" class="group inline-flex items-center bg-black/30 hover:bg-black/50 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm">
                        <div class="relative flex items-center">
                            <span>Sell Your Property</span>
                            <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Premium Footer -->
        <footer class="bg-black border-t border-secondary/10 py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center">
                    <!-- Logo & Company Info -->
                    <div class="mb-8">
                        <img 
                            src="{{ asset('images/logo.jpg') }}" 
                            alt="JB Land & Home Realty Logo" 
                            class="w-16 h-16 rounded-xl object-cover shadow-lg ring-2 ring-secondary/20 mx-auto mb-4"
                        >
                        <h3 class="text-xl font-medium text-white font-sans mb-1">JB Land & Home</h3>
                        <p class="text-sm text-secondary font-light tracking-widest uppercase">Looking for ground?</p>
                    </div>
                    
                    <!-- Decorative Line -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-md"></div>
                    </div>
                    
                    <!-- Copyright -->
                    <div class="text-sm text-white/60 font-light">
                        <p>&copy; {{ date('Y') }} JB Land & Home Realty. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
