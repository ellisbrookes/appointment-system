@extends("dashboard.layout")

@section("title", "Accept Company Invitation")

@section("content")
    <div class="container mx-auto px-6">
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                        <i class="fas fa-building text-2xl text-blue-600"></i>
                    </div>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                        You're Invited!
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Join {{ $company->name }} as a team member
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg px-6 py-8 space-y-6">
                    <!-- Company Info -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $company->name }}
                        </h3>
                        @if($company->description)
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $company->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Membership Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Your Role</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $membership->role === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                    @if($membership->role === 'admin')
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Administrator
                                    @else
                                        <i class="fas fa-user mr-2"></i>
                                        Member
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Permissions</label>
                            <div class="mt-2 space-y-2">
                                @if($membership->role === 'admin')
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-check text-green-500 mr-3"></i>
                                        Manage team members
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-check text-green-500 mr-3"></i>
                                        Access all company features
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    View company information
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    Collaborate with team
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-4">
                        <!-- Debug Info -->
                        @if(config('app.debug'))
                            <div class="text-xs text-gray-500 p-2 bg-gray-100 rounded">
                                <strong>Debug Info:</strong><br>
                                Form Action: {{ route('dashboard.companies.members.accept.submit', $company) }}<br>
                                Company ID: {{ $company->id }}<br>
                                User ID: {{ auth()->id() }}<br>
                                Membership ID: {{ $membership->id }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('dashboard.companies.members.accept.submit', $company) }}" onsubmit="console.log('Form submitting to:', this.action, 'Method:', this.method)">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <i class="fas fa-check mr-2"></i>
                                Accept Invitation
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('dashboard.companies.index') }}" 
                               class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                View all companies instead
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        By accepting this invitation, you agree to join {{ $company->name }} as a {{ $membership->role }}.
                        You can leave the company at any time from your dashboard.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
