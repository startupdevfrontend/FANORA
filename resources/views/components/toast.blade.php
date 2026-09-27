<div
    x-data="{
        toasts: [],
        show(message, type = 'success', title = null) {
            const colors = {
                success: { border: 'border-emerald-500/40', icon: 'text-emerald-400', svg: 'M5 13l4 4L19 7' },
                error: { border: 'border-red-500/40', icon: 'text-red-400', svg: 'M6 18L18 6M6 6l12 12' },
                info: { border: 'border-sky-500/40', icon: 'text-sky-400', svg: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            };
            const c = colors[type] || colors.success;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, title, ...c });
            setTimeout(() => this.dismiss(id), 5000);
        },
        dismiss(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
    }"
    x-init="window.toast = { success: (m) => show(m, 'success'), error: (m) => show(m, 'error'), info: (m) => show(m, 'info') }"
    class="pointer-events-none fixed inset-x-0 bottom-6 z-[100] flex flex-col items-center gap-2 px-4"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-brand-border bg-brand-card/95 p-4 shadow-2xl backdrop-blur"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-4"
            role="status"
        >
            <svg class="h-5 w-5 shrink-0" :class="t.icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="t.svg" />
            </svg>
            <div class="min-w-0 flex-1">
                <template x-if="t.title">
                    <p class="text-sm font-semibold text-white" x-text="t.title"></p>
                </template>
                <p class="text-sm text-brand-muted" x-text="t.message"></p>
            </div>
            <button type="button" class="shrink-0 text-brand-muted transition hover:text-white" @click="dismiss(t.id)">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>