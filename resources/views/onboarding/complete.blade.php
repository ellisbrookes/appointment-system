@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Success Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="mb-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">🎉 Welcome to Your Appointment System!</h1>
                        <p class="text-lg text-gray-600 mb-6">
                            Congratulations {{ $user->name }}! Your setup is complete and you're ready to start managing appointments.
                        </p>
                    </div>

                    @if($accountType === 'company' && $company)
                        <!-- Company Setup Complete -->
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Company is Ready!</h2>
                            <div class="grid md:grid-cols-2 gap-6 text-left">
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Company Details</h3>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <strong>{{ $company->name }}</strong>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Public URL: <a href="{{ config('app.url') }}/{{ $company->url }}" class="text-indigo-600 hover:text-indigo-500 ml-1" target="_blank">{{ config('app.url') }}/{{ $company->url }}</a>
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Trial subscription activated (10 days)
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">What's Next</h3>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Team invitations sent
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Customize your company settings
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Share your booking link with clients
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Individual Setup Complete -->
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Individual Account is Ready!</h2>
                            <div class="grid md:grid-cols-2 gap-6 text-left">
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Account Setup</h3>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Personal calendar configured
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Working hours set
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Trial subscription activated (10 days)
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">What's Next</h3>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Create your first appointment
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Customize your settings
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Explore advanced features
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Subscription Step -->
                    @if(!$user->subscribed('default'))
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-8 mb-8 border border-purple-200">
                            @if($selectedPlan)
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-semibold text-purple-900 mb-2">🎯 Confirm Your Plan Selection</h3>
                                    <p class="text-purple-700 mb-2">
                                        You've selected a plan! Complete your setup and start your free trial.
                                    </p>
                                    <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        ✓ Plan selected - Ready to start!
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-semibold text-purple-900 mb-2">🚀 Choose Your Plan</h3>
                                    <p class="text-purple-700 mb-6">
                                        Your setup is complete! Now select a plan to unlock all features and start accepting appointments.
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Pricing Toggle -->
                            <div x-data="{ yearly: false }" class="mb-8">
                                <div class="flex justify-center mb-6">
                                    <label class="flex cursor-pointer items-center gap-4">
                                        <span class="text-sm font-medium" :class="yearly ? 'text-gray-500' : 'text-purple-900'">Monthly</span>
                                        <div @click="yearly = !yearly" class="relative h-6 w-12 rounded-full bg-gray-300 p-1 transition-colors" :class="yearly ? 'bg-purple-600' : 'bg-gray-300'">
                                            <div :class="yearly ? 'translate-x-6 bg-white' : 'translate-x-0 bg-white'" class="absolute top-1 left-1 h-4 w-4 transform rounded-full transition-transform shadow-sm"></div>
                                        </div>
                                        <span class="text-sm font-medium" :class="yearly ? 'text-purple-900' : 'text-gray-500'">
                                            Yearly <span class="text-xs text-green-600">(Save 20%)</span>
                                        </span>
                                    </label>
                                </div>
                                
                                <!-- Pricing Cards -->
                                @if($selectedPlan)
                                    <!-- Selected Plan Display -->
                                    @if($productsWithPrices)
                                        @foreach($productsWithPrices as $product)
                                            @foreach($product->prices as $price)
                                                @if($price['id'] === $selectedPlan)
                                                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 mb-6 border-2 border-green-400">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <div class="flex items-center">
                                                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-lg font-semibold text-gray-900">{{ $product->name }} Plan Selected</h4>
                                                                    <p class="text-sm text-gray-600">{{ $product->description }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-2xl font-bold text-gray-900">${{ number_format($price['unit_amount'] / 100, 0) }}</div>
                                                                <div class="text-sm text-gray-600">/{{ $price['interval'] }}</div>
                                                                <div class="text-xs text-green-600 font-semibold">🎉 10-day free trial</div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <div class="text-sm text-gray-600">Ready to start your free trial</div>
                                                            <form action="{{ route('subscription.checkout') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="price_id" value="{{ $price['id'] }}">
                                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                                                    Start Free Trial
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @break
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                    
                                    <!-- Suggestion Section -->
                                    <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                                        <h5 class="font-semibold text-blue-900 mb-2">💡 Not sure about your choice?</h5>
                                        <p class="text-sm text-blue-800 mb-3">Here are all available plans. You can always change your mind!</p>
                                        <button onclick="document.getElementById('all-plans').style.display = document.getElementById('all-plans').style.display === 'none' ? 'block' : 'none'" class="text-blue-600 hover:text-blue-500 text-sm font-medium underline">
                                            View all plans
                                        </button>
                                    </div>
                                @endif
                                
                                <div id="all-plans" class="grid md:grid-cols-2 gap-6" style="{{ $selectedPlan ? 'display: none;' : '' }}">
                                    @if($productsWithPrices)
                                        @foreach($productsWithPrices as $product)
                                            @php
                                                $monthlyPrice = $product->prices->where('interval', 'month')->first();
                                                $yearlyPrice = $product->prices->where('interval', 'year')->first();
                                            @endphp
                                            @if($monthlyPrice)
                                                <div class="bg-white rounded-lg shadow-md border-2 {{ $selectedPlan === $monthlyPrice['id'] ? 'border-green-400' : ($product->name === 'Advanced' ? 'border-purple-500' : 'border-gray-200') }} p-6 {{ $product->name === 'Advanced' ? 'relative' : '' }}">
                                                    @if($product->name === 'Advanced')
                                                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                                            <span class="bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-medium">Most Popular</span>
                                                        </div>
                                                    @endif
                                                    <div class="text-center mb-4">
                                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h4>
                                                        <div class="mb-3">
                                                            <span x-show="!yearly" class="text-2xl font-bold text-gray-900">${{ number_format($monthlyPrice['unit_amount'] / 100, 0) }}</span>
                                                            @if($yearlyPrice)
                                                                <span x-show="yearly" class="text-2xl font-bold text-gray-900">${{ number_format($yearlyPrice['unit_amount'] / 100, 0) }}</span>
                                                            @endif
                                                            <span x-show="!yearly" class="text-gray-600">/month</span>
                                                            @if($yearlyPrice)
                                                                <span x-show="yearly" class="text-gray-600">/year</span>
                                                            @endif
                                                            <div class="mt-2 text-sm text-green-600 font-semibold">
                                                                🎉 10-day free trial
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                                        <li class="flex items-center">
                                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            @if($product->name === 'Advanced')
                                                                Everything in Basic
                                                            @else
                                                                Unlimited appointments
                                                            @endif
                                                        </li>
                                                        @if($product->name === 'Basic')
                                                            <li class="flex items-center">
                                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Calendar management
                                                            </li>
                                                            <li class="flex items-center">
                                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Email notifications
                                                            </li>
                                                        @endif
                                                        @if($product->name === 'Advanced')
                                                            <li class="flex items-center">
                                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Team management
                                                            </li>
                                                            <li class="flex items-center">
                                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Advanced analytics
                                                            </li>
                                                            <li class="flex items-center">
                                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Priority support
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    <form action="{{ route('subscription.checkout') }}" method="POST">
                                                        @csrf
                                                        <input x-bind:value="yearly ? '{{ $yearlyPrice ? $yearlyPrice['id'] : $monthlyPrice['id'] }}' : '{{ $monthlyPrice['id'] }}'" type="hidden" name="price_id">
                                                        <button type="submit" class="w-full py-2 px-4 {{ $selectedPlan === $monthlyPrice['id'] ? 'bg-green-600 hover:bg-green-700' : 'bg-purple-600 hover:bg-purple-700' }} text-white font-medium rounded-md transition-colors">
                                                            {{ $selectedPlan === $monthlyPrice['id'] ? '✓ Selected - Start Trial' : 'Start Free Trial - ' . $product->name }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <a href="{{ route('dashboard') }}" class="text-purple-600 hover:text-purple-500 text-sm underline">
                                    I'll choose a plan later
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="grid md:grid-cols-3 gap-4 mb-8">
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                            </svg>
                            Go to Dashboard
                        </a>
                        
                        @if($accountType === 'company' && $company)
                            <a href="{{ config('app.url') }}/{{ $company->url }}" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Public Page
                            </a>
                            <a href="{{ route('dashboard.company.members.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Manage Team
                            </a>
                        @else
                            <a href="{{ route('dashboard.appointments.create.step.one') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Appointment
                            </a>
                            <a href="{{ route('settings') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                        @endif
                    </div>

                    <!-- Trial Information -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-amber-800">
                                <strong>Your 10-day trial has started!</strong> Explore all features and upgrade when you're ready.
                                <a href="{{ route('pricing') }}" class="text-amber-900 underline hover:text-amber-700 ml-1">View pricing</a>
                            </p>
                        </div>
                    </div>

                    <!-- Getting Started Tips -->
                    <div class="text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Getting Started Tips</h3>
                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">1. Customize Your Profile</h4>
                                <p class="text-gray-600">Update your profile information and working hours to match your schedule.</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">2. Set Up Services</h4>
                                <p class="text-gray-600">Define the services you offer and their durations for better appointment management.</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">3. Share Your Link</h4>
                                <p class="text-gray-600">Share your booking link with clients so they can schedule appointments directly.</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">4. Explore Features</h4>
                                <p class="text-gray-600">Discover calendar management, notifications, and other powerful features.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
