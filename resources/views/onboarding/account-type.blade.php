@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                        <span>Step 2 of 4</span>
                        <span>50% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 50%"></div>
                    </div>
                </div>

                <!-- Account Type Selection Card -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Account Type</h1>
                        <p class="text-gray-600">
                            Are you setting up appointments for yourself or managing a team?
                        </p>
                    </div>

                    <form action="{{ route('onboarding.account-type.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <!-- Individual Account -->
                            <label for="individual" class="relative cursor-pointer">
                                <input type="radio" id="individual" name="account_type" value="individual" class="sr-only peer" required>
                                <div class="border-2 border-gray-200 rounded-xl p-6 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-all duration-200">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Individual</h3>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Perfect for freelancers, consultants, or solo professionals
                                        </p>
                                        <div class="text-left space-y-2">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Personal booking calendar
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Custom services & pricing
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Simple setup process
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selected indicator -->
                                <div class="absolute top-4 right-4 w-6 h-6 bg-indigo-500 rounded-full items-center justify-center text-white hidden peer-checked:flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </label>

                            <!-- Company Account -->
                            <label for="company" class="relative cursor-pointer">
                                <input type="radio" id="company" name="account_type" value="company" class="sr-only peer" required>
                                <div class="border-2 border-gray-200 rounded-xl p-6 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-all duration-200">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Company</h3>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Ideal for businesses with multiple team members
                                        </p>
                                        <div class="text-left space-y-2">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Team member management
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Shared company calendar
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Public booking page
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selected indicator -->
                                <div class="absolute top-4 right-4 w-6 h-6 bg-indigo-500 rounded-full items-center justify-center text-white hidden peer-checked:flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </label>
                        </div>

                        @error('account_type')
                            <div class="mb-4 text-sm text-red-600">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Continue
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                            <a href="{{ route('onboarding.welcome') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
