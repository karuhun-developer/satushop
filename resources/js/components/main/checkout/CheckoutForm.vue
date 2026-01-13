<script setup lang="ts">
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import AddAddressForm from '@/components/main/checkout/AddAddressForm.vue';
import PaymentMethodSelector from '@/components/main/checkout/PaymentMethodSelector.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useRajaongkirQuery } from '@/composables/query/useRajaongkirQuery';
import { router, usePage } from '@inertiajs/vue3';
import { Check, MapPin, Plus } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

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

interface CheckoutFormProps {
    errors: Record<string, string>;
    userAddresses?: UserAddress[];
}

const props = defineProps<CheckoutFormProps>();

const { fetchProvinces, fetchCities, fetchDistricts } = useRajaongkirQuery();
const page = usePage();

// Address selection
const showAddAddressForm = ref(false);
const selectedAddressId = ref<number | undefined>();
const selectedAddress = ref<UserAddress | undefined>();

// Form fields
const formName = ref(page.props.auth.user?.name || '');
const formEmail = ref(page.props.auth.user?.email || '');
const formPhone = ref(page.props.auth.user?.phone || '');
const formAddress = ref('');
const formPostcode = ref('');

// Location State
const selectedProvince = ref<string>();
const selectedCity = ref<string>();
const selectedDistrict = ref<string>();

// Check if user is logged in
const isLoggedIn = computed(() => !!page.props.auth.user);

// Handle address selection
const handleAddressSelect = (address: UserAddress) => {
    selectedAddress.value = address;
    selectedAddressId.value = address.id;

    // Pre-fill form fields
    formName.value = address.name;
    formPhone.value = address.phone || '';
    formAddress.value = address.address;
    formPostcode.value = address.postcode || '';
    selectedProvince.value = String(address.rajaongkir_province_id);
    selectedCity.value = String(address.rajaongkir_city_id);
    selectedDistrict.value = String(address.rajaongkir_district_id);
};

// Auto-select default address on mount
onMounted(() => {
    if (props.userAddresses && props.userAddresses.length > 0) {
        const defaultAddress = props.userAddresses.find(
            (addr) => addr.is_default,
        );
        if (defaultAddress) {
            handleAddressSelect(defaultAddress);
        }
    }
});

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
        <!-- Add Address Form (only modal now) -->
        <AddAddressForm
            :open="showAddAddressForm"
            @update:open="showAddAddressForm = $event"
            @success="router.reload({ only: ['userAddresses'] })"
        />

        <!-- Shipping Information -->
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">Shipping Information</h2>

            <!-- For Logged In Users: Show Saved Addresses -->
            <div v-if="isLoggedIn">
                <!-- Selected Address Display -->
                <div
                    v-if="selectedAddress"
                    class="mb-4 rounded-lg border bg-accent/50 p-4"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3">
                            <MapPin class="mt-1 h-5 w-5 text-primary" />
                            <div>
                                <p class="font-semibold">
                                    {{ selectedAddress.name }}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{ selectedAddress.phone }}
                                </p>
                                <p class="mt-2 text-sm">
                                    {{ selectedAddress.address }}
                                </p>
                                <p
                                    v-if="selectedAddress.postcode"
                                    class="mt-1 text-sm text-muted-foreground"
                                >
                                    Postcode: {{ selectedAddress.postcode }}
                                </p>
                            </div>
                        </div>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="
                                selectedAddress = undefined;
                                selectedAddressId = undefined;
                            "
                        >
                            Change
                        </Button>
                    </div>
                    <!-- Hidden inputs for selected address data -->
                    <input
                        type="hidden"
                        name="user_address_id"
                        :value="selectedAddressId"
                    />
                    <input
                        type="hidden"
                        name="name"
                        :value="selectedAddress.name"
                    />
                    <input
                        type="hidden"
                        name="phone"
                        :value="selectedAddress.phone"
                    />
                    <input
                        type="hidden"
                        name="address"
                        :value="selectedAddress.address"
                    />
                    <input
                        type="hidden"
                        name="postcode"
                        :value="selectedAddress.postcode"
                    />
                    <input
                        type="hidden"
                        name="rajaongkir_province_id"
                        :value="selectedAddress.rajaongkir_province_id"
                    />
                    <input
                        type="hidden"
                        name="rajaongkir_city_id"
                        :value="selectedAddress.rajaongkir_city_id"
                    />
                    <input
                        type="hidden"
                        name="rajaongkir_district_id"
                        :value="selectedAddress.rajaongkir_district_id"
                    />
                </div>

                <!-- Address Selection (when no address selected) -->
                <div v-else class="space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Select a shipping address
                        </p>
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="showAddAddressForm = true"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Add New Address
                        </Button>
                    </div>

                    <!-- Address List -->
                    <div
                        v-if="userAddresses && userAddresses.length > 0"
                        class="space-y-3"
                    >
                        <div
                            v-for="address in userAddresses"
                            :key="address.id"
                            @click="handleAddressSelect(address)"
                            class="cursor-pointer rounded-lg border p-4 transition-colors hover:border-primary hover:bg-accent"
                        >
                            <div class="flex items-start gap-3">
                                <MapPin
                                    class="mt-1 h-5 w-5 text-muted-foreground"
                                />
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold">
                                            {{ address.name }}
                                        </p>
                                        <Badge
                                            v-if="address.is_default"
                                            variant="default"
                                            class="text-xs"
                                        >
                                            <Check class="mr-1 h-3 w-3" />
                                            Default
                                        </Badge>
                                    </div>
                                    <p class="text-sm text-muted-foreground">
                                        {{ address.phone }}
                                    </p>
                                    <p class="mt-2 text-sm">
                                        {{ address.address }}
                                    </p>
                                    <p
                                        v-if="address.postcode"
                                        class="mt-1 text-sm text-muted-foreground"
                                    >
                                        Postcode: {{ address.postcode }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-show="errors.rajaongkir_province_id">
                            <p class="text-sm text-red-600 dark:text-red-500">
                                Pickk a valid user address.
                            </p>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="rounded-lg border border-dashed p-8 text-center"
                    >
                        <MapPin
                            class="mx-auto h-12 w-12 text-muted-foreground opacity-50"
                        />
                        <p class="mt-2 text-sm text-muted-foreground">
                            No saved addresses yet
                        </p>
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            class="mt-4"
                            @click="showAddAddressForm = true"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Add Your First Address
                        </Button>
                    </div>
                </div>
            </div>

            <!-- For Guest Users: Show Manual Input Form -->
            <div v-else class="grid gap-4">
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
                        v-model="formName"
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
                        v-model="formEmail"
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
                        v-model="formPhone"
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
                        v-model="formAddress"
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
                        v-model="formPostcode"
                    />
                    <InputError :message="errors.postcode" />
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <PaymentMethodSelector />
    </div>
</template>
