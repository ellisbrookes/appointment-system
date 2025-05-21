@extends('partials.dashboard.layout')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="font-semibold text-2xl text-black dark:text-white leading-tight">
                {{ __('Edit Company Information') }}
            </h2>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-gray-400 dark:bg-gray-900 sm:rounded-lg shadow">
            <div class="max-w-xl mx-auto" x-data="{ showAddUser: false }">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.company.update', $company->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Company Name -->
                    <div class="mb-5">
                        <label for="company_name" class="block font-semibold dark:text-white mb-1">Company Name</label>
                        <input type="text" name="company_name" id="company_name"
                               value="{{ old('company_name', $company->company_name) }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-5">
                        <label for="address" class="block font-semibold dark:text-white mb-1">Address</label>
                        <input type="text" name="address" id="address"
                               value="{{ old('address', $company->address) }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="mb-5">
                        <label for="city" class="block font-semibold dark:text-white mb-1">City</label>
                        <input type="text" name="city" id="city"
                               value="{{ old('city', $company->city) }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postcode -->
                    <div class="mb-6">
                        <label for="postcode" class="block font-semibold dark:text-white mb-1">Postcode</label>
                        <input type="text" name="postcode" id="postcode"
                               value="{{ old('postcode', $company->postcode) }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('postcode')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Toggle Add User Button -->
                    <div class="mb-6">
                        <button type="button"
                                @click="showAddUser = !showAddUser"
                                class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ease-in-out">
                            + Add User
                        </button>
                    </div>

                    <!-- Add User Email Input -->
                    <div x-show="showAddUser" x-transition.duration.300ms class="mb-6">
                        <label for="user_email" class="block font-semibold dark:text-white mb-1">User Email</label>
                        <input type="email" name="user_email" id="user_email"
                               value="{{ old('user_email') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="user@example.com">
                        <p class="text-sm text-gray-500 mt-1">User will be added to this company by email.</p>
                        @error('user_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition duration-150 ease-in-out">
                        Update Company
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
