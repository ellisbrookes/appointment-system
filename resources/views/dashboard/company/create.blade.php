@extends('partials.dashboard.layout')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Create Company</h1>

    <form method="POST" action="{{ route('dashboard.company.store') }}">
        @csrf

        <!-- Company Name -->
        <div class="mb-4">
            <label for="company_name" class="block font-semibold mb-1">Company Name</label>
            <input
                type="text"
                id="company_name"
                name="company_name"
                class="w-full border rounded px-3 py-2"
                value="{{ old('company_name', $company_name ?? '') }}"
                placeholder="Enter company name"
            >
            <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label for="address" class="block font-semibold mb-1">Address</label>
            <input
                type="text"
                id="address"
                name="address"
                class="w-full border rounded px-3 py-2"
                value="{{ old('address') }}"
                placeholder="Enter company address"
            >
            <x-input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

        <!-- City -->
        <div class="mb-4">
            <label for="city" class="block font-semibold mb-1">City</label>
            <input
                type="text"
                id="city"
                name="city"
                class="w-full border rounded px-3 py-2"
                value="{{ old('city') }}"
                placeholder="Enter city"
            >
            <x-input-error :messages="$errors->get('city')" class="mt-1" />
        </div>

        <!-- Postcode -->
        <div class="mb-4">
            <label for="postcode" class="block font-semibold mb-1">Postcode</label>
            <input
                type="text"
                id="postcode"
                name="postcode"
                class="w-full border rounded px-3 py-2"
                value="{{ old('postcode') }}"
                placeholder="Enter postcode"
            >
            <x-input-error :messages="$errors->get('postcode')" class="mt-1" />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label for="phone" class="block font-semibold mb-1">Phone Number</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                class="w-full border rounded px-3 py-2"
                value="{{ old('phone') }}"
                placeholder="Enter phone number"
            >
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="w-full border rounded px-3 py-2"
                value="{{ old('email') }}"
                placeholder="Enter email address"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition"
            >
                Save Company
            </button>
        </div>
    </form>
</div>
@endsection
