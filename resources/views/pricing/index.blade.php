@extends('layout')

@section('content')

    <section id="pricing" class="bg-white border-b-2 border-gray-200 py-12 px-4 pt-20">
      <div class="container mx-auto text-center">
        <h2 class="text-4xl font-bold mb-8">Pricing</h2>
        <div class="flex justify-around gap-8">
          @foreach ($productsWithPrices as $product)
            @foreach($product->prices as $price)
              <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm transition-transform hover:shadow-lg hover:translate-y-[-10px]">
                <h3 class="text-2xl font-semibold mb-4">{{ $product->name }}</h3>
                <p class="text-xl mb-4">{{ number_format($price->unit_amount / 100, 2)}} {{ strtoupper($price->currency)}}</p>
                <ul class="list-none mb-4">
                  <li>Feature 1</li>
                  <li>Feature 2</li>
                  <li>Feature 3</li>
                </ul>
                <form action="{{ route('subscription')}}" method="POST">
                  @csrf
                  <input type="hidden" name="price_id" value="{{ $price->id }}">
                  <button type="submit" class="bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700">Choose Plan</button>
                </form>
              </div>
            @endforeach
          @endforeach
        </div>
      </div>
    </section>
@endsection
