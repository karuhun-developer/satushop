<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { EmptyState } from '@/components/ui/empty-state';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { explore } from '@/routes';
import { Head } from '@inertiajs/vue3';
import { ShoppingBag } from 'lucide-vue-next';
import { ref } from 'vue';

// Mock Cart Data State (normally passed from backend or store)
// Simulating data present for layout purpose.
// Toggle this variable to test Empty State
const hasItems = ref(true);
</script>

<template>
    <Head title="Checkout" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8 md:py-12">
            <h1 class="mb-8 text-3xl font-bold tracking-tight">Checkout</h1>

            <div v-if="hasItems" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Left Column: Shipping & Payment -->
                <div class="space-y-8 lg:col-span-2">
                    <!-- Shipping Address -->
                    <Card>
                        <CardContent class="space-y-4 p-6">
                            <h3 class="text-lg font-semibold">
                                Shipping Address
                            </h3>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label>First Name</Label>
                                    <Input placeholder="John" />
                                </div>
                                <div class="space-y-2">
                                    <Label>Last Name</Label>
                                    <Input placeholder="Doe" />
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <Label>Address</Label>
                                    <Input placeholder="123 Main St" />
                                </div>
                                <div class="space-y-2">
                                    <Label>City</Label>
                                    <Input placeholder="Jakarta" />
                                </div>
                                <div class="space-y-2">
                                    <Label>Postal Code</Label>
                                    <Input placeholder="10110" />
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <Label>Phone</Label>
                                    <Input placeholder="+62 8..." />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Payment Method -->
                    <Card>
                        <CardContent class="space-y-4 p-6">
                            <h3 class="text-lg font-semibold">
                                Payment Method
                            </h3>
                            <div class="space-y-2">
                                <div
                                    class="flex cursor-pointer items-center gap-4 rounded-md border border-primary bg-muted/50 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div
                                        class="h-4 w-4 rounded-full border-4 border-primary bg-background"
                                    ></div>
                                    <span class="font-medium">Credit Card</span>
                                </div>
                                <div
                                    class="flex cursor-pointer items-center gap-4 rounded-md border p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div
                                        class="h-4 w-4 rounded-full border border-input bg-background"
                                    ></div>
                                    <span class="font-medium"
                                        >Bank Transfer</span
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 pt-4">
                                <div class="space-y-2">
                                    <Label>Card Number</Label>
                                    <Input placeholder="0000 0000 0000 0000" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label>Expiry Date</Label>
                                        <Input placeholder="MM/YY" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label>CVC</Label>
                                        <Input placeholder="123" />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <Card class="sticky top-24">
                        <CardContent class="space-y-6 p-6">
                            <h3 class="text-lg font-semibold">Total</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground"
                                        >Subtotal</span
                                    >
                                    <span>Rp 4.900.000</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground"
                                        >Shipping</span
                                    >
                                    <span class="text-green-600">Free</span>
                                </div>
                                <Separator />
                                <div
                                    class="flex justify-between text-xl font-bold"
                                >
                                    <span>Total</span>
                                    <span>Rp 4.900.000</span>
                                </div>
                            </div>

                            <Button class="w-full" size="lg"
                                >Place Order</Button
                            >
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Empty State for Checkout -->
            <div v-else class="flex h-[60vh] items-center justify-center">
                <EmptyState
                    :icon="ShoppingBag"
                    title="Nothing to Checkout"
                    description="Your cart is empty. Please add items to your cart first."
                    actionLabel="Go to Shop"
                    @action="$inertia.visit(explore.url())"
                />
            </div>
        </div>
    </ShopLayout>
</template>
