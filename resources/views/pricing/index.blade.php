@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
                    <p class="text-xl text-gray-600 mb-4">
                        Choose the perfect plan for your appointment management needs
                    </p>
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        🎉 All plans include a 10-day free trial - no credit card required!
                    </div>
                </div>

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
                                     class="flex flex-col bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ $product->name === 'Advanced' ? 'border-2 border-indigo-500 relative' : 'border border-gray-200' }}">
                                    
                                    @if($product->name === 'Advanced')
                                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                            <span class="bg-indigo-500 text-white px-3 py-1 rounded-full text-xs font-medium">Most Popular</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-8 flex-1">
                                        <div class="text-center mb-6">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                            <div class="mb-4">
                                                <span class="text-4xl font-bold text-gray-900">${{ number_format($price['unit_amount'] / 100, 0) }}</span>
                                                <span class="text-gray-600">/{{ $price['interval'] }}</span>
                                                <div class="mt-2 text-sm text-green-600 font-semibold">
                                                    🎉 10-day free trial included
                                                </div>
                                            </div>
                                            @if($product->description)
                                                <p class="text-gray-600">{{ $product->description }}</p>
                                            @endif
                                        </div>

                                        <!-- Features -->
                                        <ul class="space-y-3 mb-8">
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-gray-700 font-semibold">10-day free trial</span>
                                            </li>
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
                                            @if($product->name === 'Advanced')
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
                                                <li class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-700">Priority support</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <!-- CTA Button -->
                                    <div class="p-8 pt-0">
                                        @auth
                                            @if(auth()->user()->subscribed('default'))
                                                <a href="{{ route('subscription.index') }}" class="w-full py-3 px-4 bg-gray-300 text-gray-700 font-medium rounded-md transition-colors text-center block">
                                                    Manage Subscription
                                                </a>
                                            @else
                                                <form action="{{ route('pricing.select-plan') }}" method="POST" class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="plan_id" value="{{ $price['id'] }}">
                                                    <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
                                                        Start Free Trial
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('register') }}" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors text-center block">
                                                Start Free Trial
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- FAQ or Additional Info -->
                <div class="mt-16 text-center">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                        <p class="text-gray-600 mb-6">
                            Join thousands of professionals who trust Skedulaa to manage their business.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @auth
                                @if(auth()->user()->subscribed('default'))
                                    <a href="{{ route('subscription.billing') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Manage Billing
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                                        Go to Dashboard
                                    </a>
                                @else
                                    <p class="text-gray-600 mb-4">
                                        You're logged in! Choose a plan above to get started, or complete your onboarding first.
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                        <a href="{{ route('onboarding.welcome') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Complete Setup
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                                            Skip to Dashboard
                                        </a>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Start Free Trial
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                                    Already have an account?
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
