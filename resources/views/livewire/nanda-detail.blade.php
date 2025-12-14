<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-4 h-4 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Search
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-indigo-600 px-8 py-6">
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">{{ $nanda->label }}</h1>
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm">
                        Code: {{ $nanda->code }}
                    </span>
                </div>
                @if($nanda->nandaClass)
                    <div class="text-indigo-100 text-sm flex gap-4">
                        <span><strong>Domain:</strong> {{ $nanda->nandaClass->domain->name ?? 'N/A' }}</span>
                        <span>&bull;</span>
                        <span><strong>Class:</strong> {{ $nanda->nandaClass->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-8">
            <div class="prose prose-indigo max-w-none">
                <h3 class="text-gray-900 text-lg font-semibold mb-3">Definition & Description</h3>
                <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $nanda->description ?: 'No description available for this diagnosis.' }}
                </p>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex justify-between items-center">
                <div class="text-sm text-gray-400">
                    Last updated: {{ $nanda->updated_at->format('M d, Y') }}
                </div>
                <!-- Placeholder for future actions like Edit -->
            </div>
        </div>
    </div>
</div>