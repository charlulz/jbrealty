@props(['property'])

<div class="w-full max-w-full overflow-hidden rounded-3xl border border-white/10 bg-black/20 backdrop-blur-2xl property-gallery-container" 
     x-data="propertyGallery({{ $property->images->count() }})"
     x-init="init()"
>
     
    @if($property->images->count() > 0)
        <!-- Main Gallery Container -->
        <div class="relative w-full max-w-full">
            <!-- Hero Image -->
            <div class="relative w-full h-64 sm:h-80 lg:h-96 bg-black overflow-hidden">
                <template x-for="(image, index) in images" :key="image.id">
                    <img 
                        :src="image.url" 
                        :alt="image.alt_text || '{{ $property->title }} - Image ' + (index + 1)"
                        class="absolute inset-0 w-full h-full object-cover transition-all duration-500"
                        :class="{ 'opacity-100 scale-100': currentIndex === index, 'opacity-0 scale-105': currentIndex !== index }"
                        x-show="currentIndex === index"
                        loading="lazy"
                        @click="openModal()"
                        @touchstart="handleTouchStart($event)"
                        @touchend="handleTouchEnd($event)"
                    >
                </template>
                
                <!-- Gradient Overlays for Better Button Visibility -->
                <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-black/50 to-transparent pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-black/50 to-transparent pointer-events-none"></div>
                
                <!-- Navigation Arrows - Larger for Mobile -->
                <button 
                    @click="previousImage()" 
                    class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black w-10 h-10 sm:w-12 sm:h-12 rounded-full transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
                    x-show="images.length > 1"
                    type="button"
                    aria-label="Previous image"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button 
                    @click="nextImage()" 
                    class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-black/60 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black w-10 h-10 sm:w-12 sm:h-12 rounded-full transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
                    x-show="images.length > 1"
                    type="button"
                    aria-label="Next image"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <!-- Image Counter -->
                <div class="absolute bottom-3 right-3 sm:bottom-4 sm:right-4 bg-black/70 text-white px-3 py-1.5 rounded-2xl text-sm backdrop-blur-sm border border-white/10">
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </div>
                
                <!-- Expand Button -->
                <button 
                    @click="openModal()"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 bg-black/60 hover:bg-secondary border border-white/20 hover:border-secondary text-white hover:text-black w-10 h-10 sm:w-12 sm:h-12 rounded-full transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
                    type="button"
                    title="View all images"
                    aria-label="View all images in fullscreen"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile-Optimized Thumbnail Strip -->
            <div class="w-full p-3 sm:p-4 bg-black/40">
                <!-- Thumbnail Navigation -->
                <div class="w-full flex items-center gap-2 min-w-0">
                    <!-- Scroll Left Button -->
                    <button 
                        @click="scrollThumbnails('left')"
                        class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-black/60 border border-white/10 hover:bg-secondary hover:border-secondary hover:text-black text-white transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
                        x-show="canScrollLeft"
                        type="button"
                        aria-label="Scroll thumbnails left"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Scrollable Thumbnail Container -->
                    <div 
                        class="flex-1 min-w-0 overflow-x-auto scrollbar-hide thumbnail-container"
                        x-ref="thumbnailContainer"
                        @scroll="updateScrollButtons()"
                    >
                        <div class="flex gap-2 sm:gap-3 pb-2 min-w-max thumbnail-strip">
                            <template x-for="(image, index) in images" :key="image.id">
                                <button
                                    @click="setCurrentImage(index)"
                                    class="flex-shrink-0 w-16 h-12 sm:w-20 sm:h-16 rounded-2xl overflow-hidden border-2 transition-all duration-300"
                                    :class="{ 
                                        'border-secondary ring-2 ring-secondary/30': currentIndex === index,
                                        'border-white/20 hover:border-secondary/50': currentIndex !== index 
                                    }"
                                    type="button"
                                    :aria-label="'View image ' + (index + 1)"
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
                        class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-black/60 border border-white/10 hover:bg-secondary hover:border-secondary hover:text-black text-white transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
                        x-show="canScrollRight"
                        type="button"
                        aria-label="Scroll thumbnails right"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- View All Images Button -->
                <div class="mt-4 text-center">
                    <button 
                        @click="openModal()"
                        class="inline-flex items-center px-6 py-3 bg-secondary hover:bg-secondary/90 text-black text-sm font-medium rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-secondary/30"
                        type="button"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        View All <span x-text="images.length"></span> Images
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile-Optimized Modal Overlay -->
        <div 
            x-show="modalOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/95 backdrop-blur-sm"
            @click="closeModal()"
            @keydown.escape.window="closeModal()"
            @touchstart="handleModalTouchStart($event)"
            @touchend="handleModalTouchEnd($event)"
        >
            <!-- Modal Content -->
            <div class="flex h-full w-full items-center justify-center p-3 sm:p-4" @click.stop>
                <!-- Close Button -->
                <button 
                    @click="closeModal()"
                    class="absolute top-4 right-4 z-10 bg-black/60 hover:bg-secondary text-white hover:text-black w-12 h-12 rounded-full transition-all duration-300 backdrop-blur-sm border border-white/20 flex items-center justify-center"
                    type="button"
                    aria-label="Close gallery"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Navigation Arrows -->
                <button 
                    @click="previousImage()" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/60 hover:bg-secondary text-white hover:text-black w-12 h-12 sm:w-14 sm:h-14 rounded-full transition-all duration-300 backdrop-blur-sm border border-white/20 flex items-center justify-center"
                    x-show="images.length > 1"
                    type="button"
                    aria-label="Previous image"
                >
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button 
                    @click="nextImage()" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/60 hover:bg-secondary text-white hover:text-black w-12 h-12 sm:w-14 sm:h-14 rounded-full transition-all duration-300 backdrop-blur-sm border border-white/20 flex items-center justify-center"
                    x-show="images.length > 1"
                    type="button"
                    aria-label="Next image"
                >
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <!-- Main Image -->
                <div class="relative max-w-full max-h-full mx-auto">
                    <img 
                        :src="images[currentIndex]?.url" 
                        :alt="images[currentIndex]?.alt_text || '{{ $property->title }} - Image ' + (currentIndex + 1)"
                        class="max-w-full max-h-[calc(100vh-8rem)] object-contain rounded-2xl"
                        @touchstart="handleModalTouchStart($event)"
                        @touchend="handleModalTouchEnd($event)"
                    >
                    
                    <!-- Image Info -->
                    <div class="absolute bottom-4 left-4 right-4 bg-black/80 text-white p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-sm sm:text-base" x-text="images[currentIndex]?.title || '{{ $property->title }}'"></h3>
                                <p class="text-xs sm:text-sm text-white/80" x-text="images[currentIndex]?.alt_text || 'Property Image'"></p>
                            </div>
                            <div class="text-sm bg-secondary/20 px-3 py-1 rounded-full">
                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Thumbnail Strip in Modal - Hidden on small mobile -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 max-w-4xl w-full px-4 hidden sm:block">
                    <div class="bg-black/80 rounded-2xl p-3 backdrop-blur-sm border border-white/10">
                        <div class="flex gap-2 overflow-x-auto scrollbar-hide">
                            <template x-for="(image, index) in images" :key="image.id">
                                <button
                                    @click="setCurrentImage(index)"
                                    class="flex-shrink-0 w-14 h-10 rounded-lg overflow-hidden border-2 transition-all duration-300"
                                    :class="{ 
                                        'border-secondary': currentIndex === index,
                                        'border-white/40 hover:border-white/70': currentIndex !== index 
                                    }"
                                    type="button"
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
        <div class="flex items-center justify-center h-64 sm:h-80 bg-black/20 rounded-3xl border border-white/10">
            <div class="text-center">
                <svg class="w-16 h-16 text-secondary/60 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-white/60">No images available for this property</p>
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
        touchStartX: 0,
        touchEndX: 0,
        modalTouchStartX: 0,
        modalTouchEndX: 0,
        images: {!! json_encode($property->images->map(fn($image) => [
            'id' => $image->id,
            'url' => $image->url,
            'alt_text' => $image->alt_text,
            'title' => $image->title,
        ])->toArray()) !!},
        
        init() {
            this.updateScrollButtons();
            this.preloadImages();
            
            // Add keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (this.modalOpen) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        this.previousImage();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        this.nextImage();
                    }
                }
            });
        },
        
        preloadImages() {
            // Preload first 5 images
            this.images.slice(0, 5).forEach(image => {
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
        
        // Touch handling for gallery swipes
        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        },
        
        handleTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].clientX;
            this.handleSwipe();
        },
        
        // Touch handling for modal swipes
        handleModalTouchStart(e) {
            this.modalTouchStartX = e.touches[0].clientX;
        },
        
        handleModalTouchEnd(e) {
            this.modalTouchEndX = e.changedTouches[0].clientX;
            this.handleModalSwipe();
        },
        
        handleSwipe() {
            const swipeThreshold = 50;
            const swipeLength = this.touchEndX - this.touchStartX;
            
            if (Math.abs(swipeLength) > swipeThreshold) {
                if (swipeLength > 0) {
                    this.previousImage();
                } else {
                    this.nextImage();
                }
            }
        },
        
        handleModalSwipe() {
            const swipeThreshold = 50;
            const swipeLength = this.modalTouchEndX - this.modalTouchStartX;
            
            if (Math.abs(swipeLength) > swipeThreshold) {
                if (swipeLength > 0) {
                    this.previousImage();
                } else {
                    this.nextImage();
                }
            }
        },
        
        scrollThumbnails(direction) {
            const container = this.$refs.thumbnailContainer;
            const scrollAmount = 150;
            
            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
            
            setTimeout(() => this.updateScrollButtons(), 100);
        },
        
        scrollToThumbnail(index) {
            const container = this.$refs.thumbnailContainer;
            if (!container || !container.children[0]) return;
            
            const thumbnail = container.children[0].children[index];
            
            if (thumbnail) {
                const containerWidth = container.clientWidth;
                const thumbnailLeft = thumbnail.offsetLeft;
                const thumbnailWidth = thumbnail.clientWidth;
                const currentScroll = container.scrollLeft;
                
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
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Custom scroll behavior for smooth mobile experience */
.scrollbar-hide {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Mobile gallery overflow prevention */
@media (max-width: 640px) {
    .property-gallery-container {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    
    .property-gallery-container * {
        box-sizing: border-box;
    }
    
    /* Ensure flex containers don't exceed parent width */
    .thumbnail-container {
        width: 100%;
        max-width: 100%;
    }
    
    /* Prevent thumbnail flex overflow */
    .thumbnail-strip {
        width: max-content;
        max-width: none;
    }
}
</style>