@extends('components.layouts.guest')
@section('content')

<!-- Premium Properties Hero Section -->
<section class="py-24 bg-black relative overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Available Properties</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h1 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Explore Our</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Property Listings
                    </span>
                </h1>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                    Discover exceptional land and rural properties throughout Kentucky's most desirable locations
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Premium Filter Section -->
<section class="py-16 bg-gradient-to-b from-black to-primary/20 relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Filter Navigation -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="{{ route('properties.index') }}" class="group px-6 py-3 rounded-full border {{ !request('type') && !request('featured') ? 'bg-secondary text-black border-secondary' : 'bg-black/30 text-white/80 border-white/20 hover:border-secondary hover:text-secondary' }} transition-all duration-300 backdrop-blur-sm">
                <span class="font-medium">All Properties</span>
            </a>
            <a href="{{ route('properties.index', ['type' => 'hunting']) }}" class="group px-6 py-3 rounded-full border {{ request('type') === 'hunting' ? 'bg-secondary text-black border-secondary' : 'bg-black/30 text-white/80 border-white/20 hover:border-secondary hover:text-secondary' }} transition-all duration-300 backdrop-blur-sm">
                <span class="font-medium">Hunting Land</span>
            </a>
            <a href="{{ route('properties.index', ['type' => 'farms']) }}" class="group px-6 py-3 rounded-full border {{ request('type') === 'farms' ? 'bg-secondary text-black border-secondary' : 'bg-black/30 text-white/80 border-white/20 hover:border-secondary hover:text-secondary' }} transition-all duration-300 backdrop-blur-sm">
                <span class="font-medium">Farms</span>
            </a>
            <a href="{{ route('properties.index', ['type' => 'waterfront']) }}" class="group px-6 py-3 rounded-full border {{ request('type') === 'waterfront' ? 'bg-secondary text-black border-secondary' : 'bg-black/30 text-white/80 border-white/20 hover:border-secondary hover:text-secondary' }} transition-all duration-300 backdrop-blur-sm">
                <span class="font-medium">Waterfront</span>
            </a>
            <a href="{{ route('properties.index', ['featured' => 1]) }}" class="group px-6 py-3 rounded-full border {{ request('featured') ? 'bg-secondary text-black border-secondary' : 'bg-black/30 text-white/80 border-white/20 hover:border-secondary hover:text-secondary' }} transition-all duration-300 backdrop-blur-sm">
                <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="font-medium">Featured</span>
            </a>
        </div>

        <!-- Premium Properties Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($properties as $property)
                <!-- Property Card -->
                <div class="group relative opacity-0 fade-in-up">
                    <!-- Subtle Gold Glow -->
                    <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                    
                    <!-- Main Card -->
                    <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden hover:border-secondary/30 transition-all duration-500 hover:transform hover:scale-105">
                        <!-- Property Image -->
                        <div class="h-64 relative overflow-hidden">
                            @if($property->images->first())
                                <img 
                                    src="{{ $property->images->first()->url }}" 
                                    alt="{{ $property->title }}" 
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-600 via-green-700 to-green-800 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-black/30"></div>
                            
                            <!-- Property Type Badge -->
                            <div class="absolute top-6 left-6">
                                <div class="px-4 py-1.5 bg-blue-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                    {{ ucfirst($property->property_type ?? 'Land') }}
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-6 right-6 flex flex-col gap-2">
                                <!-- Pending/Contingent Status Badge -->
                                @if($property->status === 'pending')
                                    <div class="px-4 py-1.5 bg-amber-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase animate-pulse">
                                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Contingent/Pending
                                    </div>
                                @endif
                                
                                <!-- Owner Financing Badge -->
                                @if($property->owner_financing_available)
                                    <div class="px-4 py-1.5 bg-green-500/90 backdrop-blur-sm text-white rounded-full text-xs font-medium tracking-wide uppercase">
                                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        Owner Financing
                                    </div>
                                @endif
                                
                                <!-- Featured Badge -->
                                @if($property->featured)
                                    <div class="px-4 py-1.5 bg-secondary/90 backdrop-blur-sm text-black rounded-full text-xs font-medium tracking-wide uppercase">
                                        <svg class="w-3 h-3 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Featured
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Property Title Overlay -->
                            <div class="absolute bottom-6 left-6 right-6">
                                <h3 class="text-xl font-serif font-medium text-white mb-2 line-clamp-2">
                                    {{ Str::limit($property->title, 50) }}
                                </h3>
                                <div class="flex items-center text-white/90 text-sm font-light">
                                    <svg class="w-4 h-4 mr-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ number_format($property->total_acres) }}± acres • {{ $property->county ?? 'Kentucky' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Price & Acreage -->
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-2xl font-serif text-secondary">{{ $property->formatted_price }}</span>
                                <div class="text-right">
                                    @if($property->price_per_acre)
                                        <div class="text-white/60 text-xs uppercase tracking-wide font-light">Per Acre</div>
                                        <div class="text-white/80 font-medium text-sm">{{ $property->formatted_price_per_acre }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Description -->
                            @if($property->description)
                                <p class="text-white/70 font-light leading-relaxed mb-6 text-sm line-clamp-2">
                                    {{ Str::limit($property->description, 120) }}
                                </p>
                            @endif
                            
                            <!-- Premium CTA Button -->
                            <a href="{{ route('properties.show', $property) }}" class="group/btn block w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-3 px-6 rounded-2xl font-medium tracking-wide transition-all duration-300 backdrop-blur-sm text-center">
                                <span class="flex items-center justify-center">
                                    <span>View Details</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- No Properties State -->
                <div class="col-span-full text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-serif text-white mb-4">No Properties Found</h3>
                        <p class="text-white/70 mb-8">We couldn't find any properties matching your criteria. Try adjusting your search or browse all available properties.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center btn-premium text-black px-8 py-3 rounded-2xl font-medium tracking-wide transition-all duration-300 transform hover:scale-105">
                            <span>View All Properties</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Premium Pagination -->
        @if($properties->hasPages())
            <div class="flex justify-center mt-16">
                <div class="bg-black/40 backdrop-blur-2xl border border-white/10 rounded-2xl p-2">
                    {{ $properties->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Premium Contact CTA Section -->
<section class="py-24 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.2) 1px, transparent 0); background-size: 60px 60px;"></div>
    </div>
    
    <div class="relative max-w-6xl mx-auto px-6 lg:px-8 text-center">
        <!-- Section Header -->
        <div class="mb-12">
            <!-- Premium Badge -->
            <div class="inline-flex items-center mb-8 opacity-0 fade-in-up">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Ready to Buy?</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            
            <!-- Mixed Typography Headline -->
            <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
                <h2 class="text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">
                    <span class="block text-white font-sans font-light tracking-wide">Find Your</span>
                    <span class="block text-secondary font-serif font-medium italic tracking-tight">
                        Perfect Property
                    </span>
                </h2>
            </div>
            
            <!-- Elegant Subtitle -->
            <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
                <p class="text-lg md:text-xl text-white/80 font-light leading-relaxed font-sans tracking-wide">
                    Ready to make your land ownership dreams a reality? Contact Jeremiah today to discuss your perfect property match.
                </p>
            </div>
        </div>
        
        <!-- Premium CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center opacity-0 fade-in-up fade-in-up-delay-3">
            <a href="tel:+18594732259" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                <div class="relative flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>Call 859.473.2259</span>
                </div>
            </a>
            
            <a href="mailto:jblandandhomerealty@gmail.com?subject=Property Inquiry" class="group inline-flex items-center bg-black/30 hover:bg-black/50 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm">
                <div class="relative flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Send Email Inquiry</span>
                </div>
            </a>
        </div>
    </div>
</section>

@endsection
