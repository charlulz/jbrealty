<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    // Get first 3 available properties that have images for featured section
    $featuredProperties = \App\Models\Property::published()
        ->available() // Only show active and pending properties, not sold ones
        ->has('images')
        ->with(['images' => function ($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }])
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    return view('welcome', compact('featuredProperties'));
})->name('home');

Route::get('/agents', function () {
    return view('agents');
})->name('agents');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Legal pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/cookie-policy', function () {
    return view('legal.cookie-policy');
})->name('cookie-policy');

Route::get('/owner-financing', function () {
    return view('owner-financing');
})->name('owner-financing');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Property routes
Route::get('/properties', [App\Http\Controllers\PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [App\Http\Controllers\PropertyController::class, 'show'])->name('properties.show');

// Property inquiry routes
Route::post('/properties/{property}/inquiry', [App\Http\Controllers\PropertyInquiryController::class, 'store'])->name('property-inquiry.store');

// Protected property management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/properties/create', [App\Http\Controllers\PropertyController::class, 'create'])->name('admin.properties.create');
    Route::post('/admin/properties', [App\Http\Controllers\PropertyController::class, 'store'])->name('admin.properties.store');
    Route::get('/admin/properties/{property}/edit', [App\Http\Controllers\PropertyController::class, 'edit'])->name('admin.properties.edit');
    Route::put('/admin/properties/{property}', [App\Http\Controllers\PropertyController::class, 'update'])->name('admin.properties.update');
    Route::delete('/admin/properties/{property}', [App\Http\Controllers\PropertyController::class, 'destroy'])->name('admin.properties.destroy');
    
    // Import routes
    Route::get('/admin/import', [App\Http\Controllers\Admin\ImportController::class, 'index'])->name('admin.import.index');
    Route::post('/admin/import/csv', [App\Http\Controllers\Admin\ImportController::class, 'uploadCsv'])->name('admin.import.csv');
    Route::post('/admin/import/preview', [App\Http\Controllers\Admin\ImportController::class, 'previewCsv'])->name('admin.import.preview');
    Route::post('/admin/import/scrape', [App\Http\Controllers\Admin\ImportController::class, 'scrapeUrl'])->name('admin.import.scrape');
    Route::get('/admin/import/template/{type}', [App\Http\Controllers\Admin\ImportController::class, 'downloadTemplate'])->name('admin.import.template');
});

require __DIR__.'/auth.php';
