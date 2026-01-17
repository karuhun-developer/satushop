<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { formatCurrency } from '@/lib/utils';
import { Transaction, TransactionShop } from '@/types/main/transaction';

interface Props {
    transaction: Transaction;
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Order Summary</CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Total Weight</span>
                <span>-</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground"
                    >Total Price ({{
                        transaction.transaction_shops?.reduce(
                            (acc: number, shop: TransactionShop) =>
                                acc + (shop.transaction_details?.length || 0),
                            0,
                        )
                    }}
                    items)</span
                >
                <span>{{ formatCurrency(transaction.amount) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-muted-foreground">Total Shipping</span>
                <span>{{ formatCurrency(transaction.shipping_cost) }}</span>
            </div>
            <!-- Discount/Voucher placeholder -->

            <Separator />

            <div class="flex justify-between text-lg font-bold">
                <span>Grand Total</span>
                <span class="text-primary">{{
                    formatCurrency(transaction.total_amount)
                }}</span>
            </div>
        </CardContent>
    </Card>
</template>
