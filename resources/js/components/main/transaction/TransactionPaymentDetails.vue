<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Main/TransactionController';
import ImageUploadPreview from '@/components/ImageUploadPreview.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Label from '@/components/ui/label/Label.vue';
import { Separator } from '@/components/ui/separator';
import { useSwal } from '@/composables/useSwal';
import { Transaction } from '@/types/main/transaction';
import { Form } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
import relativeTime from 'dayjs/plugin/relativeTime';
import { CheckCircle, Clock, CreditCard, Upload } from 'lucide-vue-next';
import { computed } from 'vue';

dayjs.extend(relativeTime);
dayjs.extend(duration);

interface Props {
    transaction: Transaction;
}

const props = defineProps<Props>();

const { toast } = useSwal();

// Status Enum Mapping (Keep localized here or move to shared constant if used elsewhere, but fine here for now)
const PaymentStatus = {
    PENDING: 0,
    CAPTURED: 1,
    SETTLEMENT: 2,
    DENY: -1,
    EXPIRED: -2,
    CANCEL: -3,
};

const remainingTime = computed(() => {
    if (!props.transaction.payment?.expired_at) return null;
    const expiry = dayjs(props.transaction.payment.expired_at);
    const now = dayjs();
    const diff = expiry.diff(now);

    if (diff <= 0) return null;

    const duration = dayjs.duration(diff);
    return `${Math.floor(duration.asHours())}h ${duration.minutes()}m`;
});
</script>

<template>
    <!-- Payment Status/Action -->
    <Card
        v-if="transaction.status === PaymentStatus.PENDING"
        class="border-primary/50 shadow-md"
    >
        <CardHeader class="bg-primary/5 pb-4">
            <CardTitle class="flex items-center gap-2 text-lg text-primary">
                <Clock class="h-5 w-5" /> Waiting for Payment
            </CardTitle>
            <CardDescription
                v-if="remainingTime"
                class="font-medium text-primary/80"
            >
                Expires in {{ remainingTime }}
            </CardDescription>
        </CardHeader>
        <CardContent class="pt-6">
            <!-- Midtrans Payment -->
            <div
                v-if="transaction.payment_method === 'midtrans'"
                class="space-y-4"
            >
                <!-- VA Payment -->
                <div
                    v-if="transaction.payment?.payment_type === 'bank_transfer'"
                    class="space-y-4"
                >
                    <div class="text-center">
                        <p class="mb-1 text-sm text-muted-foreground">
                            Bank Transfer
                        </p>
                        <div class="text-xl font-bold uppercase">
                            {{ transaction.payment.channel }}
                        </div>
                    </div>
                    <div
                        class="rounded-lg border-2 border-dashed bg-muted p-4 text-center"
                    >
                        <p class="mb-1 text-xs text-muted-foreground">
                            Virtual Account Number
                        </p>
                        <p
                            class="font-mono text-2xl font-bold tracking-wider select-all"
                        >
                            {{ transaction.payment.account_number }}
                        </p>
                    </div>
                    <div class="text-center text-sm text-muted-foreground">
                        Please transfer the exact amount to the Virtual Account
                        above.
                    </div>
                </div>

                <!-- QRIS Payment -->
                <div
                    v-else-if="transaction.payment?.payment_type === 'qris'"
                    class="space-y-4 text-center"
                >
                    <p class="font-semibold">Scan QRIS to Pay</p>
                    <div
                        class="inline-block rounded-lg border bg-white p-4 shadow-sm"
                    >
                        <!-- Assuming account_number is the QR URL -->
                        <img
                            :src="transaction.payment.account_number || ''"
                            alt="QRIS Code"
                            class="h-64 w-64 object-contain"
                        />
                    </div>
                    <!-- Logos -->
                    <div class="mt-2 flex justify-center gap-2">
                        <img
                            src="/images/qris-logo.png"
                            alt="QRIS"
                            class="h-6 opacity-80"
                        />
                    </div>
                </div>
            </div>

            <!-- Manual Payment -->
            <div
                v-else-if="transaction.payment_method === 'bank_transfer'"
                class="space-y-6"
            >
                <div class="space-y-3">
                    <h4 class="flex items-center gap-2 font-semibold">
                        <CreditCard class="h-4 w-4" /> Bank Transfer Details
                    </h4>
                    <div class="space-y-1 rounded-md bg-muted p-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Bank:</span>
                            <span class="font-medium">BCA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Account:</span>
                            <span class="font-medium">1234567890</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Name:</span>
                            <span class="font-medium">PT. E-Commerce</span>
                        </div>
                    </div>
                </div>

                <Separator />

                <div class="space-y-3">
                    <h4 class="flex items-center gap-2 font-semibold">
                        <Upload class="h-4 w-4" /> Upload Proof
                    </h4>
                    <Form
                        v-bind="
                            update.put({
                                transaction: transaction.id,
                            })
                        "
                        :validate="false"
                        @success="
                            () => {
                                toast.fire({
                                    icon: 'success',
                                    title: 'Payment proof uploaded successfully!',
                                });
                            }
                        "
                        v-slot="{ errors, processing }"
                        class="space-y-3"
                    >
                        <div class="grid gap-2">
                            <Label for="proof"> Payment Proof (Image) </Label>
                            <ImageUploadPreview
                                input-id="payment_proof"
                                input-name="payment_proof"
                                label=""
                                description="Upload your payment proof image here."
                                accept="image/*"
                                :max-size="5"
                                preview-height="200px"
                                :errors="errors.payment_proof"
                                :initial-preview="transaction.payment_proof"
                            />
                        </div>
                        <Button
                            type="submit"
                            class="w-full"
                            :disabled="processing"
                        >
                            {{ processing ? 'Uploading...' : 'Submit Payment' }}
                        </Button>
                    </Form>
                </div>
            </div>

            <div v-else class="p-4 text-center">
                <p>Payment details not available.</p>
            </div>
        </CardContent>
    </Card>

    <!-- Payment Success State -->
    <Card
        v-else-if="
            transaction.status === PaymentStatus.CAPTURED ||
            transaction.status === PaymentStatus.SETTLEMENT
        "
        class="border-green-200 bg-green-50/50 dark:border-green-800 dark:bg-green-900/10"
    >
        <CardContent class="space-y-2 pt-6 text-center">
            <div
                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400"
            >
                <CheckCircle class="h-6 w-6" />
            </div>
            <h3
                class="text-lg font-semibold text-green-800 dark:text-green-300"
            >
                Payment Successful
            </h3>
            <p class="text-sm text-green-700 dark:text-green-400">
                Thank you! Your payment has been verified.
            </p>
        </CardContent>
    </Card>
</template>
