<script setup lang="ts">
import type {
    BankCode,
    MidtransPaymentType,
} from '@/components/main/checkout/MidtransCorePayment.vue';
import MidtransCorePayment from '@/components/main/checkout/MidtransCorePayment.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

export type PaymentMethod = 'cod' | 'bank_transfer' | 'midtrans';

interface PaymentMethodSelectorProps {
    modelValue?: PaymentMethod;
}

interface PaymentMethodSelectorEmits {
    (e: 'update:modelValue', value: PaymentMethod): void;
}

const page = usePage();
const props = withDefaults(defineProps<PaymentMethodSelectorProps>(), {
    modelValue: 'midtrans',
});

const emit = defineEmits<PaymentMethodSelectorEmits>();

const selectedMethod = ref<PaymentMethod>(props.modelValue);
const gateway = ref<{
    type: MidtransPaymentType;
    bank?: BankCode;
}>({ type: 'qris', bank: 'qris' });

const selectMethod = (method: PaymentMethod) => {
    selectedMethod.value = method;
    emit('update:modelValue', method);
};
</script>

<template>
    <div class="rounded-lg border bg-card p-6">
        <h2 class="mb-4 text-xl font-semibold">Payment Method</h2>

        <div class="space-y-2">
            <!-- Bank Transfer -->
            <label
                class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition-colors hover:bg-muted/50"
                :class="{
                    'border-primary bg-primary/5':
                        selectedMethod === 'bank_transfer',
                }"
            >
                <input
                    type="radio"
                    name="payment"
                    value="bank_transfer"
                    class="h-4 w-4"
                    :checked="selectedMethod === 'bank_transfer'"
                    @change="selectMethod('bank_transfer')"
                />
                <div class="flex-1">
                    <span class="font-medium">Bank Transfer</span>
                    <p class="text-xs text-muted-foreground">
                        Transfer directly to our bank account
                    </p>
                </div>
            </label>

            <!-- Midtrans -->
            <div
                class="rounded-lg border transition-colors"
                v-if="page.props.paymentGateway === 'midtrans'"
                :class="{
                    'border-primary bg-primary/5':
                        selectedMethod === 'midtrans',
                }"
            >
                <label
                    class="flex cursor-pointer items-center gap-3 p-4 hover:bg-muted/50"
                >
                    <input
                        type="radio"
                        name="payment"
                        value="midtrans"
                        class="h-4 w-4"
                        :checked="selectedMethod === 'midtrans'"
                        @change="selectMethod('midtrans')"
                    />
                    <div class="flex-1">
                        <span class="font-medium">Midtrans</span>
                        <p class="text-xs text-muted-foreground">
                            Pay with QRIS or Bank Transfer
                        </p>
                    </div>
                </label>
                <div
                    v-if="selectedMethod === 'midtrans'"
                    class="border-t px-4 pb-4"
                >
                    <MidtransCorePayment v-model="gateway" />
                </div>
            </div>
        </div>

        <!-- Hidden input for form submission -->
        <input type="hidden" name="payment_method" :value="selectedMethod" />
        <input type="hidden" name="gateway_type" :value="gateway.type" />
        <input type="hidden" name="gateway_bank" :value="gateway.bank" />
    </div>
</template>
