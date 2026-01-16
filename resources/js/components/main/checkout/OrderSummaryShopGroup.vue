<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { CartItem } from '@/composables/useCartStore';
import { formatCurrency } from '@/lib/utils';
import { computed, ref, watch } from 'vue';

interface Props {
    shopId: number;
    shopName: string;
    items: CartItem[];
    destinationDistrictId?: number | null;
}

const props = defineProps<Props>();

const { fetchShippingCosts } = useRajaongkirQuery();

const selectedOptionId = ref<string | undefined>();
const shippingOptions = ref<any[]>([]);

// Calculate total weight
const totalWeight = computed(() => {
    return props.items.reduce(
        (acc, item) => acc + item.weight * item.quantity,
        0,
    );
});

// Helper to normalized costs
const normalizeCosts = (data: any[]) => {
    if (!Array.isArray(data)) return [];

    return data.map((c: any) => ({
        id: `${c.code.toUpperCase()}-${c.service}`,
        label: `${c.name} - ${c.service} (${c.etd || 'N/A'} days)`,
        value: c.cost,
        cost: c.cost,
        courier: c.code,
        courierName: c.name,
        service: c.service,
        etd: c.etd,
    }));
};

// Define queries
const shipQuery = fetchShippingCosts(
    props.shopId,
    props.destinationDistrictId || 0,
    totalWeight.value,
);

const isLoading = computed(() => shipQuery.isFetching.value);

const emit = defineEmits(['selection']);

// Collect options when data changes
watch(
    [shipQuery.data],
    ([data]) => {
        let options: any[] = [];
        if (data) {
            options = normalizeCosts(data);
        }

        shippingOptions.value = options;

        // Reset selection if invalid
        if (selectedOptionId.value) {
            const exists = options.find((o) => o.id === selectedOptionId.value);
            if (!exists) {
                selectedOptionId.value = undefined;
                emit('selection', {
                    shopId: props.shopId,
                    cost: 0,
                    courier: null,
                    service: null,
                    etd: null,
                });
            }
        } else {
            emit('selection', {
                shopId: props.shopId,
                cost: 0,
                courier: null,
                service: null,
                etd: null,
            });
        }
    },
    { immediate: true },
);

const handleShippingChange = (optionId: string) => {
    selectedOptionId.value = optionId;
    const selected = shippingOptions.value.find((o) => o.id === optionId);

    if (selected) {
        emit('selection', { ...selected, shopId: props.shopId });
    }
};

const groupedOptions = computed(() => {
    const groups: Record<string, any[]> = {};
    shippingOptions.value.forEach((option) => {
        const key = option.courierName || option.courier.toUpperCase();
        if (!groups[key]) {
            groups[key] = [];
        }
        groups[key].push(option);
    });
    return groups;
});

const selectedOption = computed(() => {
    if (!selectedOptionId.value) return undefined;
    return shippingOptions.value.find((o) => o.id === selectedOptionId.value);
});
</script>

<template>
    <div class="mb-6 last:mb-0">
        <div class="mb-2 flex items-center justify-between">
            <h3 class="font-medium text-foreground">{{ shopName }}</h3>
            <Badge variant="outline" class="text-xs">
                {{ items.length }} items
            </Badge>
        </div>

        <!-- Items Loop -->
        <div class="mb-4 space-y-3">
            <div v-for="item in items" :key="item.id" class="flex gap-3">
                <input
                    type="hidden"
                    :name="`items[${shopId}][${item.id}][quantity]`"
                    :value="item.quantity"
                />
                <div
                    class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                >
                    <img
                        :src="item.image || 'https://via.placeholder.com/150'"
                        :alt="item.name"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">
                        {{ item.name }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        Qty: {{ item.quantity }} &times; {{ item.weight }}g
                    </p>
                    <p class="text-sm font-semibold">
                        {{ formatCurrency(Number(item.price) * item.quantity) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Shipping Selection -->
        <div class="mt-3">
            <Label
                v-if="destinationDistrictId"
                class="mb-1 block text-xs text-muted-foreground"
                >Shipping Method</Label
            >

            <div
                v-if="!destinationDistrictId"
                class="rounded bg-yellow-50 p-2 text-sm text-yellow-600"
            >
                Please select an address to calculate shipping.
            </div>

            <div
                v-else-if="isLoading"
                class="py-2 text-sm text-muted-foreground"
            >
                Calculating shipping...
            </div>

            <div v-else-if="shippingOptions.length > 0">
                <Select
                    :model-value="selectedOptionId"
                    @update:model-value="
                        (val) => handleShippingChange(val as string)
                    "
                >
                    <SelectTrigger class="w-full">
                        <div
                            v-if="selectedOption"
                            class="flex w-full items-center justify-between gap-2"
                        >
                            <span class="truncate">{{
                                selectedOption.label
                            }}</span>
                            <span class="mr-2 font-semibold">{{
                                formatCurrency(selectedOption.value)
                            }}</span>
                        </div>
                        <span v-else>Select Shipping</span>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup
                            v-for="(options, groupName) in groupedOptions"
                            :key="groupName"
                        >
                            <SelectLabel>{{ groupName }}</SelectLabel>
                            <SelectItem
                                v-for="option in options"
                                :key="option.id"
                                :value="option.id"
                            >
                                <div class="flex w-full justify-between gap-2">
                                    <span
                                        >{{ option.service }} ({{
                                            option.etd || 'N/A'
                                        }}
                                        days)</span
                                    >
                                    <span class="font-semibold">{{
                                        formatCurrency(option.value)
                                    }}</span>
                                </div>
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
            <div v-else class="text-sm text-red-500">
                No shipping available.
            </div>
        </div>

        <!-- Hidden Inputs to submit selected shipping -->
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][id]'"
            :value="selectedOption?.id"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][label]'"
            :value="selectedOption?.label"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][courier]'"
            :value="selectedOption?.courier"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][courier_name]'"
            :value="selectedOption?.courierName"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][service]'"
            :value="selectedOption?.service"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][cost]'"
            :value="selectedOption?.cost"
        />
        <input
            type="hidden"
            :name="'shipping[' + shopId + '][etd]'"
            :value="selectedOption?.etd"
        />

        <Separator class="my-4" />
    </div>
</template>
