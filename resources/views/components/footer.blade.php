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
                                    Your trusted partner in Eastern Kentucky real estate. Specializing in hunting land, farms, waterfront properties, and residential homes with deep regional expertise.
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
                                            <div>Carlisle, KY 40311</div>
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
                                "Finding your perfect place in Eastern Kentucky"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
