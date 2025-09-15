@props([
    'latitude' => null,
    'longitude' => null,
    'editable' => false,
    'height' => null,
    'title' => 'Property Location'
])

@php
    $mapId = 'property-map-' . uniqid();
    // Ensure map has a minimum height for Leaflet initialization
    // CSS responsive classes will override this for final sizing
    $mapHeight = $height ? "style=\"height:{$height}\"" : 'style="min-height:280px;height:400px;"';
@endphp

<div class="property-map-container w-full max-w-full">
    <div 
        id="{{ $mapId }}" 
        class="property-map property-map-responsive w-full max-w-full rounded-3xl border border-white/10 bg-black/20 backdrop-blur-2xl overflow-hidden" 
        {!! $mapHeight !!}
        data-latitude="{{ $latitude ?: 38.0293 }}" 
        data-longitude="{{ $longitude ?: -78.4767 }}" 
        data-editable="{{ $editable ? 'true' : 'false' }}"
        data-title="{{ $title }}"
    ></div>
    
    @if($editable)
        <div class="mt-4 p-4 bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl">
            <h4 class="text-white font-medium mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Map Instructions
            </h4>
            <div class="space-y-3 text-sm text-white/80">
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-secondary rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div>
                        <strong class="text-secondary">Auto-Location:</strong> Enter address details above and the map will automatically update with coordinates.
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-secondary rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div>
                        <strong class="text-secondary">Manual:</strong> Tap anywhere on the map or enter latitude/longitude coordinates manually.
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Map Legend for Public View -->
        <div class="mt-4 flex items-center justify-between bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-4">
            <div class="flex items-center text-white/80 text-sm">
                <svg class="w-4 h-4 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $title }}</span>
            </div>
            <div class="text-xs text-white/60">
                Tap and drag to explore
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
/* Mobile-Optimized Map Styles */
.property-map-container {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
    box-sizing: border-box;
}

