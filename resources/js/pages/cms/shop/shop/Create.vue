<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Cms/Shop/ShopController';
import ImageUploadPreview from '@/components/ImageUploadPreview.vue';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import MapLocationSelector from '@/components/MapLocationSelector.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import QuilTextEditor from '@/components/ui/quil-editor/QuilTextEditor.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { useSwal } from '@/composables/useSwal';
import { ValidationEnum } from '@/enums/global.enum';
import { LocaleDataItem } from '@/types/cms/core';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

defineProps<{
    locales: LocaleDataItem[];
}>();

const { toast } = useSwal();
const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();

// Descriptions for each locale
const descriptions = ref<Record<string, string>>({});

// Default latitude and longitude Bandung
const latitude = ref<number>(-6.90389);
const longitude = ref<number>(107.61861);

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
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Create New Shop
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new shop for your application.
            </p>

            <Form
                v-bind="store.form()"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Shop created.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <InputDescription> Default shop name. </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="logo">Logo</Label>
                        <InputDescription>
                            Shop logo image (max 2MB).
                        </InputDescription>
                        <ImageUploadPreview
                            input-id="logo"
                            input-name="logo"
                            label=""
                            description="Upload your shop logo image"
                            accept="image/*"
                            :max-size="2"
                            preview-height="200px"
                            :errors="errors.logo"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="banner">Banner</Label>
                        <InputDescription
                            >Shop banner image (max 5MB).</InputDescription
                        >
                        <ImageUploadPreview
                            input-id="banner"
                            input-name="banner"
                            label=""
                            description="Upload your shop banner image"
                            accept="image/*"
                            :max-size="5"
                            preview-height="300px"
                            :errors="errors.banner"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <template
                        v-for="locale in locales"
                        :key="`name_${locale.id}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`name_${locale.code}`"
                                >Name ({{ locale.name }})</Label
                            >
                            <InputDescription>
                                The shop name in
                                {{ locale.name }}.
                            </InputDescription>
                            <Input
                                :id="`name_${locale.code}`"
                                :name="`translations[${locale.code}][name]`"
                                type="text"
                                class="mt-1 block w-full"
                                :placeholder="`Enter name in ${locale.name}`"
                                autofocus
                            />
                            <InputError
                                :message="
                                    errors[`translations.${locale.code}.name`]
                                "
                            />
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <template
                        v-for="locale in locales"
                        :key="`editor_${locale.code}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`description_${locale.code}`"
                                >Description ({{ locale.name }})</Label
                            >
                            <InputDescription>
                                The shop description in
                                {{ locale.name }}.
                            </InputDescription>
                            <Input
                                :id="`description_${locale.code}`"
                                :name="`translations[${locale.code}][description]`"
                                type="hidden"
                                :value="descriptions[locale.code]"
                            />
                            <QuilTextEditor
                                @update:content="
                                    (value) => {
                                        descriptions[locale.code] = value;
                                    }
                                "
                            />
                            <InputError
                                :message="
                                    errors[
                                        `translations.${locale.code}.description`
                                    ]
                                "
                            />
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <InputDescription>
                            Default shop email address.
                        </InputDescription>
                        <Input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="errors.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="phone">Phone</Label>
                        <InputDescription>
                            Default shop phone number.
                        </InputDescription>
                        <Input
                            id="phone"
                            name="phone"
                            type="text"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="errors.phone" />
                    </div>
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
                    <Label for="address">Address</Label>
                    <InputDescription>
                        Default shop physical address.
                    </InputDescription>
                    <Input
                        id="address"
                        name="address"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError :message="errors.address" />
                </div>

                <div class="grid gap-2">
                    <Label for="postal_code">Postal Code</Label>
                    <InputDescription>
                        Default shop postal code.
                    </InputDescription>
                    <Input
                        id="postal_code"
                        name="postal_code"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="errors.postal_code" />
                </div>

                <div class="grid gap-2">
                    <Label for="location">Location </Label>
                    <InputDescription>
                        Shop geographical location.
                    </InputDescription>
                    <Input
                        type="hidden"
                        name="latitude"
                        :value="latitude"
                        :default-value="latitude"
                    />
                    <Input
                        type="hidden"
                        name="longitude"
                        :value="longitude"
                        :default-value="longitude"
                    />
                    <MapLocationSelector
                        :initial-lat="latitude"
                        :initial-lon="longitude"
                        map-height="500px"
                        :zoom-level="13"
                        @update:location="
                            ({ lat, lon }) => {
                                latitude = lat;
                                longitude = lon;
                            }
                        "
                    />
                    <InputError :message="errors.latitude" />
                    <InputError :message="errors.longitude" />
                </div>

                <div class="grid gap-2">
                    <Label for="status">Status</Label>
                    <InputDescription> Status of the shop. </InputDescription>
                    <Select name="status" :default-value="0">
                        <SelectTrigger id="status" class="mt-1 w-full">
                            <SelectValue placeholder="Select status" />
                        </SelectTrigger>
                        <SelectContent>
                            <template
                                v-for="valStatus in ValidationEnum"
                                :key="valStatus.value"
                            >
                                <SelectItem :value="valStatus.value">
                                    {{ valStatus.label }}
                                </SelectItem>
                            </template>
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.status" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save />
                        Save Changes
                    </Button>
                </div>
            </Form>
        </div>
    </Modal>
</template>
