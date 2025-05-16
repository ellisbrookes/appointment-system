@extends('partials.dashboard.layout')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6">
        <h2 class="font-semibold text-2xl text-black dark:text-white leading-tight">
            {{ __('Edit Company Information') }}
        </h2>

        <div class="p-4 sm:p-8 bg-white border border-gray-400 dark:bg-gray-900 sm:rounded-lg">
            <div class="max-w-xl">
                @if(session('success'))
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.company.update', $company->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Company Name -->
                    <div class="mb-4">
                        <label for="company_name" class="block font-semibold dark:text-white">Company Name</label>
                        <input type="text" name="company_name" id="company_name"
                               value="{{ old('company_name', $company->company_name) }}"
                               class="w-full border border-gray-300 rounded mt-1">
                        @error('company_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block font-semibold dark:text-white">Address</label>
                        <input type="text" name="address" id="address"
                               value="{{ old('address', $company->address) }}"
                               class="w-full border border-gray-300 rounded mt-1">
                        @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- City -->
                    <div class="mb-4">
                        <label for="city" class="block font-semibold dark:text-white">City</label>
                        <input type="text" name="city" id="city"
                               value="{{ old('city', $company->city) }}"
                               class="w-full border border-gray-300 rounded mt-1">
                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Postcode -->
                    <div class="mb-4">
                        <label for="postcode" class="block font-semibold dark:text-white">Postcode</label>
                        <input type="text" name="postcode" id="postcode"
                               value="{{ old('postcode', $company->postcode) }}"
                               class="w-full border border-gray-300 rounded mt-1">
                        @error('postcode') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Update Company
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
