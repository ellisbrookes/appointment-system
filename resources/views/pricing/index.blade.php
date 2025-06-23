@extends("layouts.layout")

@section("content")
    <section id="pricing" class="py-16">
        <div class="container mx-auto text-center">
            <h2 class="mb-8 text-4xl font-bold">Pricing</h2>

            <!-- Toggle -->
            <div
                x-data="{ yearly: false }"
                class="flex flex-col items-center"
            >
                <div class="mb-12 flex justify-center">
                    <label class="flex cursor-pointer items-center gap-4">
                        <span class="text-lg font-semibold">Monthly</span>
                        <div
                            @click="yearly = !yearly"
                            class="relative h-6 w-12 rounded-full bg-gray-300 p-1 dark:bg-gray-700"
                        >
                            <div
                                :class="yearly ? 'translate-x-6 bg-gray-400' : 'translate-x-0 bg-gray-500'"
                                class="absolute top-1 left-1 h-4 w-4 transform rounded-full transition-transform"
                            ></div>
                        </div>
                        <span class="text-lg font-semibold">Yearly</span>
                    </label>
                </div>

                <!-- Cards -->
                <div
                    class="grid w-full max-w-6xl grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3"
                >
                    @foreach ($productsWithPrices as $product)
                        @foreach ($product->prices as $price)
                            <div
                                x-show="yearly === {{ $price["interval"] === "year" ? "true" : "false" }}"
                                class="flex flex-col items-center rounded-xl bg-white px-8 py-10 text-center shadow-md transition-all hover:-translate-y-2 hover:shadow-xl dark:bg-gray-800"
                            >
                                <h3 class="mb-3 text-2xl font-semibold">
                                    {{ $product->name }}
                                </h3>
                                <p class="mb-6 text-xl font-bold">
                                    {{ number_format($price["unit_amount"] / 100, 2) }}
                                    {{ strtoupper($price["currency"]) }}
                                </p>

                                <ul
                                    class="mb-10 space-y-2 text-gray-700 dark:text-gray-300"
                                >
                                    <li>✔ Feature 1</li>
                                    <li>✔ Feature 2</li>
                                    <li>✔ Feature 3</li>
                                </ul>

                                <form
                                    action="{{ route("subscription") }}"
                                    method="POST"
                                    class="w-full"
                                >
                                    @csrf
                                    <input
                                        type="hidden"
                                        name="price_id"
                                        value="{{ $price["id"] }}"
                                    />

                                    <x-shared.primary-button
                                        type="submit"
                                        class="mt-4 py-3 text-lg"
                                    >
                                        Choose Plan
                                    </x-shared.primary-button>
                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
          @endforeach
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
