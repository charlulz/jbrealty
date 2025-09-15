<div class="max-w-6xl mx-auto opacity-0 fade-in-up">
    <!-- Refined Property Search Card -->
    <div class="relative group">
        <!-- Subtle Gold Glow -->
        <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
        
        <!-- Main Search Card -->
        <div class="relative bg-black/40 backdrop-blur-2xl border border-secondary/15 rounded-3xl p-10 md:p-14 shadow-2xl">
            <!-- Refined Header -->
            <div class="text-center mb-12">
                <h2 class="text-xl md:text-2xl font-light text-white/90 mb-4 font-sans tracking-wide">Find Your Perfect Property</h2>
                <div class="flex items-center justify-center">
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent w-20"></div>
                    <div class="w-1.5 h-1.5 bg-secondary rounded-full mx-4"></div>
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent w-20"></div>
                </div>
            </div>
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-secondary/10 border border-secondary/30 text-secondary rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('message') }}
                    </div>
                </div>
            @endif
        
        <form wire:submit.prevent="searchProperties">
            <!-- Refined Search Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                <!-- Property Type -->
                <div class="space-y-3">
                    <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Property Type</label>
                    <div class="relative">
                        <select wire:model="propertyType" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                            <option value="">All Properties</option>
                            <option value="hunting">Hunting Land</option>
                            <option value="farms">Farms</option>
                            <option value="ranches">Ranches</option>
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                            <option value="waterfront">Waterfront</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="space-y-3">
                    <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Location</label>
                    <input 
                        type="text" 
                        wire:model="location"
                        placeholder="City, County, or State"
                        class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                    />
                </div>

                <!-- Price Range -->
                <div class="space-y-3">
                    <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Price Range</label>
                    <div class="relative">
                        <select wire:model="priceRange" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                            <option value="">Any Price</option>
                            <option value="under-100k">Under $100K</option>
                            <option value="100k-250k">$100K - $250K</option>
                            <option value="250k-500k">$250K - $500K</option>
                            <option value="500k-1m">$500K - $1M</option>
                            <option value="over-1m">Over $1M</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Acres -->
                <div class="space-y-3">
                    <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Acreage</label>
                    <div class="relative">
                        <select wire:model="acreage" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                            <option value="">Any Size</option>
                            <option value="1-10">1-10 acres</option>
                            <option value="10-50">10-50 acres</option>
                            <option value="50-100">50-100 acres</option>
                            <option value="100-300">100-300 acres</option>
                            <option value="300+">300+ acres</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Elegant Advanced Search Toggle -->
            <div class="flex items-center justify-center mb-10">
                <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent flex-1 max-w-32"></div>
                <button 
                    type="button"
                    wire:click="toggleAdvancedSearch"
                    class="mx-8 group px-5 py-2.5 bg-black/20 border border-white/10 rounded-full text-white/80 hover:text-white hover:border-secondary/30 text-xs font-light tracking-widest uppercase transition-all duration-500 flex items-center backdrop-blur-sm hover:bg-black/30"
                >
                    <span>Advanced Filters</span>
                    <svg class="ml-3 h-3 w-3 transform transition-transform duration-500 {{ $advancedSearchOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent flex-1 max-w-32"></div>
            </div>

            <!-- Refined Advanced Search Fields -->
            @if ($advancedSearchOpen)
                <div class="mb-12 overflow-hidden"
                     x-show="$wire.advancedSearchOpen"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 max-h-0 transform scale-95"
                     x-transition:enter-end="opacity-100 max-h-[500px] transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 max-h-[500px] transform scale-100"
                     x-transition:leave-end="opacity-0 max-h-0 transform scale-95">
                    
                    <!-- Separator -->
                    <div class="flex items-center justify-center mb-10">
                        <div class="h-px bg-gradient-to-r from-transparent via-white/20 to-transparent w-full max-w-md"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <!-- Features -->
                        <div class="space-y-5">
                            <div class="text-center">
                                <h4 class="text-sm font-light text-secondary/80 tracking-[0.2em] uppercase mb-1">Features</h4>
                                <div class="w-8 h-px bg-secondary/30 mx-auto"></div>
                            </div>
                            <div class="space-y-4">
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="features.waterAccess" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Water Access</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="features.mineralRights" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Mineral Rights</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="features.timber" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Timber</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Improvements -->
                        <div class="space-y-5">
                            <div class="text-center">
                                <h4 class="text-sm font-light text-secondary/80 tracking-[0.2em] uppercase mb-1">Improvements</h4>
                                <div class="w-8 h-px bg-secondary/30 mx-auto"></div>
                            </div>
                            <div class="space-y-4">
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="improvements.home" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Home/Cabin</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="improvements.barn" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Barn/Outbuildings</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="improvements.utilities" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Utilities</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Recreation -->
                        <div class="space-y-5">
                            <div class="text-center">
                                <h4 class="text-sm font-light text-secondary/80 tracking-[0.2em] uppercase mb-1">Recreation</h4>
                                <div class="w-8 h-px bg-secondary/30 mx-auto"></div>
                            </div>
                            <div class="space-y-4">
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="recreation.hunting" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Hunting</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="recreation.fishing" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">Fishing</span>
                                </label>
                                <label class="group flex items-center p-3 rounded-xl hover:bg-black/20 cursor-pointer transition-colors duration-300">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="recreation.atv" class="sr-only peer">
                                        <div class="w-4 h-4 border border-white/20 rounded peer-checked:border-secondary peer-checked:bg-secondary transition-all duration-200"></div>
                                        <svg class="absolute inset-0 w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="ml-4 text-sm font-light text-white/80 group-hover:text-white transition-colors">ATV/Trails</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Refined Search Button -->
            <div class="text-center">
                <button type="submit" class="group relative inline-flex items-center btn-premium text-black px-12 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                    <div class="relative flex items-center">
                        <div wire:loading wire:target="searchProperties" class="mr-3">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <svg wire:loading.remove wire:target="searchProperties" class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span wire:loading.remove wire:target="searchProperties">Search Properties</span>
                        <span wire:loading wire:target="searchProperties">Searching...</span>
                    </div>
                </button>
                
                @if ($hasSearched)
                    <button type="button" wire:click="resetSearch" class="ml-4 bg-black/30 border border-white/20 hover:border-secondary/30 text-white/80 hover:text-secondary px-8 py-4 rounded-2xl font-light tracking-wide transition-all duration-300 backdrop-blur-sm hover:bg-black/40">
                        Reset
                    </button>
                @endif
            </div>
        </form>
        </div>
    </div>

    <!-- Premium Search Results -->
    @if ($hasSearched && count($searchResults) > 0)
        <div class="mt-16" id="search-results">
            <div class="text-center mb-12">
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">Search Results</h3>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary to-transparent w-32 mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($searchResults as $property)
                    <div class="group relative">
                        <!-- Gold accent border -->
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary/20 via-secondary/10 to-secondary/20 rounded-2xl blur-sm group-hover:blur-none transition-all duration-300"></div>
                        
                        <!-- Property Card -->
                        <div class="relative bg-black/60 backdrop-blur-xl border border-secondary/20 rounded-2xl overflow-hidden hover:border-secondary/40 transition-all duration-300">
                            <div class="h-64 relative overflow-hidden">
                                @if($property['image'])
                                    <img 
                                        src="{{ $property['image'] }}" 
                                        alt="{{ $property['title'] }} - Property Image" 
                                        class="w-full h-full object-cover"
                                        onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    >
                                    <div class="w-full h-full bg-gradient-to-br from-green-500 to-green-700 hidden"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-500 to-green-700"></div>
                                @endif
                                <div class="absolute inset-0 bg-black/20"></div>
                                <div class="absolute bottom-6 left-6 text-white">
                                    <h4 class="text-2xl font-bold mb-2">{{ $property['title'] }}</h4>
                                    <p class="text-sm opacity-90 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $property['acres'] }} • {{ $property['location'] }}
                                    </p>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-secondary text-black text-xs font-bold rounded-full uppercase tracking-wide">{{ $property['type'] }}</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-3xl font-black text-secondary">{{ $property['price'] }}</span>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-400 uppercase tracking-wide">Starting at</div>
                                    </div>
                                </div>
                                <a href="{{ route('properties.show', $property['id']) }}" class="block w-full bg-gradient-to-r from-secondary to-yellow-400 hover:from-yellow-400 hover:to-secondary text-black py-3 px-6 rounded-xl font-bold tracking-wide uppercase transition-all duration-300 transform hover:scale-105 text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($hasSearched && count($searchResults) === 0)
        <div class="mt-16 text-center">
            <div class="bg-black/60 backdrop-blur-xl border border-secondary/20 rounded-2xl p-12">
                <svg class="w-16 h-16 text-secondary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0120 12c0-4.411-3.589-8-8-8s-8 3.589-8 8c0 1.76.57 3.386 1.532 4.708z" />
                </svg>
                <p class="text-white text-xl font-semibold mb-2">No properties found matching your search criteria.</p>
                <p class="text-gray-400">Try adjusting your filters or contact our team for personalized assistance.</p>
            </div>
        </div>
    @endif
</div>

<script>
// Listen for scroll-to-results event
document.addEventListener('livewire:initialized', () => {
    Livewire.on('scroll-to-results', () => {
        setTimeout(() => {
            const resultsEl = document.getElementById('search-results');
            if (resultsEl) {
                resultsEl.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    });
});
</script>