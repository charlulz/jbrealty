@extends('components.layouts.guest')

@push('head')
<script type="application/ld+json">
@php
  $images = $property->images ? $property->images->take(5)->pluck('url')->values()->all() : [];

  $schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'RealEstateListing',
    'name'        => $property->title,
    'description' => strip_tags($property->description),
    'url'         => request()->url(),
    'identifier'  => $property->mls_number ?? (string) $property->id,
    'datePosted'  => optional($property->created_at)->toIso8601String(),
    // Only add image if we have any
    'image'       => !empty($images) ? $images : null,
    'offers' => [
      '@type'         => 'Offer',
      'price'         => is_numeric($property->price) ? (float) $property->price : (float) preg_replace('/[^\d.]/', '', (string) $property->price),
      'priceCurrency' => 'USD',
      'availability'  => 'https://schema.org/' . (
        $property->status === 'active'  ? 'InStock' :
        ($property->status === 'pending' ? 'PreOrder' : 'OutOfStock')
      ),
      'validFrom'     => optional($property->created_at)->toIso8601String(),
    ],
    'address' => [
      '@type'           => 'PostalAddress',
      'streetAddress'   => $property->address,
      'addressLocality' => $property->city,
      'addressRegion'   => $property->state,
      'postalCode'      => $property->zip,
      'addressCountry'  => 'US',
    ],
    'geo' => [
      '@type'    => 'GeoCoordinates',
      'latitude'  => ($property->latitude  !== null && $property->latitude  !== '') ? (float) $property->latitude  : null,
      'longitude' => ($property->longitude !== null && $property->longitude !== '') ? (float) $property->longitude : null,
    ],
    'additionalProperty' => array_values(array_filter([
      [
        '@type' => 'PropertyValue',
        'name'  => 'Total Acres',
        'value' => $property->total_acres,
      ],
      [
        '@type' => 'PropertyValue',
        'name'  => 'Property Type',
        'value' => ucfirst((string) $property->property_type),
      ],
      [
        '@type' => 'PropertyValue',
        'name'  => 'County',
        'value' => $property->county,
      ],
      $property->mls_number ? [
        '@type' => 'PropertyValue',
        'name'  => 'MLS Number',
        'value' => $property->mls_number,
      ] : null,
    ])),
    'agent' => [
      '@type' => 'RealEstateAgent',
      'name'  => 'Jeremiah Brown',
      'telephone' => '859-473-2259',
      'email'     => 'jblandandhomerealty@gmail.com',
      'image'     => asset('images/Jeremiah_Headshot.JPEG'),
      'worksFor'  => [
        '@type' => 'Organization',
        'name'  => 'JB Land & Home Realty',
        'url'   => 'https://jblandandhome.com',
        'logo'  => asset('images/logo.png'),
      ],
    ],
  ];

  // Remove nulls so you don’t emit invalid JSON-LD fields
  $schema = array_filter($schema, fn($v) => $v !== null);
