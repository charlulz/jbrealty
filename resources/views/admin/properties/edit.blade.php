<x-layouts.app :title="__('Edit Property')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <flux:button variant="ghost" size="sm" :href="route('properties.show', $property)" wire:navigate>
                        <flux:icon.arrow-left class="w-4 h-4 mr-1" />
                        Back to Property
                    </flux:button>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Property</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $property->title }}</p>
            </div>
            
            <div class="flex gap-2">
                <flux:button variant="outline" :href="route('properties.show', $property)" wire:navigate>
                    <flux:icon.eye class="w-4 h-4 mr-2" />
                    View
                </flux:button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.properties.update', $property) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Basic Information</h2>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <flux:field>
                            <flux:label>Property Title</flux:label>
                            <flux:input name="title" value="{{ old('title', $property->title) }}" placeholder="Beautiful Hunting Property with Creek" required />
                            <flux:error name="title" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Property Type</flux:label>
                            <flux:select name="property_type" required>
                                <option value="">Select Property Type</option>
                                <option value="hunting" {{ old('property_type', $property->property_type) === 'hunting' ? 'selected' : '' }}>Hunting Land</option>
                                <option value="farms" {{ old('property_type', $property->property_type) === 'farms' ? 'selected' : '' }}>Farm</option>
                                <option value="ranches" {{ old('property_type', $property->property_type) === 'ranches' ? 'selected' : '' }}>Ranch</option>
                                <option value="residential" {{ old('property_type', $property->property_type) === 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('property_type', $property->property_type) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="waterfront" {{ old('property_type', $property->property_type) === 'waterfront' ? 'selected' : '' }}>Waterfront</option>
                                <option value="timber" {{ old('property_type', $property->property_type) === 'timber' ? 'selected' : '' }}>Timber</option>
                                <option value="development" {{ old('property_type', $property->property_type) === 'development' ? 'selected' : '' }}>Development</option>
                                <option value="investment" {{ old('property_type', $property->property_type) === 'investment' ? 'selected' : '' }}>Investment</option>
                            </flux:select>
                            <flux:error name="property_type" />
                        </flux:field>
                    </div>

                    <div class="md:col-span-2">
                        <flux:field>
                            <flux:label>Description</flux:label>
                            <flux:textarea name="description" rows="4" placeholder="Describe the property features, location highlights, and unique characteristics...">{{ old('description', $property->description) }}</flux:textarea>
                            <flux:error name="description" />
                        </flux:field>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Location</h2>
                
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <flux:field>
                            <flux:label>Street Address (Optional)</flux:label>
                            <flux:input name="street_address" value="{{ old('street_address', $property->street_address) }}" placeholder="123 County Road" />
                            <flux:error name="street_address" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>City</flux:label>
                            <flux:input name="city" value="{{ old('city', $property->city) }}" placeholder="Madison" required />
                            <flux:error name="city" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>County</flux:label>
                            <flux:input name="county" value="{{ old('county', $property->county) }}" placeholder="Madison County" required />
                            <flux:error name="county" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>State</flux:label>
                            <flux:select name="state" required>
                                <option value="">Select State</option>
                                <option value="VA" {{ old('state', $property->state) === 'VA' ? 'selected' : '' }}>Virginia</option>
                                <option value="MD" {{ old('state', $property->state) === 'MD' ? 'selected' : '' }}>Maryland</option>
                                <option value="WV" {{ old('state', $property->state) === 'WV' ? 'selected' : '' }}>West Virginia</option>
                                <option value="NC" {{ old('state', $property->state) === 'NC' ? 'selected' : '' }}>North Carolina</option>
                                <option value="PA" {{ old('state', $property->state) === 'PA' ? 'selected' : '' }}>Pennsylvania</option>
                                <option value="TN" {{ old('state', $property->state) === 'TN' ? 'selected' : '' }}>Tennessee</option>
                            </flux:select>
                            <flux:error name="state" />
                        </flux:field>
                    </div>
                </div>

                <!-- Interactive Map -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Property Location</h3>
                    <x-property-map 
                        :latitude="old('latitude', $property->latitude)" 
                        :longitude="old('longitude', $property->longitude)" 
                        :editable="true"
                        height="350px"
                        title="Click to update property location"
                    />
                </div>
            </div>

            <!-- Pricing & Acreage -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Pricing & Acreage</h2>
                
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <flux:field>
                            <flux:label>Total Acres</flux:label>
                            <flux:input type="number" step="0.01" name="total_acres" value="{{ old('total_acres', $property->total_acres) }}" placeholder="50.00" required />
                            <flux:error name="total_acres" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Price ($)</flux:label>
                            <flux:input type="number" name="price" value="{{ old('price', $property->price) }}" placeholder="250000" required />
                            <flux:error name="price" />
                        </flux:field>
                    </div>

                    <div>
                        <flux:field>
                            <flux:label>Status</flux:label>
                            <flux:select name="status" required>
                                <option value="draft" {{ old('status', $property->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $property->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status', $property->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sold" {{ old('status', $property->status) === 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="off_market" {{ old('status', $property->status) === 'off_market' ? 'selected' : '' }}>Off Market</option>
                            </flux:select>
                            <flux:error name="status" />
                        </flux:field>
                    </div>
                </div>
            </div>

            <!-- Property Features -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Property Features</h2>
                
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-4">Land Features</h3>
                        <div class="space-y-3">
                            <flux:checkbox name="water_access" value="1" label="Water Access" {{ old('water_access', $property->water_access) ? 'checked' : '' }} />
                            <flux:checkbox name="mineral_rights" value="1" label="Mineral Rights" {{ old('mineral_rights', $property->mineral_rights) ? 'checked' : '' }} />
                            <flux:checkbox name="timber_rights" value="1" label="Timber Rights" {{ old('timber_rights', $property->timber_rights) ? 'checked' : '' }} />
                            <flux:checkbox name="hunting_rights" value="1" label="Hunting Rights" {{ old('hunting_rights', $property->hunting_rights) ? 'checked' : '' }} />
                        </div>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-4">Improvements</h3>
                        <div class="space-y-3">
                            <flux:checkbox name="has_home" value="1" label="Home/Cabin" {{ old('has_home', $property->has_home) ? 'checked' : '' }} />
                            <flux:checkbox name="has_barn" value="1" label="Barn/Outbuildings" {{ old('has_barn', $property->has_barn) ? 'checked' : '' }} />
                            <flux:checkbox name="electric_available" value="1" label="Electricity Available" {{ old('electric_available', $property->electric_available) ? 'checked' : '' }} />
                            <flux:checkbox name="water_available" value="1" label="Water Available" {{ old('water_available', $property->water_available) ? 'checked' : '' }} />
                        </div>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white mb-4">Listing Options</h3>
                        <div class="space-y-3">
                            <flux:checkbox name="featured" value="1" label="Featured Property" {{ old('featured', $property->featured) ? 'checked' : '' }} />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Images -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Property Images</h2>
                
                <!-- Existing Images -->
                @if($property->images->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Current Images ({{ $property->images->count() }})</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                            @foreach($property->images->take(12) as $image)
                                <div class="relative group">
                                    <img 
                                        src="{{ $image->url }}" 
                                        alt="{{ $image->alt_text }}" 
                                        class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                    >
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 rounded-lg flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            @if($image->is_primary)
                                                <span class="bg-yellow-500 text-black px-2 py-1 rounded text-xs font-semibold">PRIMARY</span>
                                            @else
                                                <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs">{{ strtoupper($image->category) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="absolute top-2 right-2">
                                        @if($image->is_primary)
                                            <div class="w-3 h-3 bg-yellow-400 rounded-full border-2 border-white"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($property->images->count() > 12)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Showing 12 of {{ $property->images->count() }} images. 
                                <a href="#" class="text-indigo-600 hover:text-indigo-500">View all images</a>
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Add New Images -->
                <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Images</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Upload additional images for this property. New images will be added to the existing ones.</p>
                    
                    <!-- Main Image Upload Area -->
                    <div class="mb-6">
                        <flux:field>
                            <flux:label>Additional Property Images</flux:label>
                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-600 px-6 py-10 bg-gray-50 dark:bg-gray-800/50">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600 dark:text-gray-400">
                                        <label for="property-images-edit" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-700 px-3 py-2 font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                            <span>Upload images</span>
                                            <input id="property-images-edit" name="images[]" type="file" class="sr-only" multiple accept="image/jpeg,image/jpg,image/png,image/webp">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600 dark:text-gray-500">PNG, JPG, JPEG, WebP up to 10MB each</p>
                                </div>
                            </div>
                            <flux:error name="images" />
                            <flux:error name="images.*" />
                        </flux:field>
                    </div>

                    <!-- Image Categories for New Uploads -->
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Exterior Views</flux:label>
                                <input type="file" name="exterior_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                                <flux:description>Property exterior, landscaping, entrance</flux:description>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Land & Terrain</flux:label>
                                <input type="file" name="land_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900 dark:file:text-green-300">
                                <flux:description>Fields, forests, water features, terrain</flux:description>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Aerial Views</flux:label>
                                <input type="file" name="aerial_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                                <flux:description>Drone shots, boundaries, overhead views</flux:description>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Interior</flux:label>
                                <input type="file" name="interior_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900 dark:file:text-purple-300">
                                <flux:description>Home interior, rooms (if applicable)</flux:description>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Amenities</flux:label>
                                <input type="file" name="amenity_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 dark:file:bg-yellow-900 dark:file:text-yellow-300">
                                <flux:description>Barns, sheds, ponds, hunting features</flux:description>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label class="text-sm font-medium">Documents</flux:label>
                                <input type="file" name="document_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-900 dark:file:text-gray-300">
                                <flux:description>Survey maps, plats, documentation</flux:description>
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-end pt-6">
                <flux:button variant="ghost" type="button" :href="route('properties.show', $property)" wire:navigate>
                    Cancel
                </flux:button>
                <flux:button type="submit">
                    <flux:icon.check class="w-4 h-4 mr-2" />
                    Update Property
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
