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

        <!-- Pending Invitations Section -->
        @if (!$pendingInvitations->isEmpty())
            <div class="mb-8">
                <h3 class="mb-4 text-2xl font-semibold text-orange-600 dark:text-orange-400">
                    <i class="fas fa-envelope mr-2"></i>
                    Pending Invitations ({{ $pendingInvitations->count() }})
                </h3>
                <div class="rounded-lg border border-orange-200 bg-orange-50 p-4 dark:border-orange-800 dark:bg-orange-900/20">
                    @foreach ($pendingInvitations as $company)
                        <div class="flex items-center justify-between border-b border-orange-200 py-3 last:border-b-0 dark:border-orange-700">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $company->name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Role: <span class="font-medium text-orange-600 dark:text-orange-400">{{ ucfirst($company->pivot->role) }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Invited {{ $company->pivot->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('dashboard.companies.members.accept.submit', $company->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white transition hover:bg-green-700">
                                        <i class="fas fa-check mr-1"></i>
                                        Accept
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.companies.members.leave', $company->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm text-white transition hover:bg-red-700"
                                            onclick="return confirm('Are you sure you want to decline this invitation?')">
                                        <i class="fas fa-times mr-1"></i>
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Active Companies Section -->
        @if ($activeCompanies->isEmpty() && $pendingInvitations->isEmpty())
            <p class="py-4 text-center text-gray-500 dark:text-white">
                No Companies found
            </p>
        @elseif ($activeCompanies->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    No Active Companies
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    You don't have any active company memberships yet.
                </p>
                <a href="{{ route('dashboard.companies.create') }}"
                   class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Company
                </a>
            </div>
        @else
            <div class="mb-4">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-building mr-2"></i>
                    My Companies ({{ $activeCompanies->count() }})
                </h3>
            </div>
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
                        @foreach ($activeCompanies as $company)
                            <tr
                                class="even:bg-gray-50 hover:bg-gray-100 dark:text-white dark:even:bg-gray-900 dark:hover:bg-gray-900"
                            >
                                <td class="border border-gray-300 px-6 py-4">
                                    <div class="flex items-center">
                                        <span>{{ $company->name }}</span>
                                        @if ($company->pivot->role === 'owner')
                                            <span class="ml-2 inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                <i class="fas fa-crown mr-1"></i>
                                                Owner
                                            </span>
                                        @elseif ($company->pivot->role === 'admin')
                                            <span class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Admin
                                            </span>
                                        @else
                                            <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <i class="fas fa-user mr-1"></i>
                                                Member
                                            </span>
                                        @endif
                                    </div>
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
                                            href="{{ route("dashboard.companies.show", $company->id) }}"
                                            class="align-center focus:ring-opacity-50 flex rounded-md bg-green-600 px-3 py-2 text-white transition duration-200 hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:outline-hidden"
                                        >
                                            View
                                        </a>
                                        @if ($company->pivot->role === 'owner' || $company->pivot->role === 'admin')
                                            <a
                                                href="{{ route("dashboard.companies.edit", $company->id) }}"
                                                class="align-center focus:ring-opacity-50 flex rounded-md bg-blue-600 px-3 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                                            >
                                                Edit
                                            </a>
                                        @endif

                                        @if ($company->pivot->role === 'owner')
                                            <form
                                                method="POST"
                                                action="{{ route("dashboard.companies.destroy", $company->id) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this company?');"
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
                                        @elseif ($company->pivot->role !== 'owner')
                                            <form
                                                method="POST"
                                                action="{{ route("dashboard.companies.members.leave", $company->id) }}"
                                                onsubmit="return confirm('Are you sure you want to leave this company?');"
                                                style="display: inline"
                                            >
                                                @csrf
                                                @method("DELETE")
                                                <button
                                                    type="submit"
                                                    class="focus:ring-opacity-50 flex items-center rounded-md bg-orange-600 px-4 py-2 text-white transition duration-200 hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:outline-hidden"
                                                >
                                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                                    Leave
                                                </button>
                                            </form>
                                        @endif
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