@endphp
{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<!-- Premium Property Hero Section -->
<section class="py-24 bg-black relative overflow-hidden">
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Navigation -->
        <div class="mb-8">
            <a href="{{ route('properties.index') }}" class="group inline-flex items-center text-white/80 hover:text-secondary transition-colors duration-300">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to All Properties
            </a>
        </div>

        <!-- Property Header -->
        <div class="text-center mb-12">
            <div class="mb-6">
                <div class="flex items-center justify-center gap-4 mb-4">
                    @if($property->featured)
                        <div class="px-4 py-1.5 bg-secondary/90 backdrop-blur-sm text-black rounded-full text-sm font-medium tracking-wide uppercase">
                            <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Featured Property
                        </div>
                    @endif
                    
                    @if($property->status === 'pending')
                        <div class="px-4 py-1.5 bg-amber-500/90 backdrop-blur-sm text-white rounded-full text-sm font-medium tracking-wide uppercase animate-pulse">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Contingent/Pending
                        </div>
                    @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl font-serif font-medium text-white mb-4">{{ $property->title }}</h1>
                
                <div class="flex flex-wrap items-center justify-center gap-6 text-white/80">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $property->county }}, Kentucky
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        {{ number_format($property->total_acres) }}± Acres
                    </div>
                    @if($property->property_type)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ ucwords(str_replace('_', ' ', $property->property_type)) }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="text-center">
                <div class="text-5xl md:text-6xl font-serif text-secondary mb-2">{{ $property->formatted_price }}</div>
                @if($property->price_per_acre)
                    <div class="text-white/60">{{ $property->formatted_price_per_acre }}</div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="py-16 bg-gradient-to-b from-black to-primary/20 relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="w-full max-w-full lg:col-span-2 space-y-8 min-w-0">
                <!-- Property Gallery -->
                <div class="relative w-full max-w-full bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl overflow-hidden">
                    <x-property-gallery :property="$property" />
                </div>

                <!-- Description -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                    <h2 class="text-2xl font-serif font-medium text-white mb-6 border-b border-secondary/20 pb-4">Property Overview</h2>
                    @if($property->description)
                        <div class="text-white/80 leading-relaxed whitespace-pre-wrap font-light text-lg">{{ $property->description }}</div>
                    @else
                        <p class="text-white/60 italic">Property description coming soon...</p>
                    @endif
                </div>

                <!-- Property Details -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                    <h2 class="text-2xl font-serif font-medium text-white mb-6 border-b border-secondary/20 pb-4">Property Details</h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-medium text-secondary mb-4 text-lg">Acreage Breakdown</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-white/10">
                                    <span class="text-white/70">Total Acres:</span>
                                    <span class="text-white font-medium">{{ number_format($property->total_acres) }}±</span>
                                </div>
                                @if($property->tillable_acres)
                                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                                        <span class="text-white/70">Tillable:</span>
                                        <span class="text-white">{{ number_format($property->tillable_acres) }} acres</span>
                                    </div>
                                @endif
                                @if($property->wooded_acres)
                                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                                        <span class="text-white/70">Wooded:</span>
                                        <span class="text-white">{{ number_format($property->wooded_acres) }} acres</span>
                                    </div>
                                @endif
                                @if($property->pasture_acres)
                                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                                        <span class="text-white/70">Pasture:</span>
                                        <span class="text-white">{{ number_format($property->pasture_acres) }} acres</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="font-medium text-secondary mb-4 text-lg">Property Rights</h3>
                            <div class="space-y-3">
                                @if($property->water_access)
                                    <div class="flex items-center text-secondary">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Water Access
                                    </div>
                                @endif
                                @if($property->mineral_rights)
                                    <div class="flex items-center text-secondary">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Mineral Rights Included
                                    </div>
                                @endif
                                @if($property->timber_rights)
                                    <div class="flex items-center text-secondary">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Timber Rights Included
                                    </div>
                                @endif
                                @if($property->hunting_rights)
                                    <div class="flex items-center text-secondary">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Hunting Rights
                                    </div>
                                @endif
                                @if($property->fishing_rights)
                                    <div class="flex items-center text-secondary">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Fishing Rights
                                    </div>
                                @endif
                                @if(!$property->water_access && !$property->mineral_rights && !$property->timber_rights && !$property->hunting_rights && !$property->fishing_rights)
                                    <p class="text-white/60 italic">Rights information not specified</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Financing Information -->
                @if($property->owner_financing_available)
                    <div class="relative bg-gradient-to-r from-green-500/10 via-green-600/10 to-green-500/10 backdrop-blur-2xl border border-green-500/30 rounded-3xl p-8">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-serif font-medium text-white">Owner Financing Available</h2>
                            </div>
                            <p class="text-white/80 leading-relaxed max-w-2xl mx-auto">
                                This property offers owner financing options with flexible terms and competitive rates. Skip the traditional bank approval process and work directly with the seller.
                            </p>
                        </div>

                        @if($property->owner_financing_terms)
                            <div class="bg-black/20 rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-medium text-green-400 mb-4">Financing Terms</h3>
                                <div class="text-white/90 leading-relaxed whitespace-pre-wrap">{{ $property->owner_financing_terms }}</div>
                            </div>
                        @endif

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-black/20 rounded-2xl p-6">
                                <h3 class="text-lg font-medium text-green-400 mb-4">Benefits</h3>
                                <ul class="space-y-3 text-white/80">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Lower down payment options
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Flexible credit requirements
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Faster closing process
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Direct negotiation with seller
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-black/20 rounded-2xl p-6">
                                <h3 class="text-lg font-medium text-green-400 mb-4">Next Steps</h3>
                                <p class="text-white/80 mb-4">Interested in owner financing for this property?</p>
                                <div class="space-y-3">
                                    <a href="{{ route('owner-financing') }}" class="block w-full bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 hover:border-green-500/50 text-green-400 hover:text-green-300 py-3 px-4 rounded-xl font-medium text-center transition-all duration-300">
                                        Apply for Pre-Approval
                                    </a>
                                    <a href="mailto:jblandandhomerealty@gmail.com?subject=Owner Financing Inquiry: {{ $property->title }}&body=Hi Jeremiah,%0D%0A%0D%0AI'm interested in the owner financing options for the property: {{ $property->title }}%0D%0A%0D%0ACan you provide more details about the terms and requirements?%0D%0A%0D%0AThank you!" class="block w-full bg-black/30 hover:bg-black/50 border border-white/20 hover:border-green-500/30 text-white hover:text-green-400 py-3 px-4 rounded-xl font-medium text-center transition-all duration-300">
                                        Contact About Terms
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Property Location Map -->
                @if($property->latitude && $property->longitude)
                    <div class="relative w-full max-w-full bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-4 sm:p-6 lg:p-8">
                        <h2 class="text-xl sm:text-2xl font-serif font-medium text-white mb-4 sm:mb-6 border-b border-secondary/20 pb-4">Property Location</h2>
                        <div class="w-full max-w-full rounded-2xl overflow-hidden border border-white/20">
                            <x-property-map 
                                :latitude="$property->latitude" 
                                :longitude="$property->longitude" 
                                :editable="false"
                                :title="$property->title"
                            />
                        </div>
                        <div class="mt-4 sm:mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
                            <div class="flex justify-between items-center py-2 border-b border-white/10">
                                <span class="text-white/70 text-sm sm:text-base">Latitude:</span>
                                <span class="text-white font-medium text-sm sm:text-base font-mono">{{ number_format($property->latitude, 6) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-white/10">
                                <span class="text-white/70 text-sm sm:text-base">Longitude:</span>
                                <span class="text-white font-medium text-sm sm:text-base font-mono">{{ number_format($property->longitude, 6) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Features -->
                @if($property->features->count() > 0)
                    <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                        <h2 class="text-2xl font-serif font-medium text-white mb-6 border-b border-secondary/20 pb-4">Additional Features</h2>
                        <div class="grid md:grid-cols-2 gap-8">
                            @foreach($property->features->groupBy('category') as $category => $features)
                                <div>
                                    <h3 class="font-medium text-secondary mb-4 text-lg capitalize">{{ str_replace('_', ' ', $category) }}</h3>
                                    <div class="space-y-3">
                                        @foreach($features as $feature)
                                            <div class="flex items-center text-white/90">
                                                <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $feature->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Mortgage Calculator -->
                <x-mortgage-calculator :property="$property" />

                <!-- Property Packet Request Form -->
                <x-property-packet-form :property="$property" />

                <!-- Appointment Booking -->
                <x-property-booking :property="$property" id="schedule-tour" />
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Price & Contact -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-serif text-secondary mb-2">{{ $property->formatted_price }}</div>
                        @if($property->price_per_acre)
                            <div class="text-white/60 mb-6">{{ $property->formatted_price_per_acre }}</div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <button onclick="document.getElementById('schedule-tour').scrollIntoView({behavior: 'smooth', block: 'center'})" class="group w-full bg-secondary hover:bg-secondary/90 text-black py-4 px-6 rounded-2xl font-medium text-center transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-secondary/30 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
                            </svg>
                            Schedule Property Tour
                        </button>
                        <a href="tel:+18594732259" class="group w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium text-center transition-all duration-300 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call Jeremiah: 859.473.2259
                        </a>
                        <a href="mailto:jblandandhomerealty@gmail.com?subject=Property Inquiry: {{ $property->title }}" class="group w-full bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black py-4 px-6 rounded-2xl font-medium text-center transition-all duration-300 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send Email Inquiry
                        </a>
                    </div>
                </div>

                <!-- Property Packet Request -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-6">
                    <div class="text-center mb-4">
                        <div class="w-12 h-12 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-serif font-medium text-secondary text-lg mb-2">Get Property Packet</h3>
                        <p class="text-white/70 text-sm">Instant delivery with photos, pricing, GPS & agent info</p>
                    </div>
                    
                    <div x-data="{ showPacketForm: false }">
                        <button @click="showPacketForm = !showPacketForm" class="w-full bg-secondary hover:bg-secondary/90 text-black py-3 px-6 rounded-2xl font-medium text-center transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-secondary/30 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span x-text="showPacketForm ? 'Hide Form' : 'Get Free Packet'"></span>
                        </button>
                        
                        <!-- Compact Packet Form -->
                        <div x-show="showPacketForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0" class="mt-4 overflow-hidden">
                            <div class="bg-black/60 rounded-2xl p-4 border border-white/20">
                                <x-property-packet-form :property="$property" compact="true" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agent Info -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                    <h3 class="font-serif font-medium text-secondary mb-6 text-xl border-b border-secondary/20 pb-4">Your Agent</h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-secondary/20 rounded-full flex items-center justify-center border border-secondary/30">
                            <span class="text-xl font-medium text-secondary">JB</span>
                        </div>
                        <div>
                            <p class="font-medium text-white text-lg">Jeremiah Brown</p>
                            <p class="text-white/70 mb-2">Principal Broker</p>
                            <p class="text-white/70 text-sm">jblandandhomerealty@gmail.com</p>
                            <p class="text-white/70 text-sm">859.473.2259</p>
                        </div>
                    </div>
                </div>

                <!-- Property Stats -->
                <div class="relative bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">
                    <h3 class="font-serif font-medium text-secondary mb-6 text-xl border-b border-secondary/20 pb-4">Property Stats</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Listed:</span>
                            <span class="text-white font-medium">{{ $property->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Days on Market:</span>
                            <span class="text-white font-medium">{{ (int) $property->created_at->diffInDays() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Views:</span>
                            <span class="text-white font-medium">{{ $property->views_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Property ID:</span>
                            <span class="text-white/80 text-sm font-mono">{{ $property->mls_number ?? 'JB-' . str_pad($property->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Legal Disclaimers & Compliance -->
<div class="max-w-7xl mx-auto px-6 lg:px-8">
    <x-property-disclaimers :property="$property" />
</div>

@endsection
