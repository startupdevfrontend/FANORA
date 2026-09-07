@props(['status' => session('status') ?? null, 'errors' => null])
@php($errors ??= $errors->any() ? $errors : null)

@if ($status)
    <x-alert type="success">{{ $status }}</x-alert>
@endif

@if ($errors && $errors->any())
    <x-alert type="error">
        <ul class="list-disc space-y-1">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif