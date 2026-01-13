<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import Label from '@/components/ui/label/Label.vue';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Check, MapPin, Plus } from 'lucide-vue-next';
import { ref, watch } from 'vue';

interface UserAddress {
    id: number;
    name: string;
    phone: string;
    address: string;
    postcode: string;
    rajaongkir_province_id: number;
    rajaongkir_city_id: number;
    rajaongkir_district_id: number;
    is_default: boolean;
}

interface UserAddressModalProps {
    addresses: UserAddress[];
    open: boolean;
    selectedAddressId?: number;
}

const props = defineProps<UserAddressModalProps>();
const emit = defineEmits<{
    'update:open': [value: boolean];
    select: [address: UserAddress];
    addNew: [];
}>();

const selectedId = ref<number | undefined>(props.selectedAddressId);

watch(
    () => props.selectedAddressId,
    (newValue) => {
        selectedId.value = newValue;
    },
);

const handleSelect = () => {
    const selected = props.addresses.find(
        (addr) => addr.id === selectedId.value,
    );
    if (selected) {
        emit('select', selected);
        emit('update:open', false);
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="(val) => $emit('update:open', val)">
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>Select Shipping Address</DialogTitle>
                <DialogDescription>
                    Choose an address from your saved addresses
                </DialogDescription>
            </DialogHeader>

            <div class="max-h-[60vh] overflow-y-auto">
                <RadioGroup v-model="selectedId" class="space-y-3">
                    <div
                        v-for="address in addresses"
                        :key="address.id"
                        class="relative flex items-start space-x-3 rounded-lg border p-4 transition-colors hover:bg-accent"
                        :class="{
                            'border-primary bg-primary/5':
                                selectedId === address.id,
                        }"
                    >
                        <RadioGroupItem
                            :value="address.id"
                            :id="`address-${address.id}`"
                            class="mt-1"
                        />
                        <Label
                            :for="`address-${address.id}`"
                            class="flex-1 cursor-pointer space-y-2"
                        >
                            <div class="flex items-center gap-2">
                                <MapPin class="h-4 w-4 text-muted-foreground" />
                                <span class="font-semibold">{{
                                    address.name
                                }}</span>
                                <span
                                    v-if="address.is_default"
                                    class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                                >
                                    <Check class="h-3 w-3" />
                                    Default
                                </span>
                            </div>
                            <div class="text-sm text-muted-foreground">
                                <p>{{ address.phone }}</p>
                                <p class="mt-1">{{ address.address }}</p>
                                <p v-if="address.postcode" class="mt-1">
                                    Postcode: {{ address.postcode }}
                                </p>
                            </div>
                        </Label>
                    </div>
                </RadioGroup>

                <div
                    v-if="addresses.length === 0"
                    class="py-8 text-center text-muted-foreground"
                >
                    <MapPin class="mx-auto h-12 w-12 opacity-50" />
                    <p class="mt-2">No saved addresses found</p>
                </div>
            </div>

            <div class="flex justify-between gap-2 pt-4">
                <Button
                    variant="outline"
                    @click="
                        () => {
                            $emit('update:open', false);
                            $emit('addNew');
                        }
                    "
                >
                    <Plus class="mr-2 h-4 w-4" />
                    Add New Address
                </Button>
                <div class="flex gap-2">
                    <Button
                        variant="outline"
                        @click="$emit('update:open', false)"
                    >
                        Cancel
                    </Button>
                    <Button
                        @click="handleSelect"
                        :disabled="!selectedId || addresses.length === 0"
                    >
                        Use This Address
                    </Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
