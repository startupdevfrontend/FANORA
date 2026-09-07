@props(['name', 'label' => null, 'rows' => 4, 'value' => null, 'placeholder' => null, 'required' => false, 'hint' => null, 'maxlength' => null])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = old('errors.'.$name) !== null)

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @if ($hint) aria-describedby="hint_{{ $id }}" @endif
        class="input-base {{ $hasError ? 'input-error' : '' }}"
    >{{ $value ?? old($name) }}</textarea>

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-xs text-red-400">{{ old('errors.' . $name) }}</p>
    @endif
</div>