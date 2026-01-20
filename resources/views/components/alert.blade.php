@props([
    'type' => 'success', // success | error | warning | info
    'message' => ''
])

@php
    $colors = [
        'success' => 'bg-emerald-900/80 border-emerald-600 text-emerald-200',
        'error'   => 'bg-red-900/80 border-red-600 text-red-200',
        'warning' => 'bg-amber-900/80 border-amber-600 text-amber-200',
        'info'    => 'bg-slate-800 border-slate-600 text-slate-200',
    ];

    $icons = [
        'success' => '✔',
        'error'   => '✖',
        'warning' => '⚠',
        'info'    => 'ℹ',
    ];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 4000)"
    class="flex items-start gap-3
           border-l-4 rounded-lg p-4 shadow-lg
           {{ $colors[$type] }}"
>
    <div class="text-lg font-bold">
        {{ $icons[$type] }}
    </div>

    <div class="flex-1 text-sm leading-relaxed">
        {{ $message }}
    </div>

    <button
        @click="show = false"
        class="text-lg opacity-70 hover:opacity-100 transition"
    >
        ×
    </button>
</div>
