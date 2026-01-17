<script setup lang="ts">
import TransactionOrderSummary from '@/components/main/transaction/TransactionOrderSummary.vue';
import TransactionPaymentDetails from '@/components/main/transaction/TransactionPaymentDetails.vue';
import TransactionShippingAddress from '@/components/main/transaction/TransactionShippingAddress.vue';
import TransactionShopList from '@/components/main/transaction/TransactionShopList.vue';
import { Badge } from '@/components/ui/badge';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Transaction } from '@/types/main/transaction';
import { Head, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

interface Props {
    transaction: Transaction;
}

const props = defineProps<Props>();

// Status Enum Mapping for Badge Color
const PaymentStatus = {
    PENDING: 0,
    CAPTURED: 1,
    SETTLEMENT: 2,
    DENY: -1,
    EXPIRED: -2,
    CANCEL: -3,
};

const getStatusLabel = (status: number) => {
    switch (status) {
        case PaymentStatus.PENDING:
            return 'Pending';
        case PaymentStatus.CAPTURED:
            return 'Captured';
        case PaymentStatus.SETTLEMENT:
            return 'Settlement';
        case PaymentStatus.DENY:
            return 'Denied';
        case PaymentStatus.EXPIRED:
            return 'Expired';
        case PaymentStatus.CANCEL:
            return 'Cancelled';
        default:
            return 'Unknown';
    }
};

const getStatusColor = (status: number) => {
    switch (status) {
        case PaymentStatus.CAPTURED:
        case PaymentStatus.SETTLEMENT:
            return 'default'; // Greenish
        case PaymentStatus.PENDING:
            return 'secondary'; // Yellowish/Grey
        case PaymentStatus.EXPIRED:
        case PaymentStatus.CANCEL:
        case PaymentStatus.DENY:
            return 'destructive'; // Red
        default:
            return 'outline';
    }
};

const formatDate = (dateString: string) => {
    return dayjs(dateString).format('dddd, D MMMM YYYY HH:mm');
};

const goHome = () => {
    router.visit('/');
};
</script>

<template>
    <Head title="Transaction Details" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <div
                class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1 class="text-2xl font-bold">Transaction Details</h1>
                    <p
                        class="mt-1 flex items-center gap-2 text-muted-foreground"
                    >
                        Ref:
                        <span class="font-mono font-medium">
                            {{ transaction.reference }}
                        </span>
                        <Badge
                            :variant="getStatusColor(transaction.status)"
                            class="capitalize"
                        >
                            {{ getStatusLabel(transaction.status) }}
                        </Badge>
                    </p>
                </div>
                <div class="text-right text-sm text-muted-foreground">
                    {{ formatDate(transaction.created_at) }}
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left Column: Order Details -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Products by Shop -->
                    <TransactionShopList
                        :transactionShops="transaction.transaction_shops"
                    />

                    <!-- Shipping Address -->
                    <TransactionShippingAddress :transaction="transaction" />
                </div>

                <!-- Right Column: Payment & Summary -->
                <div class="space-y-6">
                    <!-- Payment Status/Action -->
                    <TransactionPaymentDetails :transaction="transaction" />

                    <!-- Order Summary -->
                    <TransactionOrderSummary :transaction="transaction" />

                    <div class="text-center">
                        <Button
                            variant="outline"
                            class="w-full"
                            @click="goHome"
                        >
                            Back to Home
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>
