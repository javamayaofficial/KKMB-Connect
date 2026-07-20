@foreach (['status' => 'teal', 'success' => 'teal', 'info' => 'blue', 'error' => 'rose'] as $key => $color)
    @if (session($key))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mb-4 rounded-2xl px-4 py-3 text-sm bg-{{ $color }}-50 text-{{ $color }}-800 border border-{{ $color }}-200 dark:bg-{{ $color }}-950/40 dark:text-{{ $color }}-200 dark:border-{{ $color }}-900 flex justify-between items-start gap-3">
            <span>{{ session($key) }}</span>
            <button @click="show = false" class="text-{{ $color }}-500 shrink-0">&times;</button>
        </div>
    @endif
@endforeach
@if ($errors->any())
    <div class="mb-4 rounded-2xl px-4 py-3 text-sm bg-rose-50 text-rose-800 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-200 dark:border-rose-900">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
