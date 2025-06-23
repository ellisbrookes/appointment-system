@extends("dashboard.layout")

@section("content")
    <div class="container mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-center text-4xl font-bold text-gray-700 dark:text-white">
                Company Details
            </h2>
            <div class="flex space-x-4">
                <a
                    href="{{ route('dashboard.companies.edit', $company->id) }}"
                    class="rounded-lg bg-blue-600 px-6 py-3 text-white transition duration-200 hover:bg-blue-700"
                >
                    Edit Company
                </a>
                <a
                    href="{{ route('dashboard.companies.index') }}"
                    class="rounded-lg bg-gray-600 px-6 py-3 text-white transition duration-200 hover:bg-gray-700"
                >
                    Back to List
                </a>
            </div>
        </div>

        <div class="rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Company Name
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->name }}
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Email
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->email ?: 'Not provided' }}
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Phone Number
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->phone_number ?: 'Not provided' }}
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Address
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->address ?: 'Not provided' }}
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Postcode
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->postcode ?: 'Not provided' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-white">
                        Description
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $company->description ?: 'No description provided' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
