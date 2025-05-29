@extends('layouts.layout')

@section('content')

  <section id="pricing" class="bg-white py-12 px-4 pt-20">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-bold mb-8">Pricing</h2>

      <div x-data="{ yearly: false }" class="flex flex-col items-center">
        <div class="flex justify-center mb-8">
          <label class="flex items-center gap-4 cursor-pointer">
            <span class="text-lg font-semibold">Monthly</span>
            <div @click="yearly = !yearly" class="relative w-12 h-6 bg-gray-300 rounded-full p-1">
              <div 
                :class="yearly ? 'translate-x-6 bg-blue-600' : 'translate-x-0 bg-gray-500'" 
                class="absolute left-1 top-1 w-4 h-4 rounded-full transition-transform transform"
              ></div>
            </div>
            <span class="text-lg font-semibold">Yearly</span>
          </label>
        </div>

        <div class="flex justify-around gap-8" id="pricing-cards">
          @foreach ($productsWithPrices as $product)
            @foreach($product->prices as $price)
              <div 
                class="bg-white rounded-lg shadow-lg p-8 max-w-sm transition-transform hover:shadow-lg hover:translate-y-[-10px]" 
                x-show="{{ $price['interval'] === 'year' ? 'yearly' : '!yearly' }}"
              >
                <h3 class="text-2xl font-semibold mb-4">{{ $product->name }}</h3>
                <p class="text-xl mb-4">{{ number_format($price['unit_amount'] / 100, 2) }} {{ strtoupper($price['currency']) }}</p>
                <ul class="list-none mb-4">
                  <li>Feature 1</li>
                  <li>Feature 2</li>
                  <li>Feature 3</li>
                </ul>
                <form action="{{ route('subscription') }}" method="POST">
                  @csrf
                  <input type="hidden" name="price_id" value="{{ $price['id'] }}">
                  <button type="submit" class="bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700">Choose Plan</button>
                </form>
              </div>
            @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endsection