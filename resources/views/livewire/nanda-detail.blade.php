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
                        <span><strong>{{ __('Domain') }}:</strong> {{ $nanda->nandaClass->domain->name ?? 'N/A' }}</span>
                        <span>&bull;</span>
                        <span><strong>{{ __('Class') }}:</strong> {{ $nanda->nandaClass->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-8">
            <div class="prose max-w-none text-gray-700">
                {{ $nanda->description }}
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-indigo-600 font-medium hover:text-indigo-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    {{ __('Back to Search') }}
                </a>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-100 flex justify-between items-center">
            <div class="text-sm text-gray-400">
                Last updated: {{ $nanda->updated_at->format('M d, Y') }}
            </div>
            <!-- Placeholder for future actions like Edit -->
        </div>
    </div>
</div>