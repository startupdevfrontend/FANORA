@props(['name', 'label' => null, 'rows' => 4, 'value' => null, 'placeholder' => null, 'required' => false, 'hint' => null, 'maxlength' => null, 'disabled' => false])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = $errors->has($name))

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <textarea {{ $attributes->class(['input-base', 'input-error' => $hasError]) }}
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @if ($hint) aria-describedby="hint_{{ $id }}" @endif
        @if ($hasError) aria-invalid="true" @endif
    >{{ old($name, $value) }}</textarea>

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
    @enderror
</div>