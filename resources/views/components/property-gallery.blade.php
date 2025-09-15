@props(['property'])

<div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700" 
     x-data="propertyGallery({{ $property->images->count() }})">
     
    @if($property->images->count() > 0)
        <!-- Main Gallery Container -->
        <div class="relative">
            <!-- Hero Image -->
            <div class="relative h-96 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                <template x-for="(image, index) in images" :key="image.id">
                    <img 
                        :src="image.url" 
                        :alt="image.alt_text || '{{ $property->title }} - Image ' + (index + 1)"
                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300"
                        :class="{ 'opacity-100': currentIndex === index, 'opacity-0': currentIndex !== index }"
                        x-show="currentIndex === index"
                        loading="lazy"
                        @click="openModal()"
                    >
                </template>
                
                <!-- Navigation Arrows -->
                <button 
                    @click="previousImage()" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                    x-show="images.length > 1"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button 
                    @click="nextImage()" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                    x-show="images.length > 1"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <!-- Image Counter -->
                <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded-lg text-sm">
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </div>
                
                <!-- Expand Button -->
                <button 
                    @click="openModal()"
                    class="absolute top-4 right-4 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                    title="View all images"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </button>
            </div>
            
            <!-- Thumbnail Strip -->
            <div class="p-4 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <!-- Scroll Left Button -->
                    <button 
                        @click="scrollThumbnails('left')"
                        class="flex-shrink-0 p-2 rounded-lg bg-white dark:bg-gray-700 shadow-sm hover:shadow-md transition-shadow"
                        x-show="canScrollLeft"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Scrollable Thumbnail Container -->
                    <div 
                        class="flex-1 overflow-x-auto scrollbar-hide"
                        x-ref="thumbnailContainer"
                        @scroll="updateScrollButtons()"
                    >
                        <div class="flex gap-2 pb-2">
                            <template x-for="(image, index) in images" :key="image.id">
                                <button
                                    @click="setCurrentImage(index)"
                                    class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 transition-all"
                                    :class="{ 
                                        'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800': currentIndex === index,
                                        'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500': currentIndex !== index 
                                    }"
                                >
                                    <img 
                                        :src="image.url" 
                                        :alt="'Thumbnail ' + (index + 1)"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                </button>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Scroll Right Button -->
                    <button 
                        @click="scrollThumbnails('right')"
                        class="flex-shrink-0 p-2 rounded-lg bg-white dark:bg-gray-700 shadow-sm hover:shadow-md transition-shadow"
                        x-show="canScrollRight"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- View All Images Button -->
                <div class="mt-3 text-center">
                    <button 
                        @click="openModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        View All <span x-text="images.length"></span> Images
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Modal Overlay -->
        <div 
            x-show="modalOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm"
            @click="closeModal()"
            @keydown.escape.window="closeModal()"
        >
            <!-- Modal Content -->
            <div class="flex h-full w-full items-center justify-center p-4" @click.stop>
                <!-- Close Button -->
                <button 
                    @click="closeModal()"
                    class="absolute top-4 right-4 z-10 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Navigation Arrows -->
                <button 
                    @click="previousImage()" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors"
                    x-show="images.length > 1"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button 
                    @click="nextImage()" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors"
                    x-show="images.length > 1"
                >
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <!-- Main Image -->
                <div class="relative max-w-7xl max-h-full mx-auto">
                    <img 
                        :src="images[currentIndex]?.url" 
                        :alt="images[currentIndex]?.alt_text || '{{ $property->title }} - Image ' + (currentIndex + 1)"
                        class="max-w-full max-h-full object-contain rounded-lg"
                    >
                    
                    <!-- Image Info -->
                    <div class="absolute bottom-4 left-4 right-4 bg-black/70 text-white p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold" x-text="images[currentIndex]?.title || '{{ $property->title }}'"></h3>
                                <p class="text-sm text-gray-300" x-text="images[currentIndex]?.alt_text || 'Property Image'"></p>
                            </div>
                            <div class="text-sm">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Thumbnail Strip in Modal -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 max-w-4xl w-full px-4">
                    <div class="bg-black/70 rounded-lg p-3">
                        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
                            <template x-for="(image, index) in images" :key="image.id">
                                <button
                                    @click="setCurrentImage(index)"
                                    class="flex-shrink-0 w-16 h-12 rounded overflow-hidden border-2 transition-all"
                                    :class="{ 
                                        'border-blue-400': currentIndex === index,
                                        'border-gray-400 hover:border-gray-300': currentIndex !== index 
                                    }"
                                >
                                    <img 
                                        :src="image.url" 
                                        :alt="'Modal thumbnail ' + (index + 1)"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    @else
        <!-- No Images Placeholder -->
        <div class="flex items-center justify-center h-96 bg-gray-100 dark:bg-gray-800">
            <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No images available for this property</p>
            </div>
        </div>
    @endif
</div>

<script>
function propertyGallery(imageCount) {
    return {
        currentIndex: 0,
        modalOpen: false,
        canScrollLeft: false,
        canScrollRight: false,
        images: {!! json_encode($property->images->map(fn($image) => [
            'id' => $image->id,
            'url' => $image->url,
            'alt_text' => $image->alt_text,
            'title' => $image->title,
        ])->toArray()) !!},
        
        init() {
            this.updateScrollButtons();
            // Preload a few images for better performance
            this.preloadImages();
        },
        
        preloadImages() {
            // Preload first 10 images
            this.images.slice(0, 10).forEach(image => {
                const img = new Image();
                img.src = image.url;
            });
        },
        
        setCurrentImage(index) {
            this.currentIndex = index;
            this.scrollToThumbnail(index);
        },
        
        nextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            this.scrollToThumbnail(this.currentIndex);
        },
        
        previousImage() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            this.scrollToThumbnail(this.currentIndex);
        },
        
        openModal() {
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = '';
        },
        
        scrollThumbnails(direction) {
            const container = this.$refs.thumbnailContainer;
            const scrollAmount = 200;
            
            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
            
            setTimeout(() => this.updateScrollButtons(), 100);
        },
        
        scrollToThumbnail(index) {
            const container = this.$refs.thumbnailContainer;
            const thumbnail = container.children[0].children[index];
            
            if (thumbnail && container) {
                const containerWidth = container.clientWidth;
                const thumbnailLeft = thumbnail.offsetLeft;
                const thumbnailWidth = thumbnail.clientWidth;
                const currentScroll = container.scrollLeft;
                
                // Check if thumbnail is not fully visible
                if (thumbnailLeft < currentScroll || thumbnailLeft + thumbnailWidth > currentScroll + containerWidth) {
                    container.scrollTo({
                        left: thumbnailLeft - (containerWidth / 2) + (thumbnailWidth / 2),
                        behavior: 'smooth'
                    });
                }
            }
        },
        
        updateScrollButtons() {
            const container = this.$refs.thumbnailContainer;
            if (container) {
                this.canScrollLeft = container.scrollLeft > 0;
                this.canScrollRight = container.scrollLeft < container.scrollWidth - container.clientWidth;
            }
        }
    }
}
</script>

<style>
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}
</style>
