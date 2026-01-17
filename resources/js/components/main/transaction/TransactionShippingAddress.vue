<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { Transaction } from '@/types/main/transaction';
import { FileText, MapPin } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    transaction: Transaction;
}

const props = defineProps<Props>();

// Rajaongkir Data
const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();

const { data: provinceData } = fetchProvinces(5 * 60 * 1000);
const { data: cityData } = fetchCities(
    computed(() => props.transaction.rajaongkir_province_id),
    5 * 60 * 1000,
);
const { data: districtData } = fetchDistricts(
    computed(() => props.transaction.rajaongkir_city_id),
    5 * 60 * 1000,
);

const provinceName = computed(
    () =>
        provinceData.value?.find(
            (p) => p.id == props.transaction.rajaongkir_province_id,
        )?.name,
);
const cityName = computed(
    () =>
        cityData.value?.find(
            (c) => c.id == props.transaction.rajaongkir_city_id,
        )?.name,
);
const districtName = computed(
    () =>
        districtData.value?.find(
            (d) => d.id == props.transaction.rajaongkir_district_id,
        )?.name,
);
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="flex items-center gap-2 text-lg">
                <MapPin class="h-5 w-5" /> Shipping Address
            </CardTitle>
        </CardHeader>
        <CardContent>
            <div class="font-medium">
                {{ transaction.name }}
            </div>
            <div class="text-muted-foreground">
                {{ transaction.phone }}
            </div>
            <div class="mt-2">{{ transaction.address }}</div>
            <div class="text-sm text-muted-foreground">
                <span v-if="districtName">{{ districtName }}, </span>
                <span v-if="cityName">{{ cityName }}, </span>
                <span v-if="provinceName">{{ provinceName }}</span>
            </div>
            <div>{{ transaction.postcode }}</div>
            <div
                v-if="transaction.notes"
                class="mt-4 flex gap-2 rounded-md bg-yellow-50 p-3 text-sm text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200"
            >
                <FileText class="mt-0.5 h-4 w-4" />
                <div>
                    <span class="font-semibold">Note:</span>
                    {{ transaction.notes }}
                </div>
            </div>
        </CardContent>
    </Card>
</template>
