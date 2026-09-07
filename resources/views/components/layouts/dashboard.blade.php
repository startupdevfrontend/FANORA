@props([
    'title' => 'FANORA',
    'description' => '',
    'admin' => false,
    'active' => null,
    'paddingless' => false,
])
<x-layouts.app :title="$title" :description="$description" :robots="'noindex, nofollow'" :wide="true" :flush="$paddingless">
    <div class="flex gap-8 lg:gap-10">
        @if ($admin)
            <x-admin-sidebar :active="$active" />
        @else
            <x-creator-sidebar :active="$active" />
        @endif

        <main class="w-full min-w-0 {{ $paddingless ? '' : 'pt-4' }}">
            <div @class(['px-4' => ! $paddingless, 'pt-2' => ! $paddingless])>
                {{ $slot }}
            </div>
        </main>
    </div>
</x-layouts.app>