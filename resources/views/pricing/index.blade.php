@extends("layouts.layout")

@section("content")
    <section id="pricing" class="bg-white px-4 py-12 pt-20">
        <div class="container mx-auto text-center">
            <h2 class="mb-8 text-4xl font-bold">Pricing</h2>

            <div
                x-data="{ yearly: false }"
                class="flex flex-col items-center"
            >
                <div class="mb-8 flex justify-center">
                    <label class="flex cursor-pointer items-center gap-4">
                        <span class="text-lg font-semibold">Monthly</span>
                        <div
                            @click="yearly = !yearly"
                            class="relative h-6 w-12 rounded-full bg-gray-300 p-1"
                        >
                            <div
                                :class="yearly ? 'translate-x-6 bg-blue-600' : 'translate-x-0 bg-gray-500'"
                                class="absolute top-1 left-1 h-4 w-4 transform rounded-full transition-transform"
                            ></div>
                        </div>
                        <span class="text-lg font-semibold">Yearly</span>
                    </label>
                </div>

                <div class="flex justify-around gap-8" id="pricing-cards">
                    @foreach ($productsWithPrices as $product)
                        @foreach ($product->prices as $price)
                            <div
                                class="max-w-sm rounded-lg bg-white p-8 shadow-lg transition-transform hover:translate-y-[-10px] hover:shadow-lg"
                                x-show="{{ $price["interval"] === "year" ? "yearly" : "!yearly" }}"
                            >
                                <h3 class="mb-4 text-2xl font-semibold">
                                    {{ $product->name }}
                                </h3>
                                <p class="mb-4 text-xl">
                                    {{ number_format($price["unit_amount"] / 100, 2) }}
                                    {{ strtoupper($price["currency"]) }}
                                </p>
                                <ul class="mb-4 list-none">
                                    <li>Feature 1</li>
                                    <li>Feature 2</li>
                                    <li>Feature 3</li>
                                </ul>
                                <form
                                    action="{{ route("subscription") }}"
                                    method="POST"
                                >
                                    @csrf
                                    <input
                                        type="hidden"
                                        name="price_id"
                                        value="{{ $price["id"] }}"
                                    />
                                    <button
                                        type="submit"
                                        class="rounded-md bg-blue-600 px-4 py-3 text-white hover:bg-blue-700"
                                    >
                                        Choose Plan
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
