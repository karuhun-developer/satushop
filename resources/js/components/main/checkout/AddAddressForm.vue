<script setup lang="ts">
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

interface AddAddressFormProps {
    open: boolean;
}

const props = defineProps<AddAddressFormProps>();
const emit = defineEmits<{
    'update:open': [value: boolean];
    success: [];
}>();

const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();

// Form fields
const formName = ref('');
const formPhone = ref('');
const formAddress = ref('');
const formPostcode = ref('');
const isDefault = ref(false);

// Location State
const selectedProvince = ref<string>();
const selectedCity = ref<string>();
const selectedDistrict = ref<string>();

// Errors
const errors = ref<Record<string, string>>({});
const processing = ref(false);

// Fetch provinces with caching
const {
    data: provinceData,
    isError: isProvincesError,
    isFetching: isFetchingProvinces,
} = fetchProvinces(5 * 60 * 1000);

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

const handleSubmit = () => {
    processing.value = true;
    errors.value = {};

    router.post(
        '/user-addresses',
        {
            name: formName.value,
            phone: formPhone.value,
            address: formAddress.value,
            postcode: formPostcode.value,
            rajaongkir_province_id: selectedProvince.value,
            rajaongkir_city_id: selectedCity.value,
            rajaongkir_district_id: selectedDistrict.value,
            is_default: isDefault.value,
        },
        {
            onSuccess: () => {
                emit('success');
                emit('update:open', false);
                resetForm();
            },
            onError: (err) => {
                errors.value = err;
            },
            onFinish: () => {
                processing.value = false;
            },
        },
    );
};

const resetForm = () => {
    formName.value = '';
    formPhone.value = '';
    formAddress.value = '';
    formPostcode.value = '';
    selectedProvince.value = undefined;
    selectedCity.value = undefined;
    selectedDistrict.value = undefined;
    isDefault.value = false;
    errors.value = {};
};
</script>

<template>
    <Dialog :open="open" @update:open="(val) => $emit('update:open', val)">
        <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Add New Address</DialogTitle>
                <DialogDescription>
                    Add a new shipping address to your account
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="grid gap-2">
                    <Label for="add-name">Full Name</Label>
                    <Input
                        id="add-name"
                        v-model="formName"
                        type="text"
                        required
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="add-phone">Phone Number</Label>
                    <Input
                        id="add-phone"
                        v-model="formPhone"
                        type="text"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="add-address">Address</Label>
                    <Textarea
                        id="add-address"
                        v-model="formAddress"
                        required
                    />
                    <InputError :message="errors.address" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="add-province">Province</Label>
                        <Select
                            v-model="selectedProvince"
                            :disabled="isFetchingProvinces"
                        >
                            <SelectTrigger id="add-province">
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
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_province_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-city">City</Label>
                        <Select
                            v-model="selectedCity"
                            :disabled="!selectedProvince || isFetchingCities"
                        >
                            <SelectTrigger id="add-city">
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
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_city_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-district">District</Label>
                        <Select
                            v-model="selectedDistrict"
                            :disabled="!selectedCity || isFetchingDistricts"
                        >
                            <SelectTrigger id="add-district">
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
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.rajaongkir_district_id" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="add-postcode">Postal Code</Label>
                    <Input
                        id="add-postcode"
                        v-model="formPostcode"
                        type="text"
                    />
                    <InputError :message="errors.postcode" />
                </div>

                <div class="flex items-center gap-2">
                    <input
                        id="add-is-default"
                        v-model="isDefault"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300"
                    />
                    <Label for="add-is-default" class="cursor-pointer">
                        Set as default address
                    </Label>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="$emit('update:open', false)"
                        :disabled="processing"
                    >
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Saving...' : 'Save Address' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
