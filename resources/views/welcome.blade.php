@extends('components.layouts.guest')
@section('content')
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

        <!-- Dynamic Featured Properties Grid -->
        @if($featuredProperties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($featuredProperties as $index => $property)
                    <!-- Property Card {{ $index + 1 }} - {{ $property->title }} -->
                    <div class="group relative opacity-0 fade-in-up {{ $index == 0 ? '' : ($index == 1 ? 'fade-in-up-delay-1' : 'fade-in-up-delay-2') }}">
                <!-- Subtle Gold Glow -->
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                
                <!-- Main Card -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                        <!-- Property Image -->
                        <div class="h-80 relative overflow-hidden">
                            @if($property->images->count() > 0)
                                <img 
                                    src="{{ $property->images->first()->url }}" 
                                    alt="{{ $property->images->first()->alt_text ?? $property->title }}" 
                                    class="w-full h-full object-cover"
                                    loading="{{ $index == 0 ? 'eager' : 'lazy' }}"
                                >
                    @else
                                <!-- Fallback gradient if no image -->
                                <div class="w-full h-full bg-gradient-to-br from-green-600 via-green-700 to-green-800"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-6 left-6">
                                @if($property->created_at->diffInDays() <= 7)
                                    <div class="px-4 py-1.5 bg-secondary/90 backdrop-blur-sm text-black rounded-full text-xs font-medium tracking-wide uppercase">
                                        New Listing
                                    </div>
                                @elseif($property->featured)
                                    <div class="px-4 py-1.5 bg-green-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                        Featured
                                    </div>
                                @else
                                    <div class="px-4 py-1.5 bg-blue-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                        {{ ucfirst($property->property_type ?? 'Land') }}
                                    </div>
                        @endif
                            </div>
                            
                            <!-- Property Title Overlay -->
                            <div class="absolute bottom-6 left-6 right-6">
                                <h3 class="text-2xl font-serif font-medium text-white mb-2 line-clamp-2">
                                    {{ Str::limit($property->title, 40) }}
                                </h3>
                                <div class="flex items-center text-white/90 text-sm font-light">
                                    <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $property->total_acres }}± acres • {{ $property->county ?? 'Kentucky' }}</span>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Card Content -->
                        <div class="p-8">
                            <!-- Price & Acreage -->
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-3xl font-serif text-secondary">{{ $property->formatted_price }}</span>
                                <div class="text-right">
                                    <div class="text-white/60 text-xs uppercase tracking-wide font-light">Starting at</div>
                                    <div class="text-white/80 font-medium">{{ $property->total_acres }}± Acres</div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-white/70 font-light leading-relaxed mb-8 text-sm line-clamp-3">
                                {{ Str::limit($property->description, 150) }}
                            </p>
                            
                            <!-- Premium CTA Button -->
                            <a href="{{ route('properties.show', $property) }}" class="group/btn block w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium tracking-wide transition-all duration-300 backdrop-blur-sm text-center">
                                <span class="flex items-center justify-center">
                                    <span>View Details</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- No Properties with Images -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <div class="w-16 h-16 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-serif text-white mb-2">Featured Properties Coming Soon</h3>
                    <p class="text-white/70 text-sm">We're currently preparing our featured properties with professional photography.</p>
                </div>
            </div>
        @endif


        <!-- Premium View All CTA -->
        <div class="text-center mt-16 opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="{{ route('properties.index') }}" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
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
                        "JB helped us find the perfect hunting property in Kentucky. Their knowledge of the local area was invaluable, and they made the entire process smooth and stress-free."
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
                    Ready to start your land and home journey? Our local experts in Kentucky are here to help.
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
                                    <a href="tel:+18594732259" class="text-secondary hover:text-secondary/80 font-medium text-lg tracking-wide transition-colors duration-300">
                                        859.473.2259
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
                                    <a href="mailto:jblandandhomerealty@gmail.com" class="text-secondary hover:text-secondary/80 font-medium text-lg tracking-wide transition-colors duration-300">
                                        jblandandhomerealty@gmail.com
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
                                    <p class="text-white/70 text-sm mb-3">Serving Kentucky</p>
                                    <div class="text-secondary font-medium text-lg tracking-wide">
                                        <div>4629 Maysville Road</div>
                                        <div>Carlisle, KY, United States, 40311</div>
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
                                    placeholder="859.473.2259"
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
            <p class="text-secondary text-base font-light">Call us at <a href="tel:+18594732259" class="hover:text-secondary/80 transition-colors duration-300 underline decoration-secondary/30 hover:decoration-secondary/60">859.473.2259</a> for immediate assistance.</p>
        </div>
    </div>
</section>
@endsection