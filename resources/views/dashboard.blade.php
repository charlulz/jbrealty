<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Property Statistics -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <flux:icon.building-office class="w-6 h-6 text-blue-600 dark:text-blue-300" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Properties</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\Property::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <flux:icon.check-circle class="w-6 h-6 text-green-600 dark:text-green-300" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Listings</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\Property::active()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <flux:icon.chat-bubble-bottom-center-text class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Inquiries</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\PropertyInquiry::where('status', 'new')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <flux:icon.star class="w-6 h-6 text-yellow-600 dark:text-yellow-300" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Featured Properties</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\Property::featured()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid gap-4 md:grid-cols-2">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <flux:button variant="outline" :href="route('properties.index')" wire:navigate class="w-full justify-start">
                        <flux:icon.building-office class="w-4 h-4 mr-2" />
                        Browse All Properties
                    </flux:button>
                    <flux:button variant="outline" :href="route('admin.properties.create')" wire:navigate class="w-full justify-start">
                        <flux:icon.squares-plus class="w-4 h-4 mr-2" />
                        Add New Property
                    </flux:button>
                    <flux:button variant="outline" href="#" class="w-full justify-start">
                        <flux:icon.chat-bubble-bottom-center-text class="w-4 h-4 mr-2" />
                        View Inquiries
                    </flux:button>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Property Types</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Hunting Land</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ App\Models\Property::byType('hunting')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Farms</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ App\Models\Property::byType('farms')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Waterfront</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ App\Models\Property::byType('waterfront')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ranches</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ App\Models\Property::byType('ranches')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Properties</h3>
            <div class="space-y-3">
                @forelse(App\Models\Property::latest()->limit(5)->get() as $property)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $property->title }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $property->location_display }} • {{ $property->total_acres }} acres</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $property->formatted_price }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $property->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No properties found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
