@extends('partials.dashboard.layout')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Company Information</h2>

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
                <label for="company_name" class="block font-semibold">Company Name</label>
                <input type="text" name="company_name" id="company_name"
                       value="{{ old('company_name', $company->company_name) }}"
                       class="w-full border-gray-300 rounded mt-1" required>
                @error('company_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block font-semibold">Address</label>
                <input type="text" name="address" id="address"
                       value="{{ old('address', $company->address) }}"
                       class="w-full border-gray-300 rounded mt-1">
                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- City -->
            <div class="mb-4">
                <label for="city" class="block font-semibold">City</label>
                <input type="text" name="city" id="city"
                       value="{{ old('city', $company->city) }}"
                       class="w-full border-gray-300 rounded mt-1">
                @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Postcode -->
            <div class="mb-4">
                <label for="postcode" class="block font-semibold">Postcode</label>
                <input type="text" name="postcode" id="postcode"
                       value="{{ old('postcode', $company->postcode) }}"
                       class="w-full border-gray-300 rounded mt-1">
                @error('postcode') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Update Company
            </button>
        </form>
    </div>
@endsection
