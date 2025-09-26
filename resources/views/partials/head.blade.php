<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SEO Meta Tags -->
<title>{{ e($title ?? config('app.name')) }}</title>
<meta name="description" content="{{ e($description ?? 'Premium land and rural properties in Kentucky. Discover exceptional hunting land, farms, ranches, and recreational properties with Jeremiah Brown at JB Land & Home Realty.') }}">
<meta name="keywords" content="{{ e($keywords ?? 'Kentucky land for sale, hunting land, farms, ranches, rural properties, real estate, Jeremiah Brown, JB Land Home Realty') }}">
<meta name="author" content="JB Land & Home Realty">
<meta name="robots" content="index, follow">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonical ?? request()->url() }}">

<!-- Open Graph Tags for Facebook, LinkedIn, etc. -->
<meta property="og:site_name" content="JB Land & Home Realty">
<meta property="og:title" content="{{ e($ogTitle ?? $title ?? config('app.name')) }}">
<meta property="og:description" content="{{ e($ogDescription ?? $description ?? 'Premium land and rural properties in Kentucky. Discover exceptional hunting land, farms, ranches, and recreational properties with expert guidance.') }}">
<meta property="og:type" content="{{ e($ogType ?? 'website') }}">
<meta property="og:url" content="{{ e($ogUrl ?? request()->url()) }}">
<meta property="og:image" content="{{ e($ogImage ?? asset('images/logo.png')) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ e($ogImageAlt ?? 'JB Land & Home Realty - Premium Kentucky Land Properties') }}">
<meta property="og:locale" content="en_US">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@jblandandhome">
<meta name="twitter:creator" content="@jblandandhome">
<meta name="twitter:title" content="{{ e($twitterTitle ?? $ogTitle ?? $title ?? config('app.name')) }}">
<meta name="twitter:description" content="{{ e($twitterDescription ?? $ogDescription ?? $description ?? 'Premium land and rural properties in Kentucky with expert guidance from Jeremiah Brown.') }}">
<meta name="twitter:image" content="{{ e($twitterImage ?? $ogImage ?? asset('images/logo.png')) }}">
<meta name="twitter:image:alt" content="{{ e($twitterImageAlt ?? $ogImageAlt ?? 'JB Land & Home Realty Logo') }}">

<!-- Additional SEO -->
<meta name="theme-color" content="#FCCE00">
<meta name="msapplication-TileColor" content="#FCCE00">

<link rel="icon" href="{{ asset('images/logo.png') }}" sizes="any">
<link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
