<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Properties</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage property listings and featured status</p>
            </div>
            <div class="flex gap-3">
                <flux:button variant="primary" :href="route('admin.properties.create')" icon="plus" wire:navigate>
                    Add Property
                </flux:button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <flux:input 
                        type="search"
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search properties..."
                        icon="magnifying-glass"
                    />
                </div>

                <!-- Status Filter -->
                <div>
                    <flux:select name="status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="off_market" {{ request('status') === 'off_market' ? 'selected' : '' }}>Off Market</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </flux:select>
                </div>

                <!-- Featured Filter -->
                <div>
                    <flux:select name="featured">
                        <option value="">All Properties</option>
                        <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured Only</option>
                        <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
                    </flux:select>
                </div>

                <!-- Filter Button -->
                <div>
                    <flux:button type="submit" variant="primary" class="w-full">
                        Filter
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Properties Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Property
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Featured
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Images
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($properties as $property)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $property->featured ? 'bg-yellow-50 dark:bg-yellow-900/20' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($property->images->count() > 0)
                                                <img class="h-12 w-12 rounded-lg object-cover" 
                                                     src="{{ $property->images->first()->url }}" 
                                                     alt="{{ $property->title }}">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <flux:icon.building-office class="h-6 w-6 text-gray-400" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $property->title }}
                                            </div>
                                            @if($property->mls_number)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    MLS #{{ $property->mls_number }}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $property->city }}, {{ $property->state }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $property->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $property->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $property->status === 'sold' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}
                                        {{ $property->status === 'off_market' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        {{ $property->status === 'draft' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                    ">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($property->price) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        onclick="toggleFeatured('{{ $property->slug }}', this)"
                                        class="featured-toggle inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors
                                            {{ $property->featured ? 'bg-yellow-100 text-yellow-800 border border-yellow-300 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 dark:border-yellow-700' : 'bg-gray-100 text-gray-600 border border-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600' }}
                                        ">
                                        <flux:icon.star class="h-3 w-3 mr-1 {{ $property->featured ? 'text-yellow-500' : 'text-gray-400' }}" />
                                        {{ $property->featured ? 'Featured' : 'Feature' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $property->images_count }} {{ Str::plural('image', $property->images_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost" 
                                        :href="route('properties.show', $property)" 
                                        icon="eye"
                                        target="_blank"
                                    >
                                        View
                                    </flux:button>
                                    <flux:button 
                                        size="sm" 
                                        variant="ghost" 
                                        :href="route('admin.properties.edit', $property)" 
                                        icon="pencil"
                                        wire:navigate
                                    >
                                        Edit
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <flux:icon.building-office class="mx-auto h-12 w-12 mb-4" />
                                        <h3 class="text-sm font-medium mb-2">No properties found</h3>
                                        <p class="text-xs mb-4">Get started by adding your first property listing.</p>
                                        <flux:button variant="primary" :href="route('admin.properties.create')" wire:navigate>
                                            Add Property
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($properties->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $properties->total() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Properties</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $properties->where('featured', true)->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Featured Properties</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $properties->where('status', 'active')->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Active Listings</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-600">{{ $properties->where('status', 'sold')->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Sold Properties</div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFeatured(propertySlug, button) {
            // Show loading state
            button.disabled = true;
            const originalContent = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';

            fetch(`/admin/properties/${propertySlug}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    if (data.featured) {
                        button.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors bg-yellow-100 text-yellow-800 border border-yellow-300 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 dark:border-yellow-700';
                        button.innerHTML = '<svg class="h-3 w-3 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>Featured';
                        button.closest('tr').classList.add('bg-yellow-50', 'dark:bg-yellow-900/20');
                    } else {
                        button.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors bg-gray-100 text-gray-600 border border-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600';
                        button.innerHTML = '<svg class="h-3 w-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>Feature';
                        button.closest('tr').classList.remove('bg-yellow-50', 'dark:bg-yellow-900/20');
                    }

                    // Show success message
                    console.log(data.message);
                } else {
                    alert('Error updating featured status');
                    button.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating featured status');
                button.innerHTML = originalContent;
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    </script>
    @endpush
</x-layouts.app>
