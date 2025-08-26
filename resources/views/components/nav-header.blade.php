<!-- Refined Premium Navigation Header -->
<header 
    class="fixed top-0 left-0 right-0 nav-transparent border-b border-secondary/10 relative z-50 transition-all duration-300"
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        init() {
            this.$nextTick(() => {
                window.addEventListener('scroll', () => {
                    this.scrolled = window.scrollY > 50;
                    this.$el.className = this.scrolled 
                        ? this.$el.className.replace('nav-transparent', 'nav-solid')
                        : this.$el.className.replace('nav-solid', 'nav-transparent');
                });
            });
        }
    }"
>
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <img 
                            src="{{ asset('images/logo.jpg') }}" 
                            alt="JB Land & Home Realty Logo" 
                            class="w-12 h-12 rounded-lg object-cover shadow-lg ring-2 ring-secondary/20 hover:ring-secondary/40 transition-all duration-300"
                        >
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-medium text-white font-sans">JB Land & Home</h1>
                        <p class="text-sm text-secondary -mt-0.5 font-light tracking-widest uppercase">Looking for ground?</p>
                    </div>
                </a>
            </div>

            <!-- Refined Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-12">
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button 
                        @click="dropdownOpen = !dropdownOpen"
                        @click.away="dropdownOpen = false"
                        class="nav-link gold-sweep flex items-center text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300"
                    >
                        Browse Properties
                        <svg class="ml-2 h-3 w-3 transform transition-transform duration-300" 
                             :class="{ 'rotate-180': dropdownOpen }"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Refined Dropdown Menu -->
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                         class="absolute top-full left-0 mt-3 w-72 bg-black/95 backdrop-blur-2xl border border-secondary/20 rounded-xl shadow-2xl overflow-hidden">
                        <div class="p-3">
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L3 9h4v9h6v-9h4l-7-7z"/></svg>
                                Hunting Land
                            </a>
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                                Farms & Ranches
                            </a>
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                Residential
                            </a>
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2v8h12V6H4z" clip-rule="evenodd"/></svg>
                                Commercial
                            </a>
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
                                Waterfront
                            </a>
                            <a href="#" class="group flex items-center px-4 py-3 text-sm text-gray-300 hover:text-secondary hover:bg-secondary/5 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-3 opacity-60 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Land Auctions
                            </a>
                        </div>
                    </div>
                </div>
                <a href="#" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Agents</a>
                <a href="#" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">About</a>
                <a href="#" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Contact</a>
            </nav>

            <!-- Refined CTA Buttons & Mobile Menu -->
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="hidden sm:inline-flex items-center px-5 py-2.5 border border-secondary/30 rounded-lg text-sm font-light text-secondary bg-black/20 hover:bg-secondary/10 backdrop-blur-sm transition-all duration-300">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm font-light text-white/90 hover:text-white transition-colors duration-300 tracking-wide">
                        Sign In
                    </a>
                    @if (Route::has('register'))
                        <a href="#" class="hidden sm:inline-flex items-center px-6 py-2.5 btn-premium text-black rounded-xl text-sm font-medium tracking-wide">
                            Sell With Us
                        </a>
                    @endif
                @endauth

                <!-- Mobile Menu Button -->
                <button 
                    type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-secondary hover:bg-secondary/10 transition-all duration-300"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-black/95 backdrop-blur-xl border-t border-secondary/20">
            <div class="px-3 py-2" x-data="{ propertiesOpen: false }">
                <button @click="propertiesOpen = !propertiesOpen" class="flex items-center justify-between w-full text-sm font-medium text-white mb-2">
                    Browse Properties
                    <svg class="ml-1 h-4 w-4 transform transition-transform" 
                         :class="{ 'rotate-180': propertiesOpen }"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="propertiesOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="pl-3 space-y-1">
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Hunting Land</a>
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Farms & Ranches</a>
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Residential</a>
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Commercial</a>
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Waterfront</a>
                    <a href="#" class="block py-1 text-sm text-gray-400 hover:text-secondary transition-colors duration-300">Land Auctions</a>
                </div>
            </div>
            <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Agents</a>
            <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">About</a>
            <a href="#" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Contact</a>
            
            <div class="pt-4 border-t border-secondary/20">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-sm font-medium text-secondary hover:text-white transition-colors duration-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Sign In</a>
                    @if (Route::has('register'))
                        <a href="#" class="block mx-3 mt-2 px-4 py-2 bg-gradient-to-r from-secondary to-yellow-400 text-black rounded-md text-sm font-bold text-center hover:from-yellow-400 hover:to-secondary transition-all duration-300">
                            Sell With Us
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</header>
