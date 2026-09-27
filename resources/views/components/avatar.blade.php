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

    $glowClass = $glow
        ? ' avatar-glow'
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

    $avatarSrc = $src ?? app(\App\Services\MediaService::class)->avatarUrl($path);
@endphp

@if ($glow)
    <span class="relative inline-block rounded-full p-[2px] bg-gradient-to-br from-brand-magenta via-fuchsia-500 to-brand-purple {{ $sizeClass === 'h-8 w-8 text-xs' ? 'p-[1.5px]' : '' }}">
@endif

@if ($avatarSrc)
    <img {{ $attributes->class(['object-cover', $sizeClass, $glowClass, $rounded ? 'rounded-full' : 'rounded-xl']) }} src="{{ $avatarSrc }}" alt="{{ $name }}" loading="lazy">
@else
    <span {{ $attributes->class(['grid', 'place-items-center bg-gradient-brand font-bold text-white', $sizeClass, $glowClass, $rounded ? 'rounded-full' : 'rounded-xl']) }}>{{ strlen($initials) > 0 ? $initials : 'F' }}</span>
@endif

@if ($glow)
    </span>
@endif