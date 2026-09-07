@props(['title' => null])

<h1 class="text-2xl font-bold tracking-tight text-white">{{ $title ?? $slot }}</h1>