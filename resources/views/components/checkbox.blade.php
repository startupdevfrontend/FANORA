@props(['name', 'label' => null, 'checked' => false, 'required' => false, 'description' => null])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = $errors->has($name))

<div>
    <label for="{{ $id }}" class="flex cursor-pointer items-start gap-3">
        <span class="relative mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center">
            <input
                {{ $attributes->class(['peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-brand-border bg-brand-input transition-all duration-200 checked:border-brand-magenta checked:bg-brand-magenta focus:ring-4 focus:ring-brand-magenta/25 focus:outline-none']) }}
                type="checkbox"
                id="{{ $id }}"
                name="{{ $name }}"
                value="1"
                @if ($checked || old($name)) checked @endif
                @if ($required) required @endif
                @if ($hasError) aria-invalid="true" @endif
            >
            <svg class="pointer-events-none absolute h-3.5 w-3.5 text-white opacity-0 transition-opacity peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </span>
        @if ($label || $description)
            <span class="text-sm">
                @if ($label)
                    <span class="font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</span>
                @endif
                @if ($description)
                    <span class="block text-xs text-brand-muted">{{ $description }}</span>
                @endif
            </span>
        @endif
    </label>

    @error($name)
        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
    @enderror
</div>