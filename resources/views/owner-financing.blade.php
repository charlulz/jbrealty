@extends('components.layouts.guest')

@section('content')
<!-- Owner Financing Hero Section -->
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
                <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Owner Financing Available</span>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
        </div>

        <!-- Mixed Typography Headline -->
        <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
            <h1 class="text-5xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                <span class="block text-white font-sans font-light tracking-wide">Make Your</span>
                <span class="block text-secondary font-serif font-medium italic tracking-tight">
                    Dream Possible
                </span>
            </h1>
        </div>

        <!-- Elegant Subtitle -->
        <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
            <p class="text-xl md:text-2xl text-white/80 font-light leading-relaxed font-sans tracking-wide mb-12">
                Owner financing options available on select properties - making land ownership accessible with flexible terms
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="#application-form" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                    <div class="relative flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Apply for Pre-Approval
                    </div>
                </a>
                <a href="#how-it-works" class="group inline-flex items-center bg-black/30 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm hover:bg-black/50">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learn How It Works
                </a>
            </div>
        </div>
    </div>
</section>

<!-- How Owner Financing Works -->
<section id="how-it-works" class="py-32 bg-black relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 80px 80px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center mb-8">
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">How It Works</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-light text-white mb-6 font-sans tracking-wide">Owner Financing Explained</h2>
            <p class="text-xl text-white/70 font-light leading-relaxed max-w-3xl mx-auto">
                Owner financing allows you to purchase property directly from the seller with flexible terms, often with lower down payments and easier qualification requirements.
            </p>
        </div>

        <!-- Benefits Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <!-- Benefit 1 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Lower Down Payment</h3>
                    <p class="text-white/70 font-light leading-relaxed">
                        Often requires significantly less money down compared to traditional bank financing, making land ownership more accessible.
                    </p>
                </div>
            </div>

            <!-- Benefit 2 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Easier Qualification</h3>
                    <p class="text-white/70 font-light leading-relaxed">
                        Flexible credit requirements and streamlined approval process without the strict bank lending criteria.
                    </p>
                </div>
            </div>

            <!-- Benefit 3 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Faster Closing</h3>
                    <p class="text-white/70 font-light leading-relaxed">
                        Skip the lengthy bank approval process and close on your property in weeks instead of months.
                    </p>
                </div>
            </div>
        </div>

        <!-- Process Steps -->
        <div class="text-center mb-16">
            <h3 class="text-3xl font-light text-white mb-12">Simple 4-Step Process</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 text-black font-bold text-xl">1</div>
                    <h4 class="text-white font-medium mb-2">Apply</h4>
                    <p class="text-white/60 text-sm">Submit your pre-approval application</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 text-black font-bold text-xl">2</div>
                    <h4 class="text-white font-medium mb-2">Review</h4>
                    <p class="text-white/60 text-sm">We review your application and discuss terms</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 text-black font-bold text-xl">3</div>
                    <h4 class="text-white font-medium mb-2">Select</h4>
                    <p class="text-white/60 text-sm">Choose from available owner-financed properties</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 text-black font-bold text-xl">4</div>
                    <h4 class="text-white font-medium mb-2">Close</h4>
                    <p class="text-white/60 text-sm">Complete the purchase with flexible terms</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Form Section -->
<section id="application-form" class="py-32 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 80px 80px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <x-owner-financing-form />
    </div>
</section>

<!-- Available Properties CTA -->
<section class="py-32 bg-black relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <div class="inline-flex items-center mb-8">
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Browse Properties</span>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
        </div>
        
        <h2 class="text-4xl md:text-5xl font-light text-white mb-6 font-sans tracking-wide">Ready to Explore?</h2>
        <p class="text-xl text-white/70 font-light leading-relaxed max-w-3xl mx-auto mb-12">
            Browse our available properties and filter by owner financing to see what's available with flexible terms.
        </p>
        
        <a href="{{ route('properties.index') }}" class="group inline-flex items-center btn-premium text-black px-12 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
            <div class="relative flex items-center">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Browse Properties
            </div>
        </a>
    </div>
</section>

@endsection
