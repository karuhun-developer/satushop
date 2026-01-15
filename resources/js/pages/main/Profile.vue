<script setup lang="ts">
import AddressManagement from '@/components/main/profile/AddressManagement.vue';
import TransactionHistory from '@/components/main/profile/TransactionHistory.vue';
import UpdateProfileForm from '@/components/main/profile/UpdateProfileForm.vue';
import { Button } from '@/components/ui/button';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Head } from '@inertiajs/vue3';
import { MapPin, ShoppingBag, User } from 'lucide-vue-next';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
}

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

interface Transaction {
    id: string;
    date: string;
    total: number;
    status: string;
    items_count: number;
}

interface ProfilePageProps {
    user: User;
    addresses: UserAddress[];
    transactions: Transaction[];
}

defineProps<ProfilePageProps>();

const activeTab = ref('profile');

// Handle initial tab from query param
const urlParams = new URLSearchParams(window.location.search);
const tabParam = urlParams.get('tab');
if (tabParam && ['profile', 'transactions', 'addresses'].includes(tabParam)) {
    activeTab.value = tabParam;
}
</script>

<template>
    <Head title="My Profile" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="mb-8 text-3xl font-bold">My Profile</h1>

            <!-- Custom Tabs -->
            <div class="mb-6 flex gap-2 border-b">
                <Button
                    variant="ghost"
                    @click="activeTab = 'profile'"
                    :class="{
                        'border-b-2 border-primary': activeTab === 'profile',
                    }"
                    class="rounded-b-none"
                >
                    <User class="mr-2 h-4 w-4" />
                    Profile
                </Button>
                <Button
                    variant="ghost"
                    @click="activeTab = 'transactions'"
                    :class="{
                        'border-b-2 border-primary':
                            activeTab === 'transactions',
                    }"
                    class="rounded-b-none"
                >
                    <ShoppingBag class="mr-2 h-4 w-4" />
                    Transactions
                </Button>
                <Button
                    variant="ghost"
                    @click="activeTab = 'addresses'"
                    :class="{
                        'border-b-2 border-primary': activeTab === 'addresses',
                    }"
                    class="rounded-b-none"
                >
                    <MapPin class="mr-2 h-4 w-4" />
                    Addresses
                </Button>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <UpdateProfileForm
                    v-if="activeTab === 'profile'"
                    :user="user"
                />
                <TransactionHistory
                    v-if="activeTab === 'transactions'"
                    :transactions="transactions"
                />
                <AddressManagement
                    v-if="activeTab === 'addresses'"
                    :addresses="addresses"
                />
            </div>
        </div>
    </ShopLayout>
</template>
