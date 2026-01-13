<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { useCartStore } from '@/composables/useCartStore';
import { formatCurrency } from '@/lib/utils';
import { explore } from '@/routes';
import { Link } from '@inertiajs/vue3';

interface OrderSummaryProps {
    processing?: boolean;
}

defineProps<OrderSummaryProps>();

const cart = useCartStore();
</script>

<template>
    <div class="sticky top-4 rounded-lg border bg-card p-6">
        <h2 class="mb-4 text-xl font-semibold">Order Summary</h2>

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
                        :src="item.image || 'https://via.placeholder.com/150'"
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
                            formatCurrency(Number(item.price) * item.quantity)
                        }}
                    </p>
                </div>
            </div>
        </div>

        <Separator class="my-4" />

        <!-- Totals -->
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Subtotal</span>
                <span>{{ formatCurrency(cart.subtotal.value) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Shipping</span>
                <span>Free</span>
            </div>
        </div>

        <Separator class="my-4" />

        <div class="flex justify-between text-lg font-semibold">
            <span>Total</span>
            <span>{{ formatCurrency(cart.total.value) }}</span>
        </div>

        <Button class="mt-6 w-full" size="lg" :disabled="processing">
            {{ processing ? 'Processing...' : 'Place Order' }}
        </Button>

        <Link
            :href="explore.url()"
            class="mt-4 block text-center text-sm text-muted-foreground hover:text-foreground"
        >
            Continue Shopping
        </Link>
    </div>
</template>
