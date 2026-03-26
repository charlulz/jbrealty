{{--
    Source MLS listing verification badge (licensee integration).
    Display requirements: https://sourcemls.org/developers
--}}
@props([
    'url',
])

@php
    $raw = trim((string) $url);
    $valid = $raw !== '' && str_starts_with($raw, 'https://sourcemls.org/');
    $imageSrc = '';
    if ($valid) {
        $imageSrc = str_ends_with(strtolower($raw), '.png') ? $raw : $raw . '.png';
    }
@endphp

@if($valid)
    <div class="source-mls-badge flex justify-center sm:justify-start mb-6">
        <img
            src="{{ $imageSrc }}"
            width="66"
            height="30"
            alt="Source MLS Verified"
            loading="lazy"
            decoding="async"
            class="inline-block rounded-md"
            style="background: white;"
            onload="navigator.sendBeacon({{ \Illuminate\Support\Js::from($raw) }})"
            onerror="this.style.display='none'"
        />
    </div>
@endif
