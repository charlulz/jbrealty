<!-- Owner Financing Pre-Approval Application -->
<div class="max-w-4xl mx-auto">
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
                        <span class="text-secondary font-light text-xs tracking-[0.2em] uppercase">Owner Financing</span>
                    </div>
                    <div class="h-px bg-gradient-to-r from-transparent via-secondary/40 to-transparent flex-1 max-w-20"></div>
                </div>
                <h2 class="text-3xl md:text-4xl font-light text-white mb-4 font-sans tracking-wide">Pre-Approval Application</h2>
                <p class="text-white/70 font-light leading-relaxed max-w-2xl mx-auto">
                    Get pre-approved for owner financing on select properties. Complete this form to get started with your application process.
                </p>
            </div>

            <form id="owner-financing-form" class="space-y-8">
                <!-- Personal Information -->
                <div class="space-y-6">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-medium text-secondary mb-2">Personal Information</h3>
                        <div class="w-16 h-px bg-secondary/30 mx-auto"></div>
                    </div>
                    
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
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Phone Number *</label>
                            <input 
                                type="tel" 
                                name="phone"
                                required
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Current Address *</label>
                        <input 
                            type="text" 
                            name="address"
                            required
                            placeholder="Street Address, City, State, ZIP"
                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                        />
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="space-y-6">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-medium text-secondary mb-2">Financial Information</h3>
                        <div class="w-16 h-px bg-secondary/30 mx-auto"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Annual Income *</label>
                            <input 
                                type="text" 
                                name="annualIncome"
                                required
                                placeholder="$75,000"
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Employment Status *</label>
                            <div class="relative">
                                <select name="employmentStatus" required class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                                    <option value="">Select Status</option>
                                    <option value="employed">Employed</option>
                                    <option value="self-employed">Self-Employed</option>
                                    <option value="retired">Retired</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Down Payment Available</label>
                            <input 
                                type="text" 
                                name="downPayment"
                                placeholder="$25,000"
                                class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light"
                            />
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Credit Score Range</label>
                            <div class="relative">
                                <select name="creditScore" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                                    <option value="">Select Range</option>
                                    <option value="excellent">Excellent (750+)</option>
                                    <option value="good">Good (700-749)</option>
                                    <option value="fair">Fair (650-699)</option>
                                    <option value="poor">Poor (600-649)</option>
                                    <option value="unknown">Not Sure</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Interest -->
                <div class="space-y-6">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-medium text-secondary mb-2">Property Interest</h3>
                        <div class="w-16 h-px bg-secondary/30 mx-auto"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Property Type Interest</label>
                            <div class="relative">
                                <select name="propertyType" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                                    <option value="">Select Type</option>
                                    <option value="hunting">Hunting Land</option>
                                    <option value="farms">Farms</option>
                                    <option value="ranches">Ranches</option>
                                    <option value="residential">Residential</option>
                                    <option value="waterfront">Waterfront</option>
                                    <option value="any">Any Property Type</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="h-4 w-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Budget Range</label>
                            <div class="relative">
                                <select name="budgetRange" class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 appearance-none cursor-pointer font-light">
                                    <option value="">Select Range</option>
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
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-light text-secondary/80 tracking-[0.15em] uppercase">Additional Information</label>
                        <textarea 
                            name="additionalInfo"
                            rows="4"
                            placeholder="Tell us more about your property needs, timeline, or any questions you have about owner financing..."
                            class="w-full bg-black/30 border border-white/10 rounded-2xl px-5 py-4 text-white/90 placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary/30 backdrop-blur-sm transition-all duration-300 hover:border-white/20 hover:bg-black/40 font-light resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center pt-8">
                    <button type="submit" class="group relative inline-flex items-center btn-premium text-black px-12 py-4 rounded-2xl font-medium text-base tracking-wide transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-secondary/30">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                        <div class="relative flex items-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Submit Application</span>
                        </div>
                    </button>
                    
                    <p class="text-white/60 text-sm mt-4 max-w-2xl mx-auto">
                        By submitting this form, you agree to be contacted by JB Land & Home Realty regarding owner financing opportunities. Your information will be kept confidential.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('owner-financing-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Create email subject and body
    const subject = encodeURIComponent('Owner Financing Pre-Approval Application - ' + data.firstName + ' ' + data.lastName);
    const body = encodeURIComponent(`
Owner Financing Pre-Approval Application

PERSONAL INFORMATION:
Name: ${data.firstName} ${data.lastName}
Email: ${data.email}
Phone: ${data.phone}
Address: ${data.address}

FINANCIAL INFORMATION:
Annual Income: ${data.annualIncome}
Employment Status: ${data.employmentStatus}
Down Payment Available: ${data.downPayment || 'Not specified'}
Credit Score Range: ${data.creditScore || 'Not specified'}

PROPERTY INTEREST:
Property Type: ${data.propertyType || 'Not specified'}
Budget Range: ${data.budgetRange || 'Not specified'}

ADDITIONAL INFORMATION:
${data.additionalInfo || 'None provided'}

Please contact me to discuss owner financing options.

Thank you!
    `);
    
    // Open email client
    window.location.href = `mailto:jblandandhomerealty@gmail.com?subject=${subject}&body=${body}`;
    
    // Show success message
    alert('Thank you for your application! Your email client should now open with your pre-filled application. If it doesn\'t open automatically, please contact us directly at jblandandhomerealty@gmail.com.');
});
</script>
