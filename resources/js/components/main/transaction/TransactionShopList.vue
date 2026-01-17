<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import { formatCurrency } from '@/lib/utils';
import { Transaction, TransactionShop } from '@/types/main/transaction';
import { ShoppingBag, Truck } from 'lucide-vue-next';

interface Props {
    transactionShops?: TransactionShop[];
}

defineProps<Props>();
</script>

<template>
    <div
        v-for="shopTx in transactionShops"
        :key="shopTx.id"
        class="rounded-lg border bg-card text-card-foreground shadow-sm"
    >
        <div class="flex items-center justify-between border-b bg-muted/30 p-4">
            <div class="flex items-center gap-2 font-semibold">
                <span class="text-lg">{{ shopTx.shop?.name }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <Truck class="h-4 w-4" />
                <span class="uppercase">{{ shopTx.courier }}</span>
                <span
                    v-if="shopTx.receipt_number"
                    class="ml-2 rounded bg-muted px-2 py-1 font-mono text-xs"
                >
                    {{ shopTx.receipt_number }}
                </span>
            </div>
        </div>
        <div class="space-y-4 p-4">
            <div
                v-for="detail in shopTx.transaction_details"
                :key="detail.id"
                class="flex gap-4"
            >
                <div
                    class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border bg-muted"
                >
                    <!-- Placeholder for product image if available in product_details -->
                    <img
                        v-if="detail.product_flat?.images_1"
                        :src="detail.product_flat.images_1"
                        :alt="detail.product_details?.name"
                        class="h-full w-full object-cover"
                    />
                    <div
                        v-else
                        class="flex h-full w-full items-center justify-center text-muted-foreground"
                    >
                        <ShoppingBag class="h-8 w-8 opacity-20" />
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium">
                        {{ detail.product_details?.name }}
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        Qty: {{ detail.quantity }}
                    </p>
                    <p class="mt-1 font-medium">
                        {{ formatCurrency(detail.price) }}
                    </p>
                </div>
                <div class="text-right font-medium">
                    {{ formatCurrency(detail.total) }}
                </div>
            </div>

            <Separator />

            <div class="flex justify-between text-sm">
                <span>Shipping Cost</span>
                <span>{{ formatCurrency(shopTx.shipping_cost) }}</span>
            </div>
            <div class="flex justify-between font-medium">
                <span>Shop Subtotal</span>
                <span>{{ formatCurrency(shopTx.total_amount) }}</span>
            </div>
        </div>
    </div>
</template>
