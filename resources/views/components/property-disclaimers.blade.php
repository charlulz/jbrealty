<!-- Property Page Compliance Disclaimers -->
@props(['property'])

<div class="bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 mt-8">
    <h3 class="text-lg font-serif font-medium text-secondary mb-6 border-b border-secondary/20 pb-4">
        Legal Disclaimers
    </h3>

    <!-- Fair Housing Statement -->
    <div class="flex items-start mb-6">
        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
            <!-- Equal Housing Logo -->
            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L3 7v2h18V7l-9-5zM5 11v8h4v-6h6v6h4v-8H5z"/>
                <rect x="7" y="13" width="2" height="2"/>
                <rect x="15" y="13" width="2" height="2"/>
            </svg>
        </div>
        <div>
            <div class="text-white font-medium text-sm mb-2">Equal Housing Opportunity</div>
            <p class="text-white/70 text-xs leading-relaxed">
                This property is offered without regard to race, color, religion, sex, handicap, familial status, or national origin.
            </p>
        </div>
    </div>

    <!-- Property Information Disclaimer -->
    <div class="space-y-4 text-xs text-white/60">
        <div class="bg-white/5 rounded-2xl p-4">
            <p class="font-medium text-white/80 mb-2">Important Property Information:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>All information deemed reliable but not guaranteed</li>
                <li>Property details subject to errors, omissions, and changes without notice</li>
                <li>Buyers should verify all information independently</li>
                <li>Square footage, acreage, and dimensions are approximate</li>
                <li>Property boundaries should be verified by survey</li>
                @if($property->mls_number)
                <li>MLS #{{ $property->mls_number }} - Information provided by MLS</li>
                @endif
            </ul>
        </div>

        <!-- Listing Agent License -->
        <div class="bg-white/5 rounded-2xl p-4">
            <p class="font-medium text-white/80 mb-2">Licensed Professional:</p>
            <p>
                Listed by: Jeremiah Brown, Principal Broker<br>
                Kentucky Real Estate License #: 294658<br>
                JB Land & Home Realty<br>
                4629 Maysville Road, Carlisle, KY 40311
            </p>
        </div>

        <!-- Last Updated -->
        <div class="text-center pt-4 border-t border-white/10">
            <p class="text-white/50">
                Information last updated: {{ $property->updated_at->format('M j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</div>
