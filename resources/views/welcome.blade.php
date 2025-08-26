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
                            <div class="h-80 relative overflow-hidden">
                                <img 
                                    src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=2560&h=1440&auto=format&fit=crop&ixlib=rb-4.0.3" 
                                    alt="Hunter's Paradise - Hunting Land with Forest" 
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                                
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
                                        <span>124 acres • Carter County, KY</span>
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
                            <div class="h-80 relative overflow-hidden">
                                <img 
                                    src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2560&h=1440&auto=format&fit=crop&ixlib=rb-4.0.3" 
                                    alt="Historic Farm - Rolling Pastures and Farmland" 
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                                
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
                                        <span>89 acres • Carter County, KY</span>
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
                            <div class="h-80 relative overflow-hidden">
                                <img 
                                    src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=2560&h=1440&auto=format&fit=crop&ixlib=rb-4.0.3" 
                                    alt="Waterfront Estate - Lake Property with Dock" 
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                                
                                <!-- Property Title Overlay -->
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-2xl font-serif font-medium text-white mb-2">Waterfront Estate</h3>
                                    <div class="flex items-center text-white/90 text-sm font-light">
                                        <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>45 acres • Carter County, KY</span>
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

        <!-- Premium Client Testimonials Section -->
        <section class="py-32 bg-gradient-to-br from-black via-primary to-black relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 80px 80px;"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Client Success Stories</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                    </div>
                    
                    <!-- Mixed Typography Headline -->
                    <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                        <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                            <span class="block text-white font-sans font-light tracking-wide">What Our</span>
                            <span class="block text-secondary font-serif font-medium italic tracking-tight">
                                Clients Say
                            </span>
                        </h2>
                    </div>
                    
                    <!-- Elegant Subtitle -->
                    <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                        <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                            Don't just take our word for it. See what our clients have to say about their land buying experience.
                        </p>
                    </div>
                </div>

                <!-- Testimonials Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <!-- Testimonial 1 -->
                    <div class="group relative opacity-0 fade-in-up">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Testimonial Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-secondary/30 transition-all duration-500">
                            <!-- Quote Icon -->
                            <div class="mb-6">
                                <svg class="w-8 h-8 text-secondary/60" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>
                            
                            <!-- Testimonial Content -->
                            <blockquote class="text-white/90 font-light leading-relaxed mb-8 text-lg">
                                "JB helped us find the perfect hunting property in Carter County. Their knowledge of the local area was invaluable, and they made the entire process smooth and stress-free."
                            </blockquote>
                            
                            <!-- Client Info -->
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-secondary font-serif font-medium text-lg">MJ</span>
                                </div>
                                <div>
                                    <div class="text-white font-medium">Mike Johnson</div>
                                    <div class="text-secondary/80 text-sm font-light">Hunter's Paradise Owner</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Testimonial Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-secondary/30 transition-all duration-500">
                            <!-- Quote Icon -->
                            <div class="mb-6">
                                <svg class="w-8 h-8 text-secondary/60" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>
                            
                            <!-- Testimonial Content -->
                            <blockquote class="text-white/90 font-light leading-relaxed mb-8 text-lg">
                                "We sold our farm through JB Land & Home and couldn't be happier. They understood the value of our property and got us exactly what we were looking for."
                            </blockquote>
                            
                            <!-- Client Info -->
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-secondary font-serif font-medium text-lg">SW</span>
                                </div>
                                <div>
                                    <div class="text-white font-medium">Sarah Williams</div>
                                    <div class="text-secondary/80 text-sm font-light">Farm Seller</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                        
                        <!-- Testimonial Card -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 hover:border-secondary/30 transition-all duration-500">
                            <!-- Quote Icon -->
                            <div class="mb-6">
                                <svg class="w-8 h-8 text-secondary/60" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>
                            
                            <!-- Testimonial Content -->
                            <blockquote class="text-white/90 font-light leading-relaxed mb-8 text-lg">
                                "Professional, knowledgeable, and truly cares about finding the right property for your needs. JB made our waterfront dream a reality."
                            </blockquote>
                            
                            <!-- Client Info -->
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-secondary font-serif font-medium text-lg">DR</span>
                                </div>
                                <div>
                                    <div class="text-white font-medium">David Roberts</div>
                                    <div class="text-secondary/80 text-sm font-light">Waterfront Property Owner</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
                    <div class="flex items-center justify-center mb-8">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                        <div class="mx-8">
                            <div class="w-2 h-2 bg-secondary rounded-full"></div>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                    </div>
                    <p class="text-white/80 text-lg font-light mb-6">Ready to join our satisfied clients?</p>
                    <a href="#" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative flex items-center">
                            <span>Start Your Search</span>
                            <svg class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Premium Contact Section -->
        <section class="py-32 bg-black relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 100px 100px;"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/20 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Get In Touch</span>
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
                            Ready to start your land and home journey? Our local experts in Carter County are here to help.
                        </p>
                    </div>
                </div>

                <!-- Contact Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                    <!-- Contact Information -->
                    <div class="space-y-12">
                        <!-- Contact Cards -->
                        <div class="space-y-8">
                            <!-- Phone Card -->
                            <div class="group relative opacity-0 fade-in-up">
                                <!-- Subtle Gold Glow -->
                                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                                
                                <!-- Card Content -->
                                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-500">
                                    <div class="flex items-start">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-xl flex items-center justify-center mr-6">
                                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div>
                                            <h3 class="text-white font-medium text-lg mb-2">Call Us</h3>
                                            <p class="text-white/70 text-sm mb-3">Speak directly with our land specialists</p>
                                            <a href="tel:+16065551234" class="text-secondary hover:text-secondary/80 font-medium text-lg tracking-wide transition-colors duration-300">
                                                (606) 555-1234
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Card -->
                            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                                <!-- Subtle Gold Glow -->
                                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                                
                                <!-- Card Content -->
                                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-500">
                                    <div class="flex items-start">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-xl flex items-center justify-center mr-6">
                                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div>
                                            <h3 class="text-white font-medium text-lg mb-2">Email Us</h3>
                                            <p class="text-white/70 text-sm mb-3">Send us your property requirements</p>
                                            <a href="mailto:info@jblandhome.com" class="text-secondary hover:text-secondary/80 font-medium text-lg tracking-wide transition-colors duration-300">
                                                info@jblandhome.com
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Card -->
                            <div class="group relative opacity-0 fade-in-up fade-in-up-delay-2">
                                <!-- Subtle Gold Glow -->
                                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                                
                                <!-- Card Content -->
                                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-500">
                                    <div class="flex items-start">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-xl flex items-center justify-center mr-6">
                                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div>
                                            <h3 class="text-white font-medium text-lg mb-2">Visit Our Office</h3>
                                            <p class="text-white/70 text-sm mb-3">Located in the heart of Carter County</p>
                                            <div class="text-secondary font-medium text-lg tracking-wide">
                                                <div>123 Main Street</div>
                                                <div>Grayson, KY 41143</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="group relative opacity-0 fade-in-up fade-in-up-delay-3">
                            <!-- Subtle Gold Glow -->
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-2xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                            
                            <!-- Hours Content -->
                            <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-500">
                                <div class="flex items-start">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-secondary/20 to-secondary/40 rounded-xl flex items-center justify-center mr-6">
                                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1">
                                        <h3 class="text-white font-medium text-lg mb-4">Business Hours</h3>
                                        <div class="space-y-2 text-white/70">
                                            <div class="flex justify-between">
                                                <span>Monday - Friday</span>
                                                <span class="text-secondary">8:00 AM - 6:00 PM</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Saturday</span>
                                                <span class="text-secondary">9:00 AM - 4:00 PM</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Sunday</span>
                                                <span class="text-white/50">By Appointment</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="group relative opacity-0 fade-in-up fade-in-up-delay-1">
                        <!-- Subtle Gold Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-30 transition-opacity duration-700"></div>
                        
                        <!-- Form Container -->
                        <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 hover:border-secondary/30 transition-all duration-500">
                            <div class="mb-8">
                                <h3 class="text-2xl font-light text-white mb-3 font-sans tracking-wide">Send us a message</h3>
                                <p class="text-white/70 font-light">Tell us about your property needs and we'll get back to you within 24 hours.</p>
                            </div>

                            <form class="space-y-6" x-data="{ submitting: false }" @submit.prevent="submitting = true; setTimeout(() => submitting = false, 2000)">
                                <!-- Name & Email Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase mb-3">
                                            Full Name
                                        </label>
                                        <input 
                                            type="text" 
                                            name="name"
                                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300"
                                            placeholder="Enter your name"
                                            required
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase mb-3">
                                            Email Address
                                        </label>
                                        <input 
                                            type="email" 
                                            name="email"
                                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300"
                                            placeholder="Enter your email"
                                            required
                                        >
                                    </div>
                                </div>

                                <!-- Phone & Property Type Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase mb-3">
                                            Phone Number
                                        </label>
                                        <input 
                                            type="tel" 
                                            name="phone"
                                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300"
                                            placeholder="(606) 555-0000"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase mb-3">
                                            Property Interest
                                        </label>
                                        <select 
                                            name="property_type"
                                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300 appearance-none bg-no-repeat"
                                            style="background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'rgba(255,255,255,0.6)\' stroke-width=\'2\'><polyline points=\'6,9 12,15 18,9\'></polyline></svg>'); background-position: right 1rem center; background-size: 1rem;"
                                        >
                                            <option value="">Select property type</option>
                                            <option value="hunting">Hunting Land</option>
                                            <option value="farm">Farm & Agriculture</option>
                                            <option value="waterfront">Waterfront Property</option>
                                            <option value="residential">Residential Home</option>
                                            <option value="commercial">Commercial Property</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase mb-3">
                                        Tell us about your needs
                                    </label>
                                    <textarea 
                                        name="message"
                                        rows="5"
                                        class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300 resize-none"
                                        placeholder="Describe your ideal property, budget range, timeline, or any specific requirements..."
                                        required
                                    ></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button 
                                        type="submit"
                                        class="group w-full btn-premium text-black px-8 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-[1.02] shadow-xl hover:shadow-secondary/30 relative overflow-hidden"
                                        :disabled="submitting"
                                    >
                                        <!-- Shimmer Effect -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                                        
                                        <!-- Button Content -->
                                        <div class="relative flex items-center justify-center">
                                            <span x-show="!submitting">Send Message</span>
                                            <span x-show="submitting" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Sending...
                                            </span>
                                            <svg x-show="!submitting" class="ml-3 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bottom CTA -->
                <div class="text-center mt-20 opacity-0 fade-in-up fade-in-up-delay-3">
                    <div class="flex items-center justify-center mb-8">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                        <div class="mx-8">
                            <div class="w-2 h-2 bg-secondary rounded-full"></div>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/30 to-transparent w-full max-w-sm"></div>
                    </div>
                    <p class="text-white/80 text-lg font-light mb-2">Prefer to talk in person?</p>
                    <p class="text-secondary text-base font-light">Call us at <a href="tel:+16065551234" class="hover:text-secondary/80 transition-colors duration-300 underline decoration-secondary/30 hover:decoration-secondary/60">(606) 555-1234</a> for immediate assistance.</p>
                </div>
            </div>
        </section>

        <!-- Premium Footer -->
        <footer class="bg-gradient-to-br from-primary via-black to-primary border-t border-secondary/10 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 80px 80px;"></div>
            </div>

            <div class="relative">
                <!-- Main Footer Content -->
                <div class="py-20">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8">
                        <!-- Footer Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-16">
                            <!-- Company Info -->
                            <div class="lg:col-span-2">
                                <!-- Logo & Tagline -->
                                <div class="flex items-center mb-6">
                                    <img 
                                        src="{{ asset('images/logo.jpg') }}" 
                                        alt="JB Land & Home Realty Logo" 
                                        class="w-12 h-12 rounded-lg object-cover shadow-lg ring-2 ring-secondary/20 mr-4"
                                    >
                                    <div>
                                        <h3 class="text-2xl font-light text-white font-sans tracking-wide">JB Land & Home</h3>
                                        <p class="text-xs text-secondary font-light tracking-widest uppercase">Looking for ground?</p>
                                    </div>
                                </div>

                                <!-- Company Description -->
                                <p class="text-white/70 font-light leading-relaxed mb-8 max-w-md">
                                    Your trusted partner in Carter County real estate. Specializing in hunting land, farms, waterfront properties, and residential homes with over two decades of local expertise.
                                </p>

                                <!-- Newsletter Signup -->
                                <div class="mb-8">
                                    <h4 class="text-white font-medium mb-4 font-sans">Stay Updated</h4>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="flex-1">
                                            <input 
                                                type="email" 
                                                placeholder="Enter your email"
                                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-3 text-white/90 placeholder-white/40 focus:border-secondary/50 focus:ring-2 focus:ring-secondary/20 focus:outline-none transition-all duration-300"
                                            >
                                        </div>
                                        <button class="btn-premium text-black px-6 py-3 rounded-2xl font-medium text-sm tracking-wide transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-secondary/30 flex-shrink-0">
                                            Subscribe
                                        </button>
                                    </div>
                                </div>

                                <!-- Social Media -->
                                <div>
                                    <h4 class="text-white font-medium mb-4 font-sans">Follow Us</h4>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary/30 hover:bg-secondary/5 transition-all duration-300 group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary/30 hover:bg-secondary/5 transition-all duration-300 group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary/30 hover:bg-secondary/5 transition-all duration-300 group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary/30 hover:bg-secondary/5 transition-all duration-300 group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Links -->
                            <div>
                                <h4 class="text-white font-medium mb-6 font-sans tracking-wide">Quick Links</h4>
                                <ul class="space-y-4">
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Properties</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Hunting Land</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Farms</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Waterfront</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Residential</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">Sell With Us</a></li>
                                    <li><a href="#" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">About</a></li>
                                </ul>
                            </div>

                            <!-- Contact Info -->
                            <div>
                                <h4 class="text-white font-medium mb-6 font-sans tracking-wide">Contact</h4>
                                <ul class="space-y-4">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                        </svg>
                                        <div>
                                            <a href="tel:+16065551234" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">(606) 555-1234</a>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                        </svg>
                                        <div>
                                            <a href="mailto:info@jblandhome.com" class="text-white/70 hover:text-secondary font-light transition-colors duration-300">info@jblandhome.com</a>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                        </svg>
                                        <div class="text-white/70 font-light">
                                            <div>123 Main Street</div>
                                            <div>Grayson, KY 41143</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-secondary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="text-white/70 font-light">
                                            <div>Mon-Fri: 8am-6pm</div>
                                            <div>Sat: 9am-4pm</div>
                                            <div>Sun: By Appointment</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="border-t border-secondary/10 py-8">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8">
                        <div class="flex flex-col lg:flex-row items-center justify-between text-center lg:text-left">
                            <!-- Copyright -->
                            <div class="text-white/60 font-light text-sm mb-4 lg:mb-0">
                                <p>&copy; {{ date('Y') }} JB Land & Home Realty. All rights reserved.</p>
                            </div>

                            <!-- Legal Links -->
                            <div class="flex items-center space-x-8 text-sm">
                                <a href="#" class="text-white/60 hover:text-secondary font-light transition-colors duration-300">Privacy Policy</a>
                                <a href="#" class="text-white/60 hover:text-secondary font-light transition-colors duration-300">Terms of Service</a>
                                <a href="#" class="text-white/60 hover:text-secondary font-light transition-colors duration-300">Cookie Policy</a>
                            </div>

                            <!-- License Info -->
                            <div class="text-white/60 font-light text-sm mt-4 lg:mt-0">
                                <p>Licensed in Kentucky</p>
                            </div>
                        </div>

                        <!-- Decorative Separator -->
                        <div class="flex items-center justify-center mt-8">
                            <div class="h-px bg-gradient-to-r from-transparent via-secondary/20 to-transparent w-full max-w-xs"></div>
                            <div class="mx-6">
                                <div class="w-1.5 h-1.5 bg-secondary rounded-full"></div>
                            </div>
                            <div class="h-px bg-gradient-to-r from-transparent via-secondary/20 to-transparent w-full max-w-xs"></div>
                        </div>

                        <!-- Tagline -->
                        <div class="text-center mt-8">
                            <p class="text-secondary/60 font-light text-sm italic font-serif">
                                "Finding your perfect place in Carter County"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
