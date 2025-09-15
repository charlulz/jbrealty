@props([
    'latitude' => null,
    'longitude' => null,
    'editable' => false,
    'height' => '400px',
    'title' => 'Property Location'
])

@php
    $mapId = 'property-map-' . uniqid();
@endphp

<div class="property-map-container">
    <div 
        id="{{ $mapId }}" 
        class="property-map rounded-lg border border-gray-200 dark:border-gray-700" 
        style="height: {{ $height }};"
        data-latitude="{{ $latitude ?: 38.0293 }}" 
        data-longitude="{{ $longitude ?: -78.4767 }}" 
        data-editable="{{ $editable ? 'true' : 'false' }}"
        data-title="{{ $title }}"
    ></div>
    
    @if($editable)
        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2 space-y-2">
            <p class="flex items-center">
                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <strong>Auto-Location:</strong> Enter address details above and the map will automatically update with coordinates.
            </p>
            <p class="flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                </svg>
                <strong>Manual:</strong> Click anywhere on the map or enter latitude/longitude coordinates manually.
            </p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Leaflet to be available
    function initMap() {
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded. Please check that Leaflet JS is included.');
            return;
        }
        
        const mapElement = document.getElementById('{{ $mapId }}');
        if (!mapElement) {
            console.error('Map element not found: {{ $mapId }}');
            return;
        }
        
        console.log('Initializing map for element: {{ $mapId }}');
        
        try {
            const latitude = parseFloat(mapElement.dataset.latitude) || 38.4022; // Default to Carter County, KY
            const longitude = parseFloat(mapElement.dataset.longitude) || -82.9593;
            const editable = mapElement.dataset.editable === 'true';
            const title = mapElement.dataset.title || 'Property Location';
            
            console.log('Map coordinates:', { latitude, longitude, editable });
            
            // Initialize map with error handling
            const map = L.map(mapElement.id).setView([latitude, longitude], 
                (latitude === 38.4022 && longitude === -82.9593) ? 10 : 15
            );
            
            // Add OpenStreetMap tiles with error handling
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
            }).addTo(map);
            
            // Add marker if we have specific coordinates
            let marker;
            if (latitude && longitude && !(latitude === 38.4022 && longitude === -82.9593)) {
                marker = L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup(title)
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
                    marker = L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup('Property Location<br>Lat: ' + lat.toFixed(6) + '<br>Lng: ' + lng.toFixed(6))
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
                        marker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup('Property Location<br>Lat: ' + lat.toFixed(6) + '<br>Lng: ' + lng.toFixed(6));
                        
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
            
            // Address Geocoding Functionality
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
                    // Get state text, not value
                    const selectedOption = addressFields.state.selectedOptions[0];
                    if (selectedOption && selectedOption.text !== 'Select State') {
                        addressParts.push(selectedOption.text);
                    }
                }
                
                if (addressFields.zip && addressFields.zip.value.trim()) {
                    addressParts.push(addressFields.zip.value.trim());
                }
                
                // Need at least city for geocoding
                if (!addressFields.city || !addressFields.city.value.trim()) {
                    return;
                }
                
                const address = addressParts.join(', ');
                console.log('Geocoding address:', address);
                
                try {
                    // Use OpenStreetMap Nominatim (free, no API key required)
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
                        
                        // Update coordinate fields
                        const latInput = document.querySelector('input[name="latitude"]');
                        const lngInput = document.querySelector('input[name="longitude"]');
                        
                        if (latInput && lngInput) {
                            latInput.value = lat.toFixed(6);
                            lngInput.value = lng.toFixed(6);
                            
                            // Trigger input events
                            latInput.dispatchEvent(new Event('input', { bubbles: true }));
                            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        
                        // Remove existing marker
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        // Add new marker and center map
                        marker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(`${result.display_name}<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`)
                            .openPopup();
                        
                        // Center map on location
                        map.setView([lat, lng], 15);
                        
                        // Show success feedback
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
                // Remove existing status
                const existingStatus = document.querySelector('.address-geocode-status');
                if (existingStatus) {
                    existingStatus.remove();
                }
                
                // Create status element
                const statusEl = document.createElement('div');
                statusEl.className = `address-geocode-status mt-2 p-2 rounded-md text-sm ${
                    type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                    type === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                    'bg-red-100 text-red-800 border border-red-200'
                }`;
                statusEl.textContent = message;
                
                // Add after map element
                mapElement.parentNode.appendChild(statusEl);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (statusEl.parentNode) {
                        statusEl.remove();
                    }
                }, 5000);
            }
            
            function debouncedGeocode() {
                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(geocodeAddress, 1000); // Wait 1 second after user stops typing
            }
            
            // Add event listeners to address fields
            if (editable) {
                Object.values(addressFields).forEach(field => {
                    if (field) {
                        field.addEventListener('input', debouncedGeocode);
                        field.addEventListener('change', debouncedGeocode);
                    }
                });
                
                console.log('Address geocoding enabled');
            }
            
            // Trigger a resize after a short delay to ensure proper rendering
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
            
            console.log('Map initialized successfully');
            
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }
    
    // Initialize the map with a small delay to ensure DOM is ready
    setTimeout(initMap, 100);
});
</script>
@endpush
