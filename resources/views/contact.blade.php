@extends('components.layouts.guest')

@section('content')
<!-- Contact Hero Section -->
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
            <source src="{{ asset('videos/hero_alt.mp4') }}" type="video/mp4">
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
                <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Get In Touch</span>
            </div>
            <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
        </div>

        <!-- Mixed Typography Headline -->
        <div class="mb-8 opacity-0 fade-in-up fade-in-up-delay-1">
            <h1 class="text-5xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                <span class="block text-white font-sans font-light tracking-wide">Let's Start Your</span>
                <span class="block text-secondary font-serif font-medium italic tracking-tight">
                    Property Journey
                </span>
            </h1>
        </div>

        <!-- Elegant Subtitle -->
        <div class="max-w-4xl mx-auto opacity-0 fade-in-up fade-in-up-delay-2">
            <p class="text-xl md:text-2xl text-white/80 font-light leading-relaxed font-sans tracking-wide mb-12">
                Ready to find your perfect property? We're here to help you every step of the way
            </p>
            
            <!-- Quick Contact Options -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="tel:(859) 473-2259" class="group inline-flex items-center btn-premium text-black px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                    <div class="relative flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Call Now
                    </div>
                </a>
                <a href="#contact-form" class="group inline-flex items-center bg-black/30 border border-white/20 hover:border-secondary/50 text-white px-10 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 backdrop-blur-sm hover:bg-black/50">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Send Message
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-32 bg-black relative overflow-hidden">
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
                    <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Contact Information</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-light text-white mb-6 font-sans tracking-wide">Get in Touch</h2>
            <p class="text-xl text-white/70 font-light leading-relaxed max-w-3xl mx-auto">
                We're here to help you find your perfect property. Contact us today to get started.
            </p>
        </div>

        <!-- Contact Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <!-- Phone Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Call Us</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-4">
                        Speak directly with Jeremiah about your property needs.
                    </p>
                    <a href="tel:(859) 473-2259" class="text-secondary hover:text-secondary/80 font-medium text-lg transition-colors duration-300">
                        (859) 473-2259
                    </a>
                </div>
            </div>

            <!-- Email Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Email Us</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-4">
                        Send us a detailed message about your property interests.
                    </p>
                    <a href="mailto:jblandandhomerealty@gmail.com" class="text-secondary hover:text-secondary/80 font-medium transition-colors duration-300 break-all">
                        jblandandhomerealty@gmail.com
                    </a>
                </div>
            </div>

            <!-- Office Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/5 to-secondary/10 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8 hover:border-secondary/30 transition-all duration-300">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-4">Visit Our Office</h3>
                    <p class="text-white/70 font-light leading-relaxed mb-4">
                        Stop by our office for a face-to-face consultation.
                    </p>
                    <div class="text-secondary">
                        <div class="font-medium">4629 Maysville Road</div>
                        <div>Carlisle, KY 40311</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section id="contact-form" class="py-32 bg-gradient-to-br from-primary via-black to-primary relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(252,206,0,0.15) 1px, transparent 0); background-size: 80px 80px;"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 lg:px-8">
        <!-- Contact Form -->
        <div class="relative group">
            <!-- Gold Glow -->
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/10 via-secondary/20 to-secondary/10 rounded-3xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
            
            <!-- Main Form Card -->
            <div class="relative bg-black/40 backdrop-blur-2xl border border-secondary/15 rounded-3xl p-10 md:p-14 shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center mb-6">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Send Message</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-light text-white mb-4 font-sans tracking-wide">Contact Us</h2>
                    <p class="text-white/70 font-light leading-relaxed max-w-2xl mx-auto">
                        Ready to find your perfect property? Fill out the form below and we'll get back to you within 24 hours.
                    </p>
                </div>

                <form id="contact-form" class="space-y-8">
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">First Name *</label>
                            <input 
                                type="text" 
                                name="firstName"
                                required
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Last Name *</label>
                            <input 
                                type="text" 
                                name="lastName"
                                required
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Email Address *</label>
                            <input 
                                type="email" 
                                name="email"
                                required
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Phone Number</label>
                            <input 
                                type="tel" 
                                name="phone"
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                    </div>

                    <!-- Interest Type -->
                    <div class="space-y-3">
                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">I'm Interested In</label>
                        <div class="relative">
                            <select name="interestType" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                                <option value="">Select Your Interest</option>
                                <option value="buying">Buying Property</option>
                                <option value="selling">Selling Property</option>
                                <option value="hunting-land">Hunting Land</option>
                                <option value="farms">Farms & Agricultural Land</option>
                                <option value="residential">Residential Homes</option>
                                <option value="waterfront">Waterfront Properties</option>
                                <option value="owner-financing">Owner Financing</option>
                                <option value="general-inquiry">General Inquiry</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-3">
                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Message *</label>
                        <textarea 
                            name="message"
                            rows="6"
                            required
                            placeholder="Tell us about your property needs, budget, timeline, or any questions you have..."
                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light resize-none"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center pt-8">
                        <button type="submit" class="group relative inline-flex items-center btn-premium text-black px-12 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                            <div class="relative flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span>Send Message</span>
                            </div>
                        </button>
                        
                        <p class="text-white/60 text-sm mt-4 max-w-2xl mx-auto">
                            By submitting this form, you agree to be contacted by JB Land & Home Realty regarding your inquiry. We respect your privacy and will never share your information.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map & Office Hours Section -->
