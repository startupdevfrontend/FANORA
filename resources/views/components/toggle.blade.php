@props(['label' => null, 'checked' => false, 'name' => null, 'required' => false])

@php($id = ($name ?? 'toggle').'_'.\Illuminate\Support\Str::random(6))

<label for="{{ $id }}" class="inline-flex cursor-pointer items-center gap-3">
    <span class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full bg-brand-border transition-colors duration-200 peer-focus:ring-4 peer-focus:ring-brand-magenta/25 peer-checked:bg-brand-magenta {{ $attributes->get('class') }}">
        <input
            type="checkbox"
            id="{{ $id }}"
            value="1"
            class="peer sr-only"
            @if ($name) name="{{ $name }}" @endif
            @if ($checked || old((string) $name)) checked @endif
            @if ($required) required @endif
        >
        <span class="h-4 w-4 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-5"></span>
    </span>
    @if ($label)
        <span class="text-sm font-medium text-white">{{ $label }}</span>
    @endif
</label>