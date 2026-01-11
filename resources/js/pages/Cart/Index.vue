<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { EmptyState } from '@/components/ui/empty-state';
import { Separator } from '@/components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { useCartStore } from '@/composables/useCartStore';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { formatCurrency } from '@/lib/utils';
import { explore } from '@/routes';
import { index as checkoutIndex } from '@/routes/checkout';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingBag, Trash2 } from 'lucide-vue-next';

const cart = useCartStore();

const {
    items: cartItems,
    subtotal,
    total,
    removeItem,
    updateQuantity,
    clearCart,
} = cart;
</script>

<template>
    <Head title="Your Cart" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8 md:py-12">
            <h1 class="mb-8 text-3xl font-bold tracking-tight">
                Shopping Cart
            </h1>

            <div
                v-if="cartItems.length > 0"
                class="grid grid-cols-1 gap-8 lg:grid-cols-3"
            >
                <!-- Cart Items -->
                <div class="space-y-4 lg:col-span-2">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px]">Product</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Price</TableHead>
                                <TableHead>Qty</TableHead>
                                <TableHead class="text-right">Total</TableHead>
                                <TableHead></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in cartItems" :key="item.id">
                                <TableCell>
                                    <div
                                        class="h-16 w-16 overflow-hidden rounded-md bg-muted"
                                    >
                                        <img
                                            :src="item.image"
                                            :alt="item.name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                </TableCell>
                                <TableCell class="font-medium">
                                    {{ item.name }}
                                    <div
                                        class="mt-1 text-xs text-muted-foreground"
                                    >
                                        <span v-if="item.options?.color"
                                            >Color:
                                            {{ item.options.color }}</span
                                        >
                                        <span
                                            v-if="item.options?.size"
                                            class="ml-2"
                                            >Size: {{ item.options.size }}</span
                                        >
                                    </div>
                                </TableCell>
                                <TableCell>{{
                                    formatCurrency(item.price)
                                }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button
                                            variant="outline"
                                            size="icon"
                                            class="h-6 w-6"
                                            @click="
                                                updateQuantity(
                                                    item.id,
                                                    item.quantity - 1,
                                                )
                                            "
                                            :disabled="item.quantity <= 1"
                                        >
                                            -
                                        </Button>
                                        <span class="w-4 text-center">{{
                                            item.quantity
                                        }}</span>
                                        <Button
                                            variant="outline"
                                            size="icon"
                                            class="h-6 w-6"
                                            @click="
                                                updateQuantity(
                                                    item.id,
                                                    item.quantity + 1,
                                                )
                                            "
                                        >
                                            +
                                        </Button>
                                    </div>
                                </TableCell>
                                <TableCell class="text-right font-bold">
                                    {{
                                        formatCurrency(
                                            item.price * item.quantity,
                                        )
                                    }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="text-destructive"
                                        @click="removeItem(item.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <div class="flex justify-end">
                        <Button
                            variant="link"
                            class="text-destructive"
                            @click="clearCart"
                            >Clear Cart</Button
                        >
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <Card class="sticky top-24">
                        <CardContent class="space-y-4 p-6">
                            <h3 class="text-lg font-semibold">Order Summary</h3>
                            <Separator />
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Subtotal</span
                                >
                                <span>{{ formatCurrency(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Shipping</span
                                >
                                <span class="text-green-600">Free</span>
                            </div>
                            <Separator />
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>{{ formatCurrency(total) }}</span>
                            </div>

                            <Link :href="checkoutIndex.url()" class="w-full">
                                <Button class="mt-4 w-full" size="lg"
                                    >Proceed to Checkout</Button
                                >
                            </Link>

                            <p
                                class="mt-4 text-center text-xs text-muted-foreground"
                            >
                                Secure Checkout - SSL Encrypted
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Empty State -->
            <EmptyState
                v-else
                :icon="ShoppingBag"
                title="Your cart is empty"
                description="Looks like you haven't added anything to your cart yet."
                actionLabel="Continue Shopping"
                @action="$inertia.visit(explore.url())"
            />
        </div>
    </ShopLayout>
</template>
