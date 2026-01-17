<script setup lang="ts">
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
import { UserAddress } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

interface AddAddressFormProps {
    open: boolean;
    address?: UserAddress;
}

const props = defineProps<AddAddressFormProps>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    success: [];
}>();

const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();

const form = useForm({
    name: '',
    phone: '',
    address: '',
    postcode: '',
    rajaongkir_province_id: undefined as string | undefined,
    rajaongkir_city_id: undefined as string | undefined,
    rajaongkir_district_id: undefined as string | undefined,
    is_default: false,
});

// Watch for address prop changes to pre-fill form
watch(
    () => props.address,
    (newAddress) => {
        if (newAddress) {
            form.name = newAddress.name;
            form.phone = newAddress.phone;
            form.address = newAddress.address;
            form.postcode = newAddress.postcode;
            form.rajaongkir_province_id = String(
                newAddress.rajaongkir_province_id,
            );
            form.rajaongkir_city_id = String(newAddress.rajaongkir_city_id);
            form.rajaongkir_district_id = String(
                newAddress.rajaongkir_district_id,
            );
            form.is_default = newAddress.is_default;
        } else {
            form.reset();
            form.rajaongkir_province_id = undefined;
            form.rajaongkir_city_id = undefined;
            form.rajaongkir_district_id = undefined;
        }
    },
    { immediate: true },
);

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
        form.rajaongkir_province_id ? Number(form.rajaongkir_province_id) : 0,
    ),
    5 * 60 * 1000,
);

// Fetch districts
const {
    data: districtData,
    isError: isDistrictsError,
    isFetching: isFetchingDistricts,
} = fetchDistricts(
    computed(() =>
        form.rajaongkir_city_id ? Number(form.rajaongkir_city_id) : 0,
    ),
    5 * 60 * 1000,
);

// Reset downstream selections when upstream changes
watch(
    () => form.rajaongkir_province_id,
    (newVal) => {
        if (
            props.address &&
            String(props.address.rajaongkir_province_id) === newVal
        )
            return;
        form.rajaongkir_city_id = undefined;
        form.rajaongkir_district_id = undefined;
    },
);

watch(
    () => form.rajaongkir_city_id,
    (newVal) => {
        if (
            props.address &&
            String(props.address.rajaongkir_city_id) === newVal
        )
            return;
        form.rajaongkir_district_id = undefined;
    },
);

const handleSubmit = () => {
    if (props.address) {
        form.put(`/user-addresses/${props.address.id}`, {
            onSuccess: () => {
                emit('success');
                emit('update:open', false);
                form.reset();
            },
        });
    } else {
        form.post('/user-addresses', {
            onSuccess: () => {
                emit('success');
                emit('update:open', false);
                form.reset();
            },
        });
    }
};

const title = computed(() =>
    props.address ? 'Edit Address' : 'Add New Address',
);
const description = computed(() =>
    props.address
        ? 'Update your shipping address details'
        : 'Add a new shipping address to your account',
);
const submitText = computed(() => {
    if (form.processing) return 'Saving...';
    return props.address ? 'Update Address' : 'Save Address';
});
</script>

<template>
    <Dialog :open="open" @update:open="(val) => $emit('update:open', val)">
        <DialogContent class="max-h-[90vh] max-w-5xl overflow-y-auto">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="grid gap-2">
                    <Label for="add-name">Full Name</Label>
                    <Input
                        id="add-name"
                        v-model="form.name"
                        type="text"
                        required
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="add-phone">Phone Number</Label>
                    <Input id="add-phone" v-model="form.phone" type="text" />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="add-address">Address</Label>
                    <Textarea
                        id="add-address"
                        v-model="form.address"
                        required
                    />
                    <InputError :message="form.errors.address" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="add-province">Province</Label>
                        <Select
                            v-model="form.rajaongkir_province_id"
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
                                >
                                    <SelectItem
                                        v-for="province in provinceData"
                                        :key="province.id"
                                        :value="String(province.id)"
                                    >
                                        {{ province.name }}
                                    </SelectItem>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError
                            :message="form.errors.rajaongkir_province_id"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-city">City</Label>
                        <Select
                            v-model="form.rajaongkir_city_id"
                            :disabled="
                                !form.rajaongkir_province_id || isFetchingCities
                            "
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
                                <template v-if="!isCitiesError && cityData">
                                    <SelectItem
                                        v-for="city in cityData"
                                        :key="city.id"
                                        :value="String(city.id)"
                                    >
                                        {{ city.name }}
                                    </SelectItem>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.rajaongkir_city_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-district">District</Label>
                        <Select
                            v-model="form.rajaongkir_district_id"
                            :disabled="
                                !form.rajaongkir_city_id || isFetchingDistricts
                            "
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
                                >
                                    <SelectItem
                                        v-for="district in districtData"
                                        :key="district.id"
                                        :value="String(district.id)"
                                    >
                                        {{ district.name }}
                                    </SelectItem>
                                </template>
                            </SelectContent>
                        </Select>
                        <InputError
                            :message="form.errors.rajaongkir_district_id"
                        />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="add-postcode">Postal Code</Label>
                    <Input
                        id="add-postcode"
                        v-model="form.postcode"
                        type="text"
                    />
                    <InputError :message="form.errors.postcode" />
                </div>

                <div class="flex items-center gap-2">
                    <input
                        id="add-is-default"
                        v-model="form.is_default"
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
                        :disabled="form.processing"
                    >
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ submitText }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
