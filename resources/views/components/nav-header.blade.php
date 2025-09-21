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
                            src="{{ asset('images/logo.png') }}" 
                            alt="JB Land & Home Realty Logo" 
                            class="w-35 h-35 rounded-lg object-cover shadow-lg ring-2 ring-secondary/20 hover:ring-secondary/40 transition-all duration-300"
                        >
                    </div>
                    {{-- <div class="hidden sm:block">
                        <h1 class="text-xl font-medium text-white font-sans">JB Land & Home</h1>
                        <p class="text-sm text-secondary -mt-0.5 font-light tracking-widest uppercase">Looking for ground?</p>
                    </div> --}}
                </a>
            </div>

            <!-- Refined Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-12">
                <a href="{{ route('properties.index') }}" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Browse Properties</a>
                <a href="{{ route('owner-financing') }}" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Owner Financing</a>
                <a href="{{ route('agents') }}" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Agents</a>
                <a href="{{ route('about') }}" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">About</a>
                <a href="{{ route('contact') }}" class="nav-link gold-sweep text-white/90 hover:text-white px-4 py-3 text-sm font-light tracking-wide transition-all duration-300">Contact</a>
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
            <a href="{{ route('properties.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Browse Properties</a>
            <a href="{{ route('owner-financing') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Owner Financing</a>
            <a href="{{ route('agents') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Agents</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-secondary transition-colors duration-300">Contact</a>
            
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
