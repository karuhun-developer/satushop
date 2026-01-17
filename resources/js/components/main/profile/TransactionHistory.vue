<script setup lang="ts">
import { show } from '@/actions/App/Http/Controllers/Main/TransactionController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { PaginationItem } from '@/types';
import { Transaction } from '@/types/main/transaction';
import { Link, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Package } from 'lucide-vue-next';

interface TransactionHistoryProps {
    transactions: PaginationItem<Transaction>;
}

defineProps<TransactionHistoryProps>();

const onPageClick = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, preserveScroll: true });
    }
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (date: string) => {
    return dayjs(date).format('DD MMMM YYYY');
};

// Status Enum Mapping (matching Show.vue)
const PaymentStatus = {
    PENDING: 0,
    CAPTURED: 1,
    SETTLEMENT: 2,
    DENY: -1,
    EXPIRED: -2,
    CANCEL: -3,
};

const getStatusVariant = (status: number) => {
    switch (status) {
        case PaymentStatus.CAPTURED:
        case PaymentStatus.SETTLEMENT:
            return 'default';
        case PaymentStatus.PENDING:
            return 'secondary';
        case PaymentStatus.EXPIRED:
        case PaymentStatus.CANCEL:
        case PaymentStatus.DENY:
            return 'destructive';
        default:
            return 'outline';
    }
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

const getItemsCount = (transaction: Transaction) => {
    return transaction.transaction_details_count || 0;
};
</script>

<template>
    <div class="rounded-lg border bg-card">
        <div class="p-6">
            <h2 class="mb-4 text-xl font-semibold">Transaction History</h2>

            <div v-if="transactions.data.length > 0" class="space-y-4">
                <Link
                    v-for="transaction in transactions.data"
                    :key="transaction.id"
                    :href="show(transaction.id).url"
                    class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-accent"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10"
                        >
                            <Package class="h-6 w-6 text-primary" />
                        </div>
                        <div>
                            <p class="font-semibold">
                                {{ transaction.reference }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ formatDate(transaction.created_at) }}
                            </p>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ getItemsCount(transaction) }} item{{
                                    getItemsCount(transaction) !== 1 ? 's' : ''
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">
                            {{ formatCurrency(transaction.total_amount) }}
                        </p>
                        <Badge
                            :variant="getStatusVariant(transaction.status)"
                            class="mt-2"
                        >
                            {{ getStatusLabel(transaction.status) }}
                        </Badge>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div
                class="flex items-center justify-between space-x-2 py-4"
                v-if="transactions.data.length > 0"
            >
                <div class="text-sm text-muted-foreground">
                    Showing {{ transactions.from ?? 0 }} to
                    {{ transactions.to ?? 0 }} of
                    {{ transactions.total }} results
                </div>
                <div class="flex items-center space-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!transactions.prev_page_url"
                        @click="onPageClick(transactions.prev_page_url)"
                    >
                        Previous
                    </Button>
                    <div class="hidden gap-1 md:flex">
                        <template
                            v-for="(link, i) in transactions.links.slice(1, -1)"
                            :key="i"
                        >
                            <Button
                                v-if="link.url || link.label === '...'"
                                :variant="link.active ? 'default' : 'outline'"
                                size="sm"
                                :disabled="!link.url"
                                @click="onPageClick(link.url)"
                            >
                                <span v-html="link.label"></span>
                            </Button>
                        </template>
                    </div>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!transactions.next_page_url"
                        @click="onPageClick(transactions.next_page_url)"
                    >
                        Next
                    </Button>
                </div>
            </div>

            <div
                v-if="transactions.data.length === 0"
                class="py-12 text-center text-muted-foreground"
            >
                <Package class="mx-auto h-12 w-12 opacity-50" />
                <p class="mt-2">No transactions yet</p>
            </div>
        </div>
    </div>
</template>
