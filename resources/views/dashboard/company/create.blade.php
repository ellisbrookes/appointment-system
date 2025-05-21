@extends('partials.dashboard.layout')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6">
        <div>
            <h1 class="font-semibold text-2xl text-black dark:text-white leading-tight">
                Create Company
            </h1>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-gray-400 dark:bg-gray-900 sm:rounded-lg shadow">
            <div class="max-w-xl mx-auto">

                <form method="POST" action="{{ route('dashboard.company.store') }}">
                    @csrf

                    <!-- Company Name -->
                    <div class="mb-5">
                        <label for="company_name" class="block font-semibold dark:text-white mb-1">Company Name</label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('company_name', $company_name ?? '') }}"
                            placeholder="Enter company name"
                        >
                        <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                    </div>

                    <!-- Address -->
                    <div class="mb-5">
                        <label for="address" class="block font-semibold dark:text-white mb-1">Address</label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('address') }}"
                            placeholder="Enter company address"
                        >
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>

                    <!-- City -->
                    <div class="mb-5">
                        <label for="city" class="block font-semibold dark:text-white mb-1">City</label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('city') }}"
                            placeholder="Enter city"
                        >
                        <x-input-error :messages="$errors->get('city')" class="mt-1" />
                    </div>

                    <!-- Postcode -->
                    <div class="mb-6">
                        <label for="postcode" class="block font-semibold dark:text-white mb-1">Postcode</label>
                        <input
                            type="text"
                            id="postcode"
                            name="postcode"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('postcode') }}"
                            placeholder="Enter postcode"
                        >
                        <x-input-error :messages="$errors->get('postcode')" class="mt-1" />
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="block font-semibold dark:text-white mb-1">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone number"
                        >
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block font-semibold dark:text-white mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('email') }}"
                            placeholder="Enter email address"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition duration-150 ease-in-out"
                        >
                            Save Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
