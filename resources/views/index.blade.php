@extends('layouts.layout')

@section('content')

  {{-- Hero Section --}}
  <div class="py-24 text-center border-b border-gray-400 dark:border-white">
    <h1 class="text-4xl font-bold">Welcome to Appointment System</h1>
    <p class="mt-6 text-lg">
      Your reliable tool for managing appointments effectively.
    </p>
  </div>

  {{-- Features Section --}}
  <section id="features" class="py-24 border-b border-gray-400 dark:border-white">
    <div class="container mx-auto text-center px-4">
      <h2 class="text-4xl font-bold mb-12">Features</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach (['Feature 1', 'Feature 2', 'Feature 3', 'Feature 4'] as $feature)
          <div class="rounded-2xl p-6">
            <h3 class="text-xl font-semibold mb-3">{{ $feature }}</h3>
            <p>
              Description of {{ strtolower($feature) }}.
            </p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Pricing Preview Section --}}
  <section id="homepage-pricing" class="py-24 border-b border-gray-400 dark:border-white bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto text-center px-4">
      <h2 class="text-4xl font-bold mb-8">Plans That Suit You</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
        @foreach ($productsWithPrices as $product)
          @php
            // Show only one price (e.g. monthly) for preview
            $price = collect($product->prices)->firstWhere('interval', 'month') ?? $product->prices[0];
          @endphp

          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md px-6 py-10 flex flex-col items-center text-center hover:shadow-lg transition">
            <h3 class="text-2xl font-semibold mb-3">{{ $product->name }}</h3>
            <p class="text-xl font-bold mb-6">
              {{ number_format($price['unit_amount'] / 100, 2) }} {{ strtoupper($price['currency']) }}/mo
            </p>

            <ul class="space-y-2 text-gray-700 dark:text-gray-300 mb-8">
              <li>✔ Feature 1</li>
              <li>✔ Feature 2</li>
              <li>✔ Feature 3</li>
            </ul>
          </div>
        @endforeach
      </div>

      <div class="mt-12">
        <a href="{{ route('pricing') }}" class="hover:underline text-lg">See full pricing & plans →</a>
      </div>
    </div>
  </section>

  {{-- Statistics Section --}}
  <section id="statistics" class="py-24 border-b border-gray-400 dark:border-white">
    <div class="container mx-auto text-center px-4">
      <h2 class="text-4xl font-bold mb-12">Our Achievements</h2>

      @php
        $stats = [
          ['Users', number_format($userCount), 'users'],
          ['Appointments', number_format($appointmentCount), 'calendar-check'],
        ];
      @endphp

      <div class="flex flex-wrap justify-center gap-12">
        @foreach ($stats as $stat)
          <div class="p-8">
            <i class="fas fa-{{ $stat[2] }} fa-3x mb-4"></i>
            <h3 class="text-3xl font-semibold mb-2">{{ $stat[1] }}</h3>
            <p class="text-lg">{{ $stat[0] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Contact Section --}}
  <section id="contact" class="py-24 px-4">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-bold mb-4">Contact Us</h2>
      <p class="text-lg mb-10">
        Have questions or need support? We're here to help!
      </p>

      <form class="max-w-md mx-auto flex flex-col gap-6">
        <x-shared.text-input 
          type="text" 
          name="name" 
          placeholder="Your Name" 
          required 
        />

        <x-shared.text-input 
          type="email" 
          name="email" 
          placeholder="Your Email" 
          required 
        />

        <x-shared.text-input
          name="message" 
          placeholder="Your Message" 
          rows="5" 
          required 
          class="resize-none"
        ></x-shared.text-input>

        <x-shared.primary-button 
          type="submit"
        >
          Send Message
        </x-shared.primary-button>
      </form>
    </div>
  </section>

@endsection