.property-map-responsive {
    width: 100% !important;
    max-width: 100% !important;
    height: 280px !important; /* Mobile default */
    min-height: 280px !important;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

@media (min-width: 640px) {
    .property-map-responsive {
        height: 320px !important;
        min-height: 320px !important;
    }
}

@media (min-width: 768px) {
    .property-map-responsive {
        height: 350px !important;
        min-height: 350px !important;
    }
}

@media (min-width: 1024px) {
    .property-map-responsive {
        height: 400px !important;
        min-height: 400px !important;
    }
}

/* Custom Leaflet Control Styling for Premium Theme */
.leaflet-control-zoom a {
    background-color: rgba(0, 0, 0, 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    backdrop-filter: blur(12px);
    border-radius: 12px !important;
    width: 40px !important;
    height: 40px !important;
    line-height: 38px !important;
    margin: 2px !important;
    transition: all 0.3s ease !important;
}

.leaflet-control-zoom a:hover {
    background-color: #fcce00 !important;
    color: black !important;
    border-color: #fcce00 !important;
    transform: scale(1.05);
}

.leaflet-popup-content-wrapper {
    background: rgba(0, 0, 0, 0.9) !important;
    border: 1px solid rgba(252, 206, 0, 0.3) !important;
    border-radius: 16px !important;
    backdrop-filter: blur(12px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

.leaflet-popup-content {
    color: white !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    margin: 16px !important;
}

.leaflet-popup-tip {
    background: rgba(0, 0, 0, 0.9) !important;
    border: 1px solid rgba(252, 206, 0, 0.3) !important;
}

.leaflet-control-attribution {
    background: rgba(0, 0, 0, 0.7) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    border-radius: 8px !important;
    padding: 4px 8px !important;
    font-size: 11px !important;
    backdrop-filter: blur(8px);
}

/* Mobile touch improvements */
@media (max-width: 640px) {
    .leaflet-control-zoom a {
        width: 44px !important;
        height: 44px !important;
        line-height: 42px !important;
        font-size: 18px !important;
    }
    
    .leaflet-popup-content-wrapper {
        max-width: 280px !important;
    }
    
    .leaflet-popup-content {
        font-size: 13px !important;
        margin: 12px !important;
    }
}

/* Prevent map container overflow on mobile */
@media (max-width: 768px) {
    .property-map-container {
        margin: 0;
        padding: 0;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .property-map-responsive {
        border-radius: 1rem;
        overflow: hidden;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let mapInitRetries = 0;
    const maxRetries = 3;
    
    // Wait for Leaflet to be available
    function initMap() {
        mapInitRetries++;
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded. Please check that Leaflet JS is included.');
            
            // Show error message in map container
            const mapElement = document.getElementById('{{ $mapId }}');
            if (mapElement) {
                mapElement.innerHTML = `
                    <div class="flex items-center justify-center h-full text-white/60 text-center p-6">
                        <div>
                            <svg class="w-12 h-12 mx-auto mb-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p class="text-sm">Map temporarily unavailable</p>
                        </div>
                    </div>
                `;
            }
            return;
        }
        
        const mapElement = document.getElementById('{{ $mapId }}');
        if (!mapElement) {
            console.error('Map element not found: {{ $mapId }}');
            return;
        }
        
        console.log('Initializing map for element: {{ $mapId }}');
        
        try {
            // Ensure map element has proper dimensions before initializing
            const computedStyle = window.getComputedStyle(mapElement);
            const elementHeight = parseInt(computedStyle.height);
            const elementWidth = parseInt(computedStyle.width);
            
            console.log('Map element dimensions:', { width: elementWidth, height: elementHeight });
            
            // If dimensions are too small, wait a bit longer (with retry limit)
            if (elementHeight < 100 || elementWidth < 100) {
                if (mapInitRetries < maxRetries) {
                    console.log(`Map dimensions too small (attempt ${mapInitRetries}/${maxRetries}), retrying in 200ms...`);
                    setTimeout(initMap, 200);
                    return;
                } else {
                    console.warn('Map dimensions still too small after retries, initializing anyway...');
                }
            }
            
            const latitude = parseFloat(mapElement.dataset.latitude) || 38.4022; // Default to Carter County, KY
            const longitude = parseFloat(mapElement.dataset.longitude) || -82.9593;
            const editable = mapElement.dataset.editable === 'true';
            const title = mapElement.dataset.title || 'Property Location';
            
            console.log('Map coordinates:', { latitude, longitude, editable });
            
            // Mobile-optimized map options
            const mapOptions = {
                zoomControl: true,
                scrollWheelZoom: !editable, // Disable scroll zoom on mobile for better UX
                touchZoom: true,
                doubleClickZoom: true,
                boxZoom: false,
                keyboard: true,
                dragging: true,
                tap: true,
                tapTolerance: 15, // Increased tolerance for mobile
                zoomSnap: 1,
                zoomDelta: 1,
                wheelDeblounceTime: 40,
                wheelPxPerZoomLevel: 60
            };
            
            // Initialize map with mobile-friendly settings
            const map = L.map(mapElement.id, mapOptions).setView([latitude, longitude], 
                (latitude === 38.4022 && longitude === -82.9593) ? 10 : 15
            );
            
            // Position zoom controls for mobile
            map.zoomControl.setPosition('topright');
            
            // Add OpenStreetMap tiles with error handling
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
            }).addTo(map);
            
            // Custom marker icon for better visibility
            const customIcon = L.divIcon({
                html: `
                    <div class="flex items-center justify-center w-8 h-8 bg-secondary rounded-full border-2 border-white shadow-lg">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                `,
                className: 'custom-marker',
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });
            
            // Add marker if we have specific coordinates
            let marker;
            if (latitude && longitude && !(latitude === 38.4022 && longitude === -82.9593)) {
                marker = L.marker([latitude, longitude], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="text-center">
                            <div class="font-medium text-secondary mb-2">${title}</div>
                            <div class="text-sm opacity-80">
                                ${latitude.toFixed(6)}, ${longitude.toFixed(6)}
                            </div>
                        </div>
                    `)
                    .openPopup();
            }
            
            // If editable, allow clicking to set location
            if (editable) {
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    
                    console.log('Map clicked:', { lat, lng });
                    
                    // Remove existing marker
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    
                    // Add new marker
                    marker = L.marker([lat, lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div class="text-center">
                                <div class="font-medium text-secondary mb-2">Property Location</div>
                                <div class="text-sm opacity-80">
                                    Lat: ${lat.toFixed(6)}<br>
                                    Lng: ${lng.toFixed(6)}
                                </div>
                            </div>
                        `)
                        .openPopup();
                    
                    // Update form fields
                    const latInput = document.querySelector('input[name="latitude"]');
                    const lngInput = document.querySelector('input[name="longitude"]');
                    
                    if (latInput) {
                        latInput.value = lat.toFixed(6);
                        latInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    if (lngInput) {
                        lngInput.value = lng.toFixed(6);
                        lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    
                    console.log('Updated form fields:', { lat: lat.toFixed(6), lng: lng.toFixed(6) });
                    
                    // Dispatch custom event for any listeners
                    mapElement.dispatchEvent(new CustomEvent('locationSelected', {
                        detail: { latitude: lat, longitude: lng }
                    }));
                });
                
                // Listen for coordinate input changes
                const latInput = document.querySelector('input[name="latitude"]');
                const lngInput = document.querySelector('input[name="longitude"]');
                
                function updateMarkerFromInputs() {
                    const lat = parseFloat(latInput?.value);
                    const lng = parseFloat(lngInput?.value);
                    
                    if (lat && lng && !isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                        console.log('Updating marker from inputs:', { lat, lng });
                        
                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        // Add new marker and center map
                        marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup(`
                                <div class="text-center">
                                    <div class="font-medium text-secondary mb-2">Property Location</div>
                                    <div class="text-sm opacity-80">
                                        Lat: ${lat.toFixed(6)}<br>
                                        Lng: ${lng.toFixed(6)}
                                    </div>
                                </div>
                            `);
                        
                        map.setView([lat, lng], 15);
                    }
                }
                
                if (latInput && lngInput) {
                    latInput.addEventListener('blur', updateMarkerFromInputs);
                    lngInput.addEventListener('blur', updateMarkerFromInputs);
                    latInput.addEventListener('change', updateMarkerFromInputs);
                    lngInput.addEventListener('change', updateMarkerFromInputs);
                }
            }
            
            // Address Geocoding Functionality (same as before but with status styling)
            const addressFields = {
                street: document.querySelector('input[name="street_address"]'),
                city: document.querySelector('input[name="city"]'),
                state: document.querySelector('select[name="state"]'),
                zip: document.querySelector('input[name="zip_code"]')
            };
            
            let geocodeTimeout;
            
            async function geocodeAddress() {
                // Build address string
                const addressParts = [];
                
                if (addressFields.street && addressFields.street.value.trim()) {
                    addressParts.push(addressFields.street.value.trim());
                }
                
                if (addressFields.city && addressFields.city.value.trim()) {
                    addressParts.push(addressFields.city.value.trim());
                }
                
                if (addressFields.state && addressFields.state.value) {
                    const selectedOption = addressFields.state.selectedOptions[0];
                    if (selectedOption && selectedOption.text !== 'Select State') {
                        addressParts.push(selectedOption.text);
                    }
                }
                
                if (addressFields.zip && addressFields.zip.value.trim()) {
                    addressParts.push(addressFields.zip.value.trim());
                }
                
                if (!addressFields.city || !addressFields.city.value.trim()) {
                    return;
                }
                
                const address = addressParts.join(', ');
                console.log('Geocoding address:', address);
                
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=us&q=${encodeURIComponent(address)}`);
                    
                    if (!response.ok) {
                        throw new Error('Geocoding request failed');
                    }
                    
                    const results = await response.json();
                    
                    if (results && results.length > 0) {
                        const result = results[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);
                        
                        console.log('Geocoding successful:', { lat, lng, display_name: result.display_name });
                        
                        const latInput = document.querySelector('input[name="latitude"]');
                        const lngInput = document.querySelector('input[name="longitude"]');
                        
                        if (latInput && lngInput) {
                            latInput.value = lat.toFixed(6);
                            lngInput.value = lng.toFixed(6);
                            latInput.dispatchEvent(new Event('input', { bubbles: true }));
                            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(map)
                            .bindPopup(`
                                <div class="text-center">
                                    <div class="font-medium text-secondary mb-2">${result.display_name.split(',')[0]}</div>
                                    <div class="text-sm opacity-80">
                                        Lat: ${lat.toFixed(6)}<br>
                                        Lng: ${lng.toFixed(6)}
                                    </div>
                                </div>
                            `)
                            .openPopup();
                        
                        map.setView([lat, lng], 15);
                        showAddressStatus('✓ Location found and map updated', 'success');
                        
                    } else {
                        console.log('No geocoding results found for:', address);
                        showAddressStatus('Address not found. Please check and try again.', 'warning');
                    }
                    
                } catch (error) {
                    console.error('Geocoding error:', error);
                    showAddressStatus('Unable to locate address. Please enter coordinates manually.', 'error');
                }
            }
            
            function showAddressStatus(message, type) {
                const existingStatus = document.querySelector('.address-geocode-status');
                if (existingStatus) {
                    existingStatus.remove();
                }
                
                const statusEl = document.createElement('div');
                statusEl.className = `address-geocode-status mt-3 p-3 rounded-2xl text-sm border backdrop-blur-2xl ${
                    type === 'success' ? 'bg-green-500/20 text-green-200 border-green-500/30' :
                    type === 'warning' ? 'bg-yellow-500/20 text-yellow-200 border-yellow-500/30' :
                    'bg-red-500/20 text-red-200 border-red-500/30'
                }`;
                statusEl.textContent = message;
                
                mapElement.parentNode.appendChild(statusEl);
                
                setTimeout(() => {
                    if (statusEl.parentNode) {
                        statusEl.remove();
                    }
                }, 5000);
            }
            
            function debouncedGeocode() {
                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(geocodeAddress, 1000);
            }
            
            if (editable) {
                Object.values(addressFields).forEach(field => {
                    if (field) {
                        field.addEventListener('input', debouncedGeocode);
                        field.addEventListener('change', debouncedGeocode);
                    }
                });
                
                console.log('Address geocoding enabled');
            }
            
            // Mobile-specific map enhancements
            map.on('zoomend', function() {
                // Ensure minimum zoom for mobile usability
                if (map.getZoom() < 8) {
                    map.setZoom(8);
                }
            });
            
            // Trigger resize after initialization
            setTimeout(function() {
                map.invalidateSize();
            }, 250);
            
            // Handle orientation changes on mobile
            window.addEventListener('orientationchange', function() {
                setTimeout(function() {
                    map.invalidateSize();
                }, 500);
            });
            
            console.log('Map initialized successfully with mobile optimizations');
            
        } catch (error) {
            console.error(`Error initializing map (attempt ${mapInitRetries}/${maxRetries}):`, error);
            
            // Retry if we haven't exceeded max attempts
            if (mapInitRetries < maxRetries) {
                console.log(`Retrying map initialization in 300ms...`);
                setTimeout(initMap, 300);
                return;
            }
            
            // Show fallback content after all retries exhausted
            const mapElement = document.getElementById('{{ $mapId }}');
            if (mapElement) {
                mapElement.innerHTML = `
                    <div class="flex items-center justify-center h-full text-white/60 text-center p-6">
                        <div>
                            <svg class="w-12 h-12 mx-auto mb-4 text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm">Unable to load map</p>
                            <p class="text-xs mt-2 opacity-60">Please refresh the page</p>
                        </div>
                    </div>
                `;
            }
        }
    }
    
    // Initialize the map with appropriate delay
    // Shorter delay for desktop, slightly longer for mobile touch devices
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const initDelay = isMobile ? 150 : 100;
    
    console.log('Scheduling map initialization:', { isMobile, delay: initDelay });
    
    // Reset retry counter for the initial call
    mapInitRetries = 0;
    setTimeout(initMap, initDelay);
});
</script>
@endpush