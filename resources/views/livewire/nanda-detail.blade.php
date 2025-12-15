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
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Definition') }}</h3>
                <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    {{ $nanda->description }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                     <h4 class="font-medium text-gray-900 mb-2">{{ __('Diagnostic Details') }}</h4>
                     <ul class="space-y-2 text-sm text-gray-600">
                        @if($nanda->approval_year)
                            <li class="flex items-start gap-2"><span class="font-medium whitespace-nowrap">{{ __('Approved') }}:</span> <span>{{ $nanda->approval_year }}</span></li>
                        @endif
                         @if($nanda->evidence_level)
                            <li class="flex items-start gap-2"><span class="font-medium whitespace-nowrap">{{ __('Evidence Level') }}:</span> <span>{{ $nanda->evidence_level }}</span></li>
                        @endif
                         @if($nanda->diagnosis_status)
                            <li class="flex items-start gap-2"><span class="font-medium whitespace-nowrap">{{ __('Status') }}:</span> <span>{{ $nanda->diagnosis_status }}</span></li>
                        @endif
                     </ul>
                </div>
                <div>
                     <h4 class="font-medium text-gray-900 mb-2">{{ __('Focus & Judgment') }}</h4>
                     <ul class="space-y-2 text-sm text-gray-600">
                        @if($nanda->focus)
                            <li class="flex items-start gap-2"><span class="font-medium whitespace-nowrap">{{ __('Focus') }}:</span> <span>{{ $nanda->focus }}</span></li>
                        @endif
                         @if($nanda->judgment)
                            <li class="flex items-start gap-2"><span class="font-medium whitespace-nowrap">{{ __('Judgment') }}:</span> <span>{{ $nanda->judgment }}</span></li>
                        @endif
                     </ul>
                </div>
            </div>

            @if(!empty($nanda->risk_factors))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Risk Factors') }}
                    </h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($nanda->risk_factors as $factor)
                            <li class="flex items-start text-sm text-gray-700">
                                <span class="mr-2 text-indigo-400">&bull;</span> {{ $factor }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!empty($nanda->at_risk_population))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                         <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        {{ __('At Risk Population') }}
                    </h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($nanda->at_risk_population as $pop)
                            <li class="flex items-start text-sm text-gray-700">
                                <span class="mr-2 text-indigo-400">&bull;</span> {{ $pop }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

             @if(!empty($nanda->associated_conditions))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        {{ __('Associated Conditions') }}
                    </h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($nanda->associated_conditions as $cond)
                            <li class="flex items-start text-sm text-gray-700">
                                <span class="mr-2 text-indigo-400">&bull;</span> {{ $cond }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

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