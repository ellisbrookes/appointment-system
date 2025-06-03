@php
    use Carbon\Carbon;
@endphp

@extends("dashboard.layout")

@section("content")
    <div class="container mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h2
                class="text-center text-4xl font-bold text-gray-700 dark:text-white"
            >
                Company List
            </h2>
            <a
                href="{{ route("dashboard.companies.create") }}"
                class="rounded-lg bg-green-600 px-6 py-3 text-white transition duration-200 hover:bg-green-700 dark:text-white"
            >
                Create Company
            </a>
        </div>

        @if ($companies->isEmpty())
            <p class="py-4 text-center text-gray-500 dark:text-white">
                No Companies found
            </p>
        @else
            <div class="overflow-x-auto">
                <table
                    class="text-md w-full table-auto border-collapse border border-gray-400 text-center md:table-fixed"
                >
                    <thead>
                        <tr
                            class="bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-white"
                        >
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Name
                            </th>
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Email
                            </th>
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Phone Number
                            </th>
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Address
                            </th>
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Description
                            </th>
                            <th
                                class="border border-gray-300 px-6 py-3 text-center"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr
                                class="even:bg-gray-50 hover:bg-gray-100 dark:text-white dark:even:bg-gray-900 dark:hover:bg-gray-900"
                            >
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $company->name }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $company->email }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $company->phone_number }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $company->address }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $company->description }}
                                </td>
                                <td class="border-b border-gray-300 px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            href="{{ route("dashboard.companies.index") }}"
                                            class="align-center focus:ring-opacity-50 flex rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route("dashboard.companies.destroy", $company->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this company');"
                                            style="display: inline"
                                        >
                                            @csrf
                                            @method("DELETE")
                                            <button
                                                type="submit"
                                                class="focus:ring-opacity-50 flex items-center rounded-md bg-red-600 px-4 py-2 text-white transition duration-200 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:outline-hidden"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
