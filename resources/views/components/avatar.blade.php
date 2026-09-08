@props(['path' => null, 'src' => null, 'name' => '', 'size' => 'md', 'rounded' => true, 'glow' => false])

@php
    $sizeClass = match ($size) {
        'xs' => 'h-8 w-8 text-xs',
        'sm' => 'h-10 w-10 text-sm',
        'md' => 'h-12 w-12 text-base',
        'lg' => 'h-20 w-20 text-2xl',
        'xl' => 'h-32 w-32 text-4xl',
        default => 'h-12 w-12 text-base',
    };

    $glowRingClass = match ($size) {
        'xs' => 'ring-2 ring-offset-2 ring-offset-brand-bg',
        'sm' => 'ring-2 ring-offset-2 ring-offset-brand-bg',
        'md' => 'ring-[3px] ring-offset-[3px] ring-offset-brand-bg',
        'lg' => 'ring-4 ring-offset-4 ring-offset-brand-bg',
        'xl' => 'ring-[6px] ring-offset-[6px] ring-offset-brand-bg',
        default => 'ring-[3px] ring-offset-[3px] ring-offset-brand-bg',
    };

    $glowClass = $glow
        ? ' avatar-glow relative ' . $glowRingClass . ' ring-transparent [--tw-ring-color:theme(colors.brand.magenta)]'
        : '';

    $glowShadowClass = $glow
        ? match ($size) {
            'xs', 'sm' => 'shadow-md shadow-brand-magenta/30',
            'md' => 'shadow-lg shadow-brand-magenta/40',
            'lg' => 'shadow-xl shadow-brand-magenta/40',
            'xl' => 'shadow-2xl shadow-brand-magenta/50',
            default => 'shadow-lg shadow-brand-magenta/40',
        }
        : '';

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

@if ($glow)
    <span class="relative inline-block {{ $glowShadowClass }} {{ $rounded ? 'rounded-full' : 'rounded-xl' }} p-[2px] bg-gradient-to-br from-brand-magenta via-fuchsia-500 to-brand-purple">
@endif

@if ($src && $path === null)
    <img src="{{ $src }}" alt="{{ $name }}" loading="lazy" class="{{ $rounded ? 'rounded-full' : 'rounded-xl' }} object-cover {{ $sizeClass }} {{ $glow ? '' : '' }}">
@elseif ($path && exists(app(\App\Services\MediaService::class)->path($path)))
    <img src="{{ app(\App\Services\MediaService::class)->avatarUrl($path) }}" alt="{{ $name }}" loading="lazy" class="{{ $rounded ? 'rounded-full' : 'rounded-xl' }} object-cover {{ $sizeClass }}">
@else
    <span class="grid {{ $rounded ? 'rounded-full' : 'rounded-xl' }} place-items-center bg-gradient-brand font-bold text-white {{ $sizeClass }}">{{ strlen($initials) > 0 ? $initials : 'F' }}</span>
@endif

@if ($glow)
    </span>
@endif