@props(['property', 'compact' => false])

<div class="relative w-full max-w-full {{ $compact ? '' : 'bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-4 sm:p-6 lg:p-8' }}"
     x-data="propertyPacketForm()"
     x-init="init()">
     
    @if(!$compact)
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl sm:text-2xl font-serif font-medium text-white">Get Instant Property Packet</h3>
            </div>
            <p class="text-white/70 text-sm sm:text-base">Receive a comprehensive property information packet delivered instantly to your phone or email.</p>
        </div>

        <!-- Benefits List -->
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center text-white/80 text-sm">
                <svg class="w-4 h-4 mr-3 text-secondary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>Property details & photos</span>
            </div>
            <div class="flex items-center text-white/80 text-sm">
                <svg class="w-4 h-4 mr-3 text-secondary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>Pricing & payment options</span>
            </div>
            <div class="flex items-center text-white/80 text-sm">
                <svg class="w-4 h-4 mr-3 text-secondary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>GPS coordinates & map</span>
            </div>
            <div class="flex items-center text-white/80 text-sm">
                <svg class="w-4 h-4 mr-3 text-secondary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>Agent contact information</span>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="relative">
        <!-- Loading Overlay -->
        <div x-show="loading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/60 backdrop-blur-sm rounded-3xl z-10 flex items-center justify-center">
            <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-4 animate-spin text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                <p class="text-white text-sm">Preparing your property packet...</p>
            </div>
        </div>

        <!-- Success State -->
        <div x-show="success" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="text-center py-8" style="display: none;">
            <div class="w-16 h-16 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h4 class="text-xl font-medium text-secondary mb-2">Property Packet Sent!</h4>
            <p class="text-white/70 mb-6">Check your <span x-text="deliveryMethod === 'email' ? 'email' : 'text messages'"></span> for your comprehensive property information packet.</p>
            <button @click="reset()" class="inline-flex items-center px-6 py-3 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black rounded-2xl transition-all duration-300 backdrop-blur-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Request Another Packet
            </button>
        </div>

        <!-- Contact Form -->
        <form @submit.prevent="submitForm()" x-show="!success" class="{{ $compact ? 'space-y-3' : 'space-y-4' }}">
            <!-- Name Fields -->
            <div class="grid grid-cols-1 {{ $compact ? '' : 'sm:grid-cols-2' }} gap-{{ $compact ? '3' : '4' }}">
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">First Name *</label>
                    <input 
                        type="text" 
                        x-model="form.firstName"
                        required
                        class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                        placeholder="John"
                    >
                </div>
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">Last Name *</label>
                    <input 
                        type="text" 
                        x-model="form.lastName"
                        required
                        class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                        placeholder="Doe"
                    >
                </div>
            </div>

            <!-- Contact Fields -->
            <div class="grid grid-cols-1 {{ $compact ? '' : 'sm:grid-cols-2' }} gap-{{ $compact ? '3' : '4' }}">
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        x-model="form.email"
                        required
                        class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                        placeholder="john@example.com"
                    >
                </div>
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">Phone Number *</label>
                    <input 
                        type="tel" 
                        x-model="form.phone"
                        required
                        class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                        placeholder="(555) 123-4567"
                    >
                </div>
            </div>

            <!-- Delivery Method -->
            <div>
                <label class="block text-white/80 text-sm font-medium mb-3">How would you like to receive your packet? *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="delivery" 
                            value="email" 
                            x-model="deliveryMethod"
                            class="sr-only"
                        >
                        <div class="flex items-center p-4 bg-black/60 border-2 rounded-2xl transition-all duration-300" :class="deliveryMethod === 'email' ? 'border-secondary bg-secondary/10' : 'border-white/20 hover:border-white/40'">
                            <svg class="w-5 h-5 mr-3" :class="deliveryMethod === 'email' ? 'text-secondary' : 'text-white/70'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <div class="font-medium" :class="deliveryMethod === 'email' ? 'text-secondary' : 'text-white'">Email Delivery</div>
                                <div class="text-sm text-white/70">PDF sent to your inbox</div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="delivery" 
                            value="sms" 
                            x-model="deliveryMethod"
                            class="sr-only"
                        >
                        <div class="flex items-center p-4 bg-black/60 border-2 rounded-2xl transition-all duration-300" :class="deliveryMethod === 'sms' ? 'border-secondary bg-secondary/10' : 'border-white/20 hover:border-white/40'">
                            <svg class="w-5 h-5 mr-3" :class="deliveryMethod === 'sms' ? 'text-secondary' : 'text-white/70'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <div>
                                <div class="font-medium" :class="deliveryMethod === 'sms' ? 'text-secondary' : 'text-white'">Text Message</div>
                                <div class="text-sm text-white/70">Link sent via SMS</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Interest Level -->
            <div>
                <label class="block text-white/80 text-sm font-medium mb-2">What's your interest level in this property?</label>
                <select 
                    x-model="form.interestLevel"
                    class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                >
                    <option value="">Select an option</option>
                    <option value="just_browsing">Just browsing</option>
                    <option value="somewhat_interested">Somewhat interested</option>
                    <option value="very_interested">Very interested</option>
                    <option value="ready_to_purchase">Ready to purchase</option>
                </select>
            </div>

            <!-- Error Message -->
            <div x-show="error" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="p-4 bg-red-500/20 border border-red-500/30 rounded-2xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-red-200 text-sm" x-text="errorMessage"></p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-secondary hover:bg-secondary/90 disabled:bg-secondary/50 text-black py-4 px-6 rounded-2xl font-medium text-center transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-secondary/30 disabled:transform-none flex items-center justify-center"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-text="loading ? 'Sending Packet...' : 'Get My Property Packet'"></span>
                </button>
            </div>

            <!-- Privacy Notice -->
            <p class="text-xs text-white/50 text-center mt-4 leading-relaxed">
                By submitting this form, you agree to receive communications about this property. 
                Your information is secure and will not be shared with third parties.
            </p>
        </form>
    </div>
</div>

<script>
function propertyPacketForm() {
    return {
        loading: false,
        success: false,
        error: false,
        errorMessage: '',
        deliveryMethod: 'email',
        form: {
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
            interestLevel: '',
            propertyId: '{{ $property->id }}',
            propertyTitle: '{{ $property->title }}',
        },
        
        init() {
            // Form is ready
        },
        
        async submitForm() {
            this.loading = true;
            this.error = false;
            this.errorMessage = '';
            
            try {
                const formData = {
                    ...this.form,
                    deliveryMethod: this.deliveryMethod,
                    propertyUrl: window.location.href
                };
                
                const response = await fetch('/api/property-packet', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    this.success = true;
                    
                    // Track conversion event
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'property_packet_request', {
                            'property_id': this.form.propertyId,
                            'delivery_method': this.deliveryMethod,
                            'interest_level': this.form.interestLevel
                        });
                    }
                } else {
                    throw new Error(data.message || 'Failed to send property packet');
                }
                
            } catch (error) {
                console.error('Property packet error:', error);
                this.error = true;
                this.errorMessage = error.message || 'Sorry, there was an error sending your packet. Please try again or call us directly.';
            } finally {
                this.loading = false;
            }
        },
        
        reset() {
            this.success = false;
            this.error = false;
            this.errorMessage = '';
            this.form = {
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                interestLevel: '',
                propertyId: '{{ $property->id }}',
                propertyTitle: '{{ $property->title }}',
            };
            this.deliveryMethod = 'email';
        }
    }
}
</script>
