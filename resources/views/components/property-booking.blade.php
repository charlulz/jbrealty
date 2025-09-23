@props(['property', 'id' => null])

<div class="relative w-full max-w-full bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-4 sm:p-6 lg:p-8" 
     @if($id) id="{{ $id }}" @endif>
    
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
            </svg>
            <h3 class="text-xl sm:text-2xl font-serif font-medium text-white">Schedule a Property Tour</h3>
        </div>
        <p class="text-white/70 text-sm sm:text-base">Book a convenient time to view this property with our expert agent.</p>
    </div>

    <!-- Booking Widget Container -->
    <div class="relative">
        <!-- Premium Frame Wrapper -->
        <div class="relative bg-black/60 border border-white/20 rounded-3xl p-4 sm:p-6 backdrop-blur-sm overflow-hidden">
            <!-- Subtle Gold Accent Border -->
            <div class="absolute inset-0 bg-gradient-to-r from-secondary/20 via-transparent to-secondary/20 rounded-3xl opacity-30"></div>
            
            <!-- Loading State -->
            <div class="relative bg-white/5 rounded-2xl min-h-[600px] sm:min-h-[700px] lg:min-h-[800px] overflow-hidden booking-calendar">
                <!-- Loading Placeholder -->
                <div id="booking-loading" class="flex items-center justify-center h-full min-h-[600px] sm:min-h-[700px] lg:min-h-[800px] text-white/60">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-4 animate-spin text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <p class="text-sm">Loading booking calendar...</p>
                    </div>
                </div>
                
                <!-- Booking Calendar Iframe -->
                <iframe 
                    src="https://api.leadconnectorhq.com/widget/booking/GVsVLwpaDE79SQHkuJ19" 
                    style="width: 100%; height: 100%; min-height: 600px; border: none; background: transparent;" 
                    scrolling="auto" 
                    id="GVsVLwpaDE79SQHkuJ19_1757972997153"
                    class="absolute inset-0 w-full h-full rounded-2xl"
                    onload="document.getElementById('booking-loading').style.display='none'"
                    title="Schedule Property Tour"
                    allowfullscreen
                ></iframe>
            </div>
        </div>

        <!-- Call-to-Action Footer -->
        <div class="mt-6 text-center">
            <div class="bg-gradient-to-r from-secondary/20 via-secondary/10 to-secondary/20 border border-secondary/30 rounded-3xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-left">
                        <h4 class="text-lg font-medium text-secondary mb-1">Ready to See This Property?</h4>
                        <p class="text-white/70 text-sm">Use the calendar above to book your tour, or contact us directly.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                           onclick="document.querySelector('.booking-calendar iframe').scrollIntoView({behavior: 'smooth', block: 'center'})"
                           class="inline-flex items-center px-6 py-3 bg-secondary hover:bg-secondary/90 text-black font-medium rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-secondary/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
                            </svg>
                            Back to Calendar
                        </button>
                        
                        <a href="tel:+18594732259" 
                           class="inline-flex items-center px-6 py-3 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black font-medium rounded-2xl transition-all duration-300 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call Now
                        </a>
                        
                        <a href="mailto:jblandandhomerealty@gmail.com?subject=Property Tour Request: {{ $property->title }}&body=Hi Jeremiah,%0D%0A%0D%0AI'm interested in scheduling a tour of the property at {{ $property->title }}.%0D%0A%0D%0APlease let me know your available times.%0D%0A%0D%0AThank you!" 
                           class="inline-flex items-center px-6 py-3 bg-black/30 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black font-medium rounded-2xl transition-all duration-300 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LeadConnector Form Embed Script -->
<script src="https://link.msgsndr.com/js/form_embed.js" type="text/javascript"></script>

<style>
/* Custom styling for booking widget */
.booking-widget-container {
    background: transparent !important;
}

/* Responsive iframe handling */
@media (max-width: 640px) {
    #GVsVLwpaDE79SQHkuJ19_1757972997153 {
        min-height: 600px !important;
        height: 600px !important;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    #GVsVLwpaDE79SQHkuJ19_1757972997153 {
        min-height: 700px !important;
        height: 700px !important;
    }
}

@media (min-width: 1025px) {
    #GVsVLwpaDE79SQHkuJ19_1757972997153 {
        min-height: 800px !important;
        height: 800px !important;
    }
}

/* Override any default iframe styling from the widget */
iframe[src*="leadconnectorhq.com"] {
    background: transparent !important;
    border-radius: 1rem !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
}

/* Custom scrollbar styling for better UX */
iframe[src*="leadconnectorhq.com"]::-webkit-scrollbar {
    width: 6px;
}

iframe[src*="leadconnectorhq.com"]::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

iframe[src*="leadconnectorhq.com"]::-webkit-scrollbar-thumb {
    background: rgba(252, 206, 0, 0.6);
    border-radius: 3px;
}

iframe[src*="leadconnectorhq.com"]::-webkit-scrollbar-thumb:hover {
    background: rgba(252, 206, 0, 0.8);
}

/* Smooth loading transition */
#booking-loading {
    transition: opacity 0.3s ease-out;
}

/* Responsive adjustments for the booking section */
@media (max-width: 640px) {
    .booking-calendar {
        min-height: 600px !important;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .booking-calendar {
        min-height: 700px !important;
    }
}

@media (min-width: 1025px) {
    .booking-calendar {
        min-height: 800px !important;
    }
}
</style>
