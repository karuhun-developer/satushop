<script setup lang="ts">
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import PaymentMethodSelector from '@/components/main/checkout/PaymentMethodSelector.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

interface CheckoutFormProps {
    errors: Record<string, string>;
}

const props = defineProps<CheckoutFormProps>();

const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();
const page = usePage();

// Location State
const selectedProvince = ref<string>();
const selectedCity = ref<string>();
const selectedDistrict = ref<string>();

// Fetch provinces with caching
const {
    data: provinceData,
    isError: isProvincesError,
    isFetching: isFetchingProvinces,
} = fetchProvinces(5 * 60 * 1000); // Cache for 5 minutes

// Fetch cities
const {
    data: cityData,
    isError: isCitiesError,
    isFetching: isFetchingCities,
} = fetchCities(
    computed(() =>
        selectedProvince.value ? Number(selectedProvince.value) : 0,
    ),
    5 * 60 * 1000,
);

// Fetch districts
const {
    data: districtData,
    isError: isDistrictsError,
    isFetching: isFetchingDistricts,
} = fetchDistricts(
    computed(() => (selectedCity.value ? Number(selectedCity.value) : 0)),
    5 * 60 * 1000,
);

// Reset downstream selections when upstream changes
watch(selectedProvince, () => {
    selectedCity.value = undefined;
    selectedDistrict.value = undefined;
});

watch(selectedCity, () => {
    selectedDistrict.value = undefined;
});
</script>

<template>
    <div class="space-y-6">
        <!-- Shipping Information -->
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">Shipping Information</h2>
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="name">Full Name</Label>
                    <InputDescription> Enter your full name. </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="page.props.auth.user.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <InputDescription> Enter your email. </InputDescription>
                    <Input
                        id="email"
                        name="email"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="page.props.auth.user.email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">Phone Number</Label>
                    <InputDescription> Enter your phone. </InputDescription>
                    <Input
                        id="phone"
                        name="phone"
                        type="number"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="page.props.auth.user.phone"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="address">Address</Label>
                    <InputDescription> Enter your address. </InputDescription>
                    <Textarea
                        id="address"
                        name="address"
                        type="number"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.address" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="rajaongkir_province_id">Province</Label>
                        <InputDescription>
                            Select the province where the shop is located.
                        </InputDescription>
                        <Select
                            v-model="selectedProvince"
                            name="rajaongkir_province_id"
                            :disabled="isFetchingProvinces"
                        >
                            <SelectTrigger
                                id="rajaongkir_province_id"
                                class="mt-1 w-full"
                            >
                                <SelectValue
                                    :placeholder="
                                        isFetchingProvinces
                                            ? 'Loading...'
                                            : 'Select Province'
                                    "
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <template
                                    v-if="!isProvincesError && provinceData"
                                    v-for="province in provinceData"
                                    :key="province.id"
                                >
                                    <SelectItem :value="String(province.id)">{{
                                        province.name
                                    }}</SelectItem>
                                </template>
                                <template v-else-if="isProvincesError">
                                    <div class="p-2 text-sm text-red-600">
                                        Failed to load provinces
                                    </div>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_province_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="rajaongkir_city_id">City</Label>
                        <InputDescription>
                            Select the city where the shop is located.
                        </InputDescription>
                        <Select
                            v-model="selectedCity"
                            name="rajaongkir_city_id"
                            :disabled="!selectedProvince || isFetchingCities"
                        >
                            <SelectTrigger
                                id="rajaongkir_city_id"
                                class="mt-1 w-full"
                            >
                                <SelectValue
                                    :placeholder="
                                        isFetchingCities
                                            ? 'Loading...'
                                            : 'Select City'
                                    "
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <template
                                    v-if="!isCitiesError && cityData"
                                    v-for="city in cityData"
                                    :key="city.id"
                                >
                                    <SelectItem :value="String(city.id)">{{
                                        city.name
                                    }}</SelectItem>
                                </template>
                                <template v-else-if="isCitiesError">
                                    <div class="p-2 text-sm text-red-600">
                                        Failed to load cities
                                    </div>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_city_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="rajaongkir_district_id">District</Label>
                        <InputDescription>
                            Select the district where the shop is located.
                        </InputDescription>
                        <Select
                            v-model="selectedDistrict"
                            name="rajaongkir_district_id"
                            :disabled="!selectedCity || isFetchingDistricts"
                        >
                            <SelectTrigger
                                id="rajaongkir_district_id"
                                class="mt-1 w-full"
                            >
                                <SelectValue
                                    :placeholder="
                                        isFetchingDistricts
                                            ? 'Loading...'
                                            : 'Select District'
                                    "
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <template
                                    v-if="!isDistrictsError && districtData"
                                    v-for="district in districtData"
                                    :key="district.id"
                                >
                                    <SelectItem :value="String(district.id)">{{
                                        district.name
                                    }}</SelectItem>
                                </template>
                                <template v-else-if="isDistrictsError">
                                    <div class="p-2 text-sm text-red-600">
                                        Failed to load districts
                                    </div>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_district_id" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="postcode">Postal Code</Label>
                    <InputDescription>
                        Enter your postal code.
                    </InputDescription>
                    <Input
                        id="postcode"
                        name="postcode"
                        type="number"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.postcode" />
                </div>
            </div>
        </div>


        <!-- Payment Method -->
        <PaymentMethodSelector />
    </div>
</template>
