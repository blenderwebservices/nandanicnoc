<div class="max-w-4xl mx-auto py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
            {{ __('NANDA Nursing Diagnoses') }}
        </h1>
        <p class="text-gray-600 mb-8">{{ __('Search for nursing diagnoses by code or keyword') }}</p>

        <div class="max-w-xl mx-auto relative">
            <input wire:model.live.debounce.500ms="search" type="text"
                placeholder="{{ __('Search NANDA (e.g., Acute Pain, 00132)...') }}"
                class="w-full px-5 py-3 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            <div class="absolute right-4 top-3.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($nandas as $nanda)
            <a href="{{ route('nanda.detail', $nanda) }}"
                class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 group">
                <div class="flex flex-wrap gap-2 mb-3">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ __('Code') }}: {{ $nanda->code }}
                    </span>
                    @if($nanda->nandaClass)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ __('Class') }}: {{ $nanda->nandaClass->name }}
                        </span>
                        @if($nanda->nandaClass->domain)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ __('Domain') }}: {{ $nanda->nandaClass->domain->name }}
                            </span>
                        @endif
                    @endif

                    @if($nanda->approval_year)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ __('Year') }}: {{ $nanda->approval_year }}
                        </span>
                    @endif
                    @if($nanda->evidence_level)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ __('LOE') }}: {{ $nanda->evidence_level }}
                        </span>
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                    {{ $nanda->label }}
                </h3>
                <p class="text-sm text-gray-500 line-clamp-3">{{ $nanda->description }}</p>
                <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium">
                    {{ __('Learn more') }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-12 h-12 mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <p class="text-gray-500 text-lg">{{ __('No diagnoses found matching your search.') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $nandas->links() }}
    </div>
</div>