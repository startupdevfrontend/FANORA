@props(['path' => null, 'src' => null, 'name' => '', 'size' => 'md', 'rounded' => true])

@php
    $sizeClass = match ($size) {
        'xs' => 'h-8 w-8 text-xs',
        'sm' => 'h-10 w-10 text-sm',
        'md' => 'h-12 w-12 text-base',
        'lg' => 'h-20 w-20 text-2xl',
        'xl' => 'h-32 w-32 text-4xl',
        default => 'h-12 w-12 text-base',
    };

    $initials = '';

    foreach (preg_split('/\s+/', trim($name)) as $part) {
        if (filled($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        if (strlen($initials) >= 2) {
            break;
        }
    }
@endphp

@if ($src && $path === null)
    <img src="{{ $src }}" alt="{{ $name }}" loading="lazy" class="{{ $rounded ? 'rounded-full' : 'rounded-xl' }} object-cover {{ $sizeClass }}">
@elseif ($path && exists(app(\App\Services\MediaService::class)->path($path)))
    <img src="{{ app(\App\Services\MediaService::class)->avatarUrl($path) }}" alt="{{ $name }}" loading="lazy" class="{{ $rounded ? 'rounded-full' : 'rounded-xl' }} object-cover {{ $sizeClass }}">
@else
    <span class="grid {{ $rounded ? 'rounded-full' : 'rounded-xl' }} place-items-center bg-gradient-brand font-bold text-white {{ $sizeClass }}">{{ strlen($initials) > 0 ? $initials : 'F' }}</span>
@endif