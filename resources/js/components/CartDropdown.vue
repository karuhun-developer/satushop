<script setup lang="ts">
import { index as cart } from '@/actions/App/Http/Controllers/Main/CartController';
import { index as checkout } from '@/actions/App/Http/Controllers/Main/CheckoutController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useCartStore } from '@/composables/useCartStore';
import { Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

const { items: cartItems, total: cartTotal } = useCartStore();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(price);
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="relative">
                <ShoppingCart class="h-5 w-5" />
                <Badge
                    v-if="cartItems.length > 0"
                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center p-0 text-[10px]"
                >
                    {{ cartItems.length }}
                </Badge>
                <span class="sr-only">Open cart</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-80 p-0">
            <DropdownMenuLabel class="p-4 font-normal">
                <div class="flex flex-col space-y-1">
                    <p class="text-sm leading-none font-medium">
                        Shopping Cart
                    </p>
                    <p class="text-xs leading-none text-muted-foreground">
                        {{ cartItems.length }} items
                    </p>
                </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />

            <!-- Items -->
            <div class="max-h-[300px] space-y-4 overflow-y-auto p-4">
                <div
                    v-for="item in cartItems"
                    :key="item.id"
                    class="flex gap-4"
                >
                    <div
                        class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                    >
                        <img
                            :src="item.image"
                            :alt="item.name"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <div class="min-w-0 flex-1 space-y-1">
                        <p class="truncate text-sm leading-none font-medium">
                            {{ item.name }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            {{ item.quantity }} x
                            {{ formatPrice(item.price) }}
                        </p>
                    </div>
                </div>
                <div
                    v-if="cartItems.length === 0"
                    class="py-6 text-center text-sm text-muted-foreground"
                >
                    Your cart is empty
                </div>
            </div>

            <DropdownMenuSeparator v-if="cartItems.length > 0" />

            <!-- Footer -->
            <div v-if="cartItems.length > 0" class="space-y-4 p-4 pt-2">
                <div class="flex justify-between text-sm font-medium">
                    <span>Total</span>
                    <span>{{ formatPrice(cartTotal) }}</span>
                </div>
                <div class="grid gap-2">
                    <DropdownMenuItem as-child>
                        <Link :href="cart.url()" class="w-full cursor-pointer">
                            <Button variant="outline" class="w-full" size="sm"
                                >View Cart</Button
                            >
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem as-child>
                        <Link
                            :href="checkout.url()"
                            class="w-full cursor-pointer"
                        >
                            <Button class="w-full" size="sm">Checkout</Button>
                        </Link>
                    </DropdownMenuItem>
                </div>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
