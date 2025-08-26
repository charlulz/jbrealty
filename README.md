# JB Land & Home Realty - Real Estate Mockup

A modern real estate website mockup built using the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire).

## TALL Stack Implementation

This project demonstrates best practices using:

### 🎨 **Tailwind CSS**
- Custom brand colors: Primary (#231e20) and Secondary (#fcce00)
- Responsive design with mobile-first approach
- Custom utility classes for brand consistency
- Professional real estate styling inspired by Mossy Oak Properties

### ⚡ **Alpine.js**
- Interactive navigation with smooth dropdown menus
- Mobile menu toggle with animations
- Animated statistics counters with intersection observer
- Clean, declarative JavaScript without jQuery

### 🚀 **Laravel**
- Modern Laravel 11+ structure
- Custom Blade components for reusable UI elements
- Proper routing and authentication integration
- Clean MVC architecture

### 🔄 **Livewire**
- `PropertySearch` component with reactive property search
- Real-time form validation and error handling
- Advanced search filters with proper state management
- Smooth AJAX interactions without custom JavaScript

## Features

### Navigation
- Responsive header with brand logo
- Dropdown property type navigation
- Mobile-friendly hamburger menu
- Authentication integration (Login/Dashboard links)

### Hero Section
- Professional hero design with brand colors
- Property search form powered by Livewire
- Advanced search filters (features, improvements, recreation)
- Animated statistics counters

### Property Search
- Real-time search with Livewire
- Form validation and error handling
- Loading states and user feedback
- Simulated search results display
- Advanced filter options

### Design Elements
- Professional real estate color scheme
- Mobile-responsive layout
- Smooth transitions and animations
- Accessibility-friendly components

## Brand Colors

- **Primary**: `#231e20` (Dark charcoal)
- **Secondary**: `#fcce00` (Golden yellow)

## Components Structure

```
resources/views/
├── components/
│   ├── nav-header.blade.php       # Main navigation
│   └── hero-section.blade.php     # Hero with Livewire search
├── livewire/
│   └── property-search.blade.php  # Livewire search component
└── welcome.blade.php              # Main landing page
```

## Livewire Components

### PropertySearch
- **Location**: `app/Livewire/PropertySearch.php`
- **Features**: 
  - Property type, location, price, and acreage filtering
  - Advanced search with checkboxes for features
  - Real-time validation
  - Search results display
  - Reset functionality

## Development Notes

This is a mockup/demo showing:
- Proper TALL stack architecture
- Modern Laravel best practices
- Component-based UI design
- Interactive search functionality
- Professional real estate website patterns

The search functionality currently returns simulated results but is structured to easily connect to a real property database.

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install && npm run build`
4. Copy `.env.example` to `.env` and configure
5. Run `php artisan key:generate`
6. Start the development server with `php artisan serve`

## TALL Stack Benefits Demonstrated

- **Server-side rendering** with client-side interactivity
- **Component reusability** with Blade and Livewire
- **Real-time updates** without complex JavaScript
- **Progressive enhancement** with Alpine.js
- **Maintainable codebase** following Laravel conventions
