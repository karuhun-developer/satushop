<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Package } from 'lucide-vue-next';

interface Transaction {
    id: string;
    date: string;
    total: number;
    status: string;
    items_count: number;
}

interface TransactionHistoryProps {
    transactions: Transaction[];
}

defineProps<TransactionHistoryProps>();

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const getStatusVariant = (status: string) => {
    switch (status) {
        case 'completed':
            return 'default';
        case 'processing':
            return 'secondary';
        case 'cancelled':
            return 'destructive';
        default:
            return 'outline';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'completed':
            return 'Completed';
        case 'processing':
            return 'Processing';
        case 'cancelled':
            return 'Cancelled';
        default:
            return status;
    }
};
</script>

<template>
    <div class="rounded-lg border bg-card">
        <div class="p-6">
            <h2 class="mb-4 text-xl font-semibold">Transaction History</h2>

            <div v-if="transactions.length > 0" class="space-y-4">
                <div
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-accent"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Package class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <p class="font-semibold">{{ transaction.id }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ formatDate(transaction.date) }}
                            </p>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ transaction.items_count }} item{{
                                    transaction.items_count > 1 ? 's' : ''
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">
                            {{ formatCurrency(transaction.total) }}
                        </p>
                        <Badge
                            :variant="getStatusVariant(transaction.status)"
                            class="mt-2"
                        >
                            {{ getStatusLabel(transaction.status) }}
                        </Badge>
                    </div>
                </div>
            </div>

            <div
                v-else
                class="py-12 text-center text-muted-foreground"
            >
                <Package class="mx-auto h-12 w-12 opacity-50" />
                <p class="mt-2">No transactions yet</p>
            </div>
        </div>
    </div>
</template>
