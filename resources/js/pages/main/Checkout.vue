<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { EmptyState } from '@/components/ui/empty-state';
import { Separator } from '@/components/ui/separator';
import { useCartStore } from '@/composables/useCartStore';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { formatCurrency } from '@/lib/utils';
import { explore } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingBag } from 'lucide-vue-next';
import { computed } from 'vue';

const cart = useCartStore();

const hasItems = computed(() => cart.items.value.length > 0);
</script>

<template>
    <Head title="Checkout" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="mb-8 text-3xl font-bold">Checkout</h1>

            <!-- Checkout Content -->
            <div v-if="hasItems" class="grid gap-8 lg:grid-cols-3">
                <!-- Checkout Form -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Shipping Information -->
                    <div class="rounded-lg border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">
                            Shipping Information
                        </h2>

                        <div class="grid gap-4">
                            <div class="grid gap-2">
                                <label class="text-sm font-medium"
                                    >Full Name</label
                                >
                                <input
                                    type="text"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Enter your full name"
                                />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Email</label>
                                <input
                                    type="email"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Enter your email"
                                />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium"
                                    >Phone Number</label
                                >
                                <input
                                    type="tel"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Enter your phone number"
                                />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium"
                                    >Address</label
                                >
                                <textarea
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Enter your full address"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label class="text-sm font-medium"
                                        >City</label
                                    >
                                    <input
                                        type="text"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="City"
                                    />
                                </div>

                                <div class="grid gap-2">
                                    <label class="text-sm font-medium"
                                        >Postal Code</label
                                    >
                                    <input
                                        type="text"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Postal code"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="rounded-lg border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">
                            Payment Method
                        </h2>

                        <div class="space-y-2">
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 hover:bg-muted/50"
                            >
                                <input
                                    type="radio"
                                    name="payment"
                                    value="cod"
                                    class="h-4 w-4"
                                    checked
                                />
                                <span class="font-medium"
                                    >Cash on Delivery (COD)</span
                                >
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 hover:bg-muted/50"
                            >
                                <input
                                    type="radio"
                                    name="payment"
                                    value="transfer"
                                    class="h-4 w-4"
                                />
                                <span class="font-medium">Bank Transfer</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary (Right Side) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 rounded-lg border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">
                            Order Summary
                        </h2>

                        <!-- Cart Items -->
                        <div class="mb-4 space-y-3">
                            <div
                                v-for="item in cart.items.value"
                                :key="item.id"
                                class="flex gap-3"
                            >
                                <div
                                    class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                                >
                                    <img
                                        :src="
                                            item.image ||
                                            'https://via.placeholder.com/150'
                                        "
                                        :alt="item.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">
                                        {{ item.name }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Qty: {{ item.quantity }}
                                    </p>
                                    <p class="text-sm font-semibold">
                                        {{
                                            formatCurrency(
                                                Number(item.price) *
                                                    item.quantity,
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <Separator class="my-4" />

                        <!-- Totals -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Subtotal</span
                                >
                                <span>{{
                                    formatCurrency(cart.subtotal.value)
                                }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Shipping</span
                                >
                                <span>Free</span>
                            </div>
                        </div>

                        <Separator class="my-4" />

                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span>{{ formatCurrency(cart.total.value) }}</span>
                        </div>

                        <Button class="mt-6 w-full" size="lg">
                            Place Order
                        </Button>

                        <Link
                            :href="explore.url()"
                            class="mt-4 block text-center text-sm text-muted-foreground hover:text-foreground"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-20">
                <EmptyState
                    title="Your cart is empty"
                    description="Add some products to your cart to proceed with checkout."
                    actionLabel="Continue Shopping"
                    @action="$inertia.visit(explore.url())"
                >
                    <template #icon>
                        <ShoppingBag class="h-12 w-12 text-muted-foreground" />
                    </template>
                </EmptyState>
            </div>
        </div>
    </ShopLayout>
</template>
