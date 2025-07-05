@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
                    <p class="text-xl text-gray-600">
                        Select the perfect plan for your appointment management needs
                    </p>
                </div>

                <!-- Current Subscription Status -->
                @if($user->subscribed('default'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-green-800">Active Subscription</h3>
                                <p class="text-green-700">You have an active subscription. Thank you for being a valued customer!</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('subscription.billing') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                                Manage Billing
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-amber-800">No Active Subscription</h3>
                                <p class="text-amber-700">Subscribe to unlock all features and start managing your appointments.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pricing Toggle -->
                <div x-data="{ yearly: false }" class="flex flex-col items-center">
                    <div class="mb-12 flex justify-center">
                        <label class="flex cursor-pointer items-center gap-4">
                            <span class="text-lg font-semibold" :class="yearly ? 'text-gray-500' : 'text-gray-900'">Monthly</span>
                            <div @click="yearly = !yearly" class="relative h-6 w-12 rounded-full bg-gray-300 p-1 transition-colors" :class="yearly ? 'bg-indigo-600' : 'bg-gray-300'">
                                <div :class="yearly ? 'translate-x-6 bg-white' : 'translate-x-0 bg-white'" class="absolute top-1 left-1 h-4 w-4 transform rounded-full transition-transform shadow-sm"></div>
                            </div>
                            <span class="text-lg font-semibold" :class="yearly ? 'text-gray-900' : 'text-gray-500'">
                                Yearly 
                                <span class="text-sm text-green-600 font-normal">(Save 20%)</span>
                            </span>
                        </label>
                    </div>

                    <!-- Pricing Cards -->
                    <div class="grid w-full max-w-6xl grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($productsWithPrices as $product)
                            @foreach ($product->prices as $price)
                                <div x-show="yearly === {{ $price['interval'] === 'year' ? 'true' : 'false' }}" 
                                     class="flex flex-col bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-8 flex-1">
                                        <div class="text-center mb-6">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                            <div class="mb-4">
                                                <span class="text-4xl font-bold text-gray-900">${{ number_format($price['unit_amount'] / 100, 0) }}</span>
                                                <span class="text-gray-600">/{{ $price['interval'] }}</span>
                                            </div>
                                            @if($product->description)
                                                <p class="text-gray-600">{{ $product->description }}</p>
                                            @endif
                                        </div>

                                        <!-- Features -->
                                        <ul class="space-y-3 mb-8">
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700">Unlimited appointments</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700">Calendar management</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700">Email notifications</span>
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700">Public booking page</span>
                                            </li>
                                            @if($product->name === 'Premium' || $product->name === 'Enterprise')
                                                <li class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Team management</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Advanced analytics</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <!-- CTA Button -->
                                    <div class="p-8 pt-0">
                                        @if($user->subscribed('default'))
                                            <button disabled class="w-full py-3 px-4 bg-gray-300 text-gray-500 font-medium rounded-md cursor-not-allowed">
                                                Current Plan
                                            </button>
                                        @else
                                            <form action="{{ route('subscription.checkout') }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="price_id" value="{{ $price['id'] }}">
                                                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
                                                    Get Started
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- Stripe Connect Section -->
                <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Accept Payments with Stripe</h2>
                        <p class="text-gray-600">
                            Connect your Stripe account to start accepting payments from your clients directly.
                        </p>
                    </div>

                    @if($user->stripe_connect_id)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-green-800">Stripe Connected</h3>
                                        <p class="text-green-700">Your Stripe account is connected and ready to accept payments.</p>
                                    </div>
                                </div>
                                <form action="{{ route('subscription.stripe-connect.disconnect') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors">
                                        Disconnect
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <form action="{{ route('subscription.stripe-connect.create') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    Connect Stripe Account
                                </button>
                            </form>
                            <p class="text-sm text-gray-500 mt-2">
                                Secure connection powered by Stripe Connect
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
