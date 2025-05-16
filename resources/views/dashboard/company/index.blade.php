@extends('partials.dashboard.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-12">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Your Companies
            </h1>

            <a href="{{ route('dashboard.company.create') }}"
               class="inline-block bg-blue-600 text-white px-5 py-3 rounded-xl shadow-md hover:bg-blue-700 transition">
                + Add Company
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-800 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-100 px-6 py-4 rounded-lg mb-10">
                {{ session('success') }}
            </div>
        @endif

        {{-- Companies Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-8 pt-4">
            @forelse($companies as $company)
                <a href="{{ route('dashboard.company.edit', $company) }}"
                   class="group block bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:ring-2 hover:ring-blue-500 transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div
                          class="flex-shrink-0 w-16 h-16 rounded-full 
                                 bg-gradient-to-br from-blue-700 to-blue-900 
                                 flex items-center justify-center text-xl font-bold
                                 text-white drop-shadow-md
                                 border-2 border-blue-700 dark:border-transparent"
                        >
                            {{ strtoupper(substr($company->company_name, 0, 2)) }}
                        </div>

                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                {{ $company->company_name }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $company->city ?? 'No city specified' }}
                            </p>
                        </div>
                    </div>

                    {{-- Divider line --}}
                    <div class="border-t mt-4 pt-4 border-gray-200 dark:border-gray-700"></div>

                    {{-- User count --}}
                    <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2">
                        <span class="text-lg">👥</span>
                        {{ $company->users_count ?? 0 }} {{ Str::plural('User', $company->users_count ?? 0) }}
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center text-gray-500 dark:text-gray-400">
                    No companies found.
                </div>
            @endforelse
        </div>
    </div>
@endsection
