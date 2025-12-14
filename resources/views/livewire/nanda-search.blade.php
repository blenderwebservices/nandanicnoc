<div class="space-y-6">
    <div class="text-center py-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">NANDA Nursing Diagnoses</h1>
        <p class="text-gray-600 mb-8">Search for nursing diagnoses by code or keyword</p>

        <div class="max-w-xl mx-auto relative">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Search NANDA (e.g., Acute Pain, 00132)..."
                class="w-full px-5 py-3 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            <div class="absolute right-4 top-3.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($nandas as $nanda)
            <a href="{{ route('nanda.detail', $nanda) }}"
                class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 group">
                <div class="flex flex-wrap gap-2 mb-3">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Code: {{ $nanda->code }}
                    </span>
                    @if($nanda->nandaClass)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Class: {{ $nanda->nandaClass->name }}
                        </span>
                        @if($nanda->nandaClass->domain)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Domain: {{ $nanda->nandaClass->domain->name }}
                            </span>
                        @endif
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                    {{ $nanda->label }}
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    {{ $nanda->description }}
                </p>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <p class="text-lg">No diagnoses found matching "{{ $search }}"</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $nandas->links() }}
    </div>
</div>