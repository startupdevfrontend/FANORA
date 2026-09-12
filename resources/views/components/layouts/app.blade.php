@props([
    'title' => 'FANORA',
    'description' => 'Seu conteúdo. Seu público. Seu espaço. Plataforma de creators e conteúdo exclusivo.',
    'canonical' => null,
    'ogType' => 'website',
    'robots' => null,
    'wide' => false,
    'flush' => false,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — FANORA</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <link rel="icon" href="{{ asset('icons/icon-32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    @if ($robots)
        <meta name="robots" content="{{ $robots }}">
    @endif

    <!-- Open Graph -->
    <meta property="og:site_name" content="FANORA">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ asset('icons/icon-512.png') }}">

    <!-- Twitter / X cards -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ asset('icons/icon-512.png') }}">

    <meta name="theme-color" content="#080808">

    @isset($seo)
        {{ $seo }}
    @endisset

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-black text-white">
    <x-navbar />
    <x-flash />

    <main class="{{ $flush ? '' : 'pb-24' }} min-h-[75vh] w-full px-4 py-10 sm:px-6 lg:px-8 xl:px-10 2xl:px-12">
        {{ $slot }}
    </main>

    <x-footer />

    @if (config('fanora.pwa_enabled'))
        <x-pwa-register />
    @endif
</body>
</html>