@extends('layouts.layout')

@section('content')

<section id="pricing" class="py-16">
  <div class="container mx-auto text-center">
    <h2 class="text-4xl font-bold mb-8">Pricing</h2>

    <!-- Toggle -->
    <div x-data="{ yearly: false }" class="flex flex-col items-center">
      <div class="flex justify-center mb-12">
        <label class="flex items-center gap-4 cursor-pointer">
          <span class="text-lg font-semibold">Monthly</span>
          <div @click="yearly = !yearly" class="relative w-12 h-6 bg-gray-300 dark:bg-gray-700 rounded-full p-1">
            <div 
              :class="yearly ? 'translate-x-6 bg-gray-400' : 'translate-x-0 bg-gray-500'" 
              class="absolute left-1 top-1 w-4 h-4 rounded-full transition-transform transform"
            ></div>
          </div>
          <span class="text-lg font-semibold">Yearly</span>
        </label>
      </div>

      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 w-full max-w-6xl">
        @foreach ($productsWithPrices as $product)
          @foreach($product->prices as $price)
            <div 
              class="dark:bg-gray-800 bg-white rounded-xl shadow-md px-8 py-10 flex flex-col items-center text-center transition-all hover:shadow-xl hover:-translate-y-2" 
              x-show="{{ $price['interval'] === 'year' ? 'yearly' : '!yearly' }}"
            >
              <h3 class="text-2xl font-semibold mb-3">{{ $product->name }}</h3>
              <p class="text-xl mb-6 font-bold">
                {{ number_format($price['unit_amount'] / 100, 2) }} {{ strtoupper($price['currency']) }}
              </p>

              <ul class="space-y-2 text-gray-700 dark:text-gray-300 mb-10">
                <li>✔ Feature 1</li>
                <li>✔ Feature 2</li>
                <li>✔ Feature 3</li>
              </ul>

              <form action="{{ route('subscription') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="price_id" value="{{ $price['id'] }}">

                <x-shared.primary-button 
                  type="submit" 
                  class="py-3 text-lg mt-4"
                >
                  Choose Plan
                </x-shared.primary-button>
              </form>
            </div>
          @endforeach
        @endforeach
      </div>
    </div>
  </div>
</section>

@endsection