<section class="py-32 bg-black relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Office Hours & Additional Info -->
            <div class="space-y-8">
                <div>
                    <div class="inline-flex items-center mb-8">
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                        <div class="mx-6 px-5 py-1.5 border border-secondary/30 rounded-full backdrop-blur-sm bg-secondary/10">
                            <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Office Information</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-light text-white mb-6 font-sans tracking-wide">Visit Our Office</h2>
                </div>

                <!-- Office Hours -->
                <div class="bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8">
                    <h3 class="text-xl font-medium text-secondary mb-6">Office Hours</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Available:</span>
                            <span class="text-white font-medium">24/7</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Phone & Email:</span>
                            <span class="text-white font-medium">Always Open</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/10">
                            <span class="text-white/70">Response Time:</span>
                            <span class="text-white font-medium">Within 24 Hours</span>
                        </div>
                    </div>
                    <p class="text-white/60 text-sm mt-4">
                        *After Hours by Appointment
                    </p>
                </div>

                <!-- Services -->
                <div class="bg-black/40 backdrop-blur-xl border border-secondary/15 rounded-2xl p-8">
                    <h3 class="text-xl font-medium text-secondary mb-6">Our Services</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Hunting Land Sales
                        </div>
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Farm & Ranch Sales
                        </div>
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Residential Homes
                        </div>
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Waterfront Properties
                        </div>
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Owner Financing
                        </div>
                        <div class="flex items-center text-white/80">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Property Consulting
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Create email subject and body
    const subject = encodeURIComponent('Contact Form Submission - ' + data.firstName + ' ' + data.lastName);
    const body = encodeURIComponent(`
New Contact Form Submission

CONTACT INFORMATION:
Name: ${data.firstName} ${data.lastName}
Email: ${data.email}
Phone: ${data.phone || 'Not provided'}

INQUIRY DETAILS:
Interest: ${data.interestType || 'Not specified'}

MESSAGE:
${data.message}

Please respond to this inquiry as soon as possible.

Best regards,
JB Land & Home Realty Website
    `);
    
    // Open email client
    window.location.href = `mailto:jblandandhomerealty@gmail.com?subject=${subject}&body=${body}`;
    
    // Show success message
    alert('Thank you for your message! Your email client should now open with your inquiry. If it doesn\'t open automatically, please contact us directly at jblandandhomerealty@gmail.com or (859) 473-2259.');
});
</script>

@endsection
