<div class="max-w-4xl mx-auto py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
            {{ __('NANDA Nursing Diagnoses') }}
        </h1>
        <p class="text-gray-600 mb-8">{{ __('Search for nursing diagnoses by code or keyword') }}</p>

        <div class="max-w-xl mx-auto relative mb-6" x-data="{ 
                showSuggestions: false
            }" @click.away="showSuggestions = false">

            <input wire:model.live.debounce.500ms="search" type="text" @focus="showSuggestions = true"
                @input="showSuggestions = true" placeholder="{{ __('Search NANDA (e.g., Acute Pain, 00132)...') }}"
                class="w-full px-5 py-3 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">

            <div class="absolute right-4 top-3.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>

            @if(!empty($suggestions))
                <div x-show="showSuggestions" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                    <div class="py-2">
                        <div class="px-4 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ __('Suggestions') }}
                        </div>
                        @foreach($suggestions as $suggestion)
                            <button type="button" wire:click="$set('search', '{{ str_replace("'", "\\'", $suggestion) }}')"
                                @click="showSuggestions = false"
                                class="w-full text-left px-5 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition flex items-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-3.5 h-3.5 mr-3 text-gray-300 group-hover:text-indigo-400 transition">
                                    <path fill-rule="evenodd"
                                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $suggestion }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @php
            $domainColors = [
                '01' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                '02' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                '03' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                '04' => 'bg-amber-100 text-amber-700 border-amber-200',
                '05' => 'bg-rose-100 text-rose-700 border-rose-200',
                '06' => 'bg-violet-100 text-violet-700 border-violet-200',
                '07' => 'bg-orange-100 text-orange-700 border-orange-200',
                '08' => 'bg-pink-100 text-pink-700 border-pink-200',
                '09' => 'bg-blue-100 text-blue-700 border-blue-200',
                '10' => 'bg-teal-100 text-teal-700 border-teal-200',
                '11' => 'bg-red-100 text-red-700 border-red-200',
                '12' => 'bg-lime-100 text-lime-700 border-lime-200',
                '13' => 'bg-sky-100 text-sky-700 border-sky-200',
            ];
        @endphp

        <div class="flex flex-wrap justify-center gap-2 mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            @foreach($domains as $domain)
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $domainColors[$domain->code] ?? 'bg-gray-100 text-gray-700 border-gray-200' }} shadow-sm">
                    {{ $domain->code }}. {{ $domain->name }}
                </span>
            @endforeach
        </div>

        @if($nandas->total() > 0)
            <div class="text-center mb-8 animate-in fade-in zoom-in duration-500">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-medium border border-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2">
                        <path fill-rule="evenodd"
                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ trans_choice('{1} :count diagnosis found|[2,*] :count diagnoses found', $nandas->total(), ['count' => $nandas->total()]) }}
                </span>
            </div>
        @endif
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
                            {{ __('Class') }}: {{ $nanda->nandaClass->code }}. {{ $nanda->nandaClass->name }}
                        </span>
                        @if($nanda->nandaClass->domain)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ __('Domain') }}: {{ $nanda->nandaClass->domain->code }}. {{ $nanda->nandaClass->domain->name }}
                            </span>
                        @endif
                    @endif

                    @if($nanda->approval_year)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ __('Year') }}: {{ $nanda->approval_year }}
                        </span>
                    @endif
                    @if($nanda->evidence_level)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
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