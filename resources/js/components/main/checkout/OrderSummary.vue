<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { useCartStore } from '@/composables/useCartStore';
import { formatCurrency } from '@/lib/utils';
import { explore } from '@/routes';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import OrderSummaryShopGroup from './OrderSummaryShopGroup.vue';

interface OrderSummaryProps {
    processing?: boolean;
    destinationDistrictId?: number | null;
}

const props = defineProps<OrderSummaryProps>();

const cart = useCartStore();

const shippingSelections = ref<Record<number, any>>({});

const handleShippingSelection = (payload: any) => {
    shippingSelections.value[payload.shopId] = payload;
};

// Check if all shops have shipping selected (if destination is set)
const canPlaceOrder = computed(() => {
    if (!props.destinationDistrictId) return false; // Must have address

    // Check if cart has items
    if (Object.keys(cart.groupedItems.value).length === 0) return false;

    const shopIds = Object.keys(cart.groupedItems.value).map(Number);
    // Ensure every shop has a shipping cost set (ignoring type check for demo, cost >= 0)
    return shopIds.every((id) => {
        const sel = shippingSelections.value[id];
        return sel && sel.courier !== null;
    });
});

const totalShipping = computed(() => {
    return Object.values(shippingSelections.value).reduce(
        (acc: number, curr: any) => acc + (curr.cost || 0),
        0,
    );
});

const grandTotal = computed(() => {
    return cart.subtotal.value + totalShipping.value;
});
</script>

<template>
    <div class="sticky top-4 space-y-6">
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">Order Summary</h2>

            <!-- Group items by Shop -->
            <OrderSummaryShopGroup
                v-for="(group, shopId) in cart.groupedItems.value"
                :key="`${shopId}-${destinationDistrictId}`"
                :shop-id="Number(shopId)"
                :shop-name="group.shop_name"
                :items="group.items"
                :destination-district-id="destinationDistrictId"
                @selection="handleShippingSelection"
            />

            <!-- Totals -->
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Subtotal</span>
                    <span>{{ formatCurrency(cart.subtotal.value) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Total Shipping</span>
                    <span>{{ formatCurrency(totalShipping) }}</span>
                </div>
            </div>

            <Separator class="my-4" />

            <div class="flex justify-between text-lg font-semibold">
                <span>Total</span>
                <span>{{ formatCurrency(grandTotal) }}</span>
            </div>

            <Button
                class="mt-6 w-full"
                size="lg"
                :disabled="processing || !canPlaceOrder"
            >
                {{ processing ? 'Processing...' : 'Place Order' }}
            </Button>

            <Link
                :href="explore.url()"
                class="mt-4 block text-center text-sm text-muted-foreground hover:text-foreground"
            >
                Continue Shopping
            </Link>
        </div>
    </div>
</template>
