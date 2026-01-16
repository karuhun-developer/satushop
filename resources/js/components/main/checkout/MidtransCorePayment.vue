<script setup lang="ts">
import { Landmark } from 'lucide-vue-next';
import { ref } from 'vue';

export type MidtransPaymentType = 'qris' | 'bank_transfer';
export type BankCode = 'bca' | 'bni' | 'bri' | 'mandiri' | 'qris';

interface MidtransCorePaymentProps {
    modelValue?: {
        type: MidtransPaymentType;
        bank?: BankCode;
    };
}

interface MidtransCorePaymentEmits {
    (
        e: 'update:modelValue',
        value: { type: MidtransPaymentType; bank?: BankCode },
    ): void;
}

const props = withDefaults(defineProps<MidtransCorePaymentProps>(), {
    modelValue: () => ({ type: 'qris' }),
});

const emit = defineEmits<MidtransCorePaymentEmits>();

const selectedType = ref<MidtransPaymentType>(props.modelValue.type);
const selectedBank = ref<BankCode | undefined>(props.modelValue.bank);

const selectPaymentType = (type: MidtransPaymentType) => {
    selectedType.value = type;
    if (type === 'qris') {
        selectedBank.value = undefined;
        emit('update:modelValue', { type, bank: 'qris' });
    } else {
        // Default to BCA if bank transfer selected
        selectedBank.value = selectedBank.value || 'bca';
        emit('update:modelValue', { type, bank: selectedBank.value });
    }
};

const selectBank = (bank: BankCode) => {
    selectedBank.value = bank;
    emit('update:modelValue', { type: selectedType.value, bank });
};

const banks = [
    { code: 'bca' as BankCode, name: 'BCA', logo: '🏦' },
    { code: 'bni' as BankCode, name: 'BNI', logo: '🏦' },
    { code: 'bri' as BankCode, name: 'BRI', logo: '🏦' },
    { code: 'mandiri' as BankCode, name: 'Mandiri', logo: '🏦' },
    { code: 'permata' as BankCode, name: 'Permata', logo: '🏦' },
];
</script>

<template>
    <div class="space-y-4 pt-4">
        <p class="text-sm font-medium text-muted-foreground">
            Select Payment Method
        </p>

        <!-- Payment Type Selection -->
        <div class="grid grid-cols-2 gap-3">
            <!-- QRIS -->
            <button
                type="button"
                @click="selectPaymentType('qris')"
                class="flex flex-col items-center gap-3 rounded-lg border bg-background p-4 transition-all hover:border-primary"
                :class="{
                    'border-primary bg-primary/5': selectedType === 'qris',
                    'border-input': selectedType !== 'qris',
                }"
            >
                <img src="/images/QRIS.svg" alt="QRIS" class="h-12 w-12" />
                <div class="text-center">
                    <p class="text-sm font-medium">QRIS</p>
                    <p class="text-xs text-muted-foreground">Scan QR to pay</p>
                </div>
            </button>

            <!-- Bank Transfer -->
            <button
                type="button"
                @click="selectPaymentType('bank_transfer')"
                class="flex flex-col items-center gap-3 rounded-lg border bg-background p-4 transition-all hover:border-primary"
                :class="{
                    'border-primary bg-primary/5':
                        selectedType === 'bank_transfer',
                    'border-input': selectedType !== 'bank_transfer',
                }"
            >
                <Landmark
                    class="h-12 w-12"
                    :class="
                        selectedType === 'bank_transfer'
                            ? 'text-primary'
                            : 'text-muted-foreground'
                    "
                />
                <div class="text-center">
                    <p class="text-sm font-medium">Bank Transfer</p>
                    <p class="text-xs text-muted-foreground">Virtual Account</p>
                </div>
            </button>
        </div>

        <!-- Bank Selection (shown when bank_transfer is selected) -->
        <div v-if="selectedType === 'bank_transfer'" class="space-y-3">
            <p class="text-sm font-medium text-muted-foreground">Select Bank</p>
            <div class="grid grid-cols-2 gap-2">
                <button
                    v-for="bank in banks"
                    :key="bank.code"
                    type="button"
                    @click="selectBank(bank.code)"
                    class="flex items-center justify-center rounded-lg border bg-background p-3 transition-all hover:border-primary"
                    :class="{
                        'border-primary bg-primary/5':
                            selectedBank === bank.code,
                        'border-input': selectedBank !== bank.code,
                    }"
                >
                    <img
                        :src="`/images/${bank.code.toUpperCase()}.svg`"
                        :alt="bank.name"
                        class="h-8 w-auto"
                    />
                </button>
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <input type="hidden" name="payment_type" :value="selectedType" />
        <input
            v-if="selectedBank"
            type="hidden"
            name="bank_code"
            :value="selectedBank"
        />
    </div>
</template>
