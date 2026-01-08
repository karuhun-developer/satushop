<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { index as shopIndex } from '@/routes/shop';
import { index as checkoutIndex } from '@/routes/checkout';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { EmptyState } from '@/components/ui/empty-state';
import { Trash2, ShoppingBag } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

// Mock Cart Data
const cartItems = ref([
    {
        id: 1,
        name: 'Premium Wireless Headphones',
        price: 2500000,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=200&h=200'
    },
    {
        id: 2,
        name: 'Minimalist Watch',
        price: 1200000,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=200&h=200'
    }
]);

const subtotal = 4900000;
const shipping = 0;
const total = 4900000;

const removeItem = (id: number) => {
    cartItems.value = cartItems.value.filter(item => item.id !== id);
};

const clearCart = () => {
    cartItems.value = [];
};
</script>

<template>
    <Head title="Your Cart" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8 md:py-12">
            <h1 class="text-3xl font-bold tracking-tight mb-8">Shopping Cart</h1>

            <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
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
                                    <div class="h-16 w-16 bg-muted rounded-md overflow-hidden">
                                        <img :src="item.image" :alt="item.name" class="object-cover w-full h-full" />
                                    </div>
                                </TableCell>
                                <TableCell class="font-medium">{{ item.name }}</TableCell>
                                <TableCell>{{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price) }}</TableCell>
                                <TableCell>{{ item.quantity }}</TableCell>
                                <TableCell class="text-right font-bold">
                                    {{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price * item.quantity) }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="icon" class="text-destructive" @click="removeItem(item.id)">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                     </Table> 
                     
                     <div class="flex justify-end">
                        <Button variant="link" class="text-destructive" @click="clearCart">Clear Cart</Button>
                     </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <Card class="sticky top-24">
                        <CardContent class="p-6 space-y-4">
                            <h3 class="font-semibold text-lg">Order Summary</h3>
                            <Separator />
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Subtotal</span>
                                <span>{{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <Separator />
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>{{ new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total) }}</span>
                            </div>
                            
                            <Link :href="checkoutIndex.url()" class="w-full">
                                <Button class="w-full mt-4" size="lg">Proceed to Checkout</Button>
                            </Link>

                            <p class="text-xs text-muted-foreground text-center mt-4">
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
                @action="$inertia.visit(shopIndex.url())"
             />
        </div>
    </ShopLayout>
</template>
