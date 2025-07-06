@extends("layouts.main")

@section("content")
    {{-- Hero Section --}}
    <div class="border-b border-gray-400 py-24 text-center dark:border-white">
        <h1 class="text-4xl font-bold">Welcome to Skedulaa</h1>
        <p class="mt-6 text-lg">
            Your reliable tool for managing appointments effectively.
        </p>
    </div>

    {{-- Features Section --}}
    <section
        id="features"
        class="border-b border-gray-400 py-24 dark:border-white"
    >
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-12 text-4xl font-bold">Features</h2>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (["Feature 1", "Feature 2", "Feature 3", "Feature 4"] as $feature)
                    <div class="rounded-2xl p-6">
                        <h3 class="mb-3 text-xl font-semibold">
                            {{ $feature }}
                        </h3>
                        <p>Description of {{ strtolower($feature) }}.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing Preview Section --}}
    <section
        id="homepage-pricing"
        class="border-b border-gray-400 py-24 dark:border-white dark:bg-gray-900"
    >
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-8 text-4xl font-bold">Plans That Suit You</h2>

            <div
                class="mx-auto grid max-w-6xl grid-cols-1 gap-10 md:grid-cols-3"
            >
                @foreach ($productsWithPrices as $product)
                    @php
                        $price = collect($product->prices)->firstWhere("interval", "month") ?? $product->prices[0];
                    @endphp

                    <div
                        class="flex flex-col items-center rounded-xl bg-white px-6 py-10 text-center shadow-md transition hover:shadow-lg dark:bg-gray-800"
                    >
                        <h3 class="mb-3 text-2xl font-semibold">
                            {{ $product->name }}
                        </h3>
                        <p class="mb-6 text-xl font-bold">
                            {{ number_format($price["unit_amount"] / 100, 2) }}
                            {{ strtoupper($price["currency"]) }}/mo
                        </p>

                        <ul
                            class="mb-8 space-y-2 text-gray-700 dark:text-gray-300"
                        >
                            <li>✔ Feature 1</li>
                            <li>✔ Feature 2</li>
                            <li>✔ Feature 3</li>
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                <a
                    href="{{ route("pricing") }}"
                    class="text-lg hover:underline"
                >
                    See full pricing & plans →
                </a>
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section
        id="statistics"
        class="border-b border-gray-400 py-24 dark:border-white"
    >
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-12 text-4xl font-bold">Our Achievements</h2>

            @php
                $stats = [
                    ["Users", number_format($userCount), "users"],
                    ["Appointments", number_format($appointmentCount), "calendar-check"],
                ];
            @endphp

            <div class="flex flex-wrap justify-center gap-12">
                @foreach ($stats as $stat)
                    <div class="p-8">
                        <i class="fas fa-{{ $stat[2] }} fa-3x mb-4"></i>
                        <h3 class="mb-2 text-3xl font-semibold">
                            {{ $stat[1] }}
                        </h3>
                        <p class="text-lg">{{ $stat[0] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="contact" class="px-4 py-24">
        <div class="container mx-auto text-center">
            <h2 class="mb-4 text-4xl font-bold">Contact Us</h2>
            <p class="mb-10 text-lg">
                Have questions or need support? We're here to help!
            </p>

            <form class="mx-auto flex max-w-md flex-col gap-6">
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

                <x-shared.primary-button type="submit">
                    Send Message
                </x-shared.primary-button>
            </form>
        </div>
    </section>
@endsection
