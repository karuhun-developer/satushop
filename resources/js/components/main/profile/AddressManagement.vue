<script setup lang="ts">
import AddAddressForm from '@/components/main/checkout/AddAddressForm.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useSwal } from '@/composables/useSwal';
import { UserAddress } from '@/types';
import { router } from '@inertiajs/vue3';
import { Check, MapPin, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';


interface AddressManagementProps {
    addresses: UserAddress[];
}

defineProps<AddressManagementProps>();

const { toast, confirm } = useSwal();
const showAddAddressForm = ref(false);
const selectedAddressToEdit = ref<UserAddress | undefined>();

const openAddModal = () => {
    selectedAddressToEdit.value = undefined;
    showAddAddressForm.value = true;
};

const openEditModal = (address: UserAddress) => {
    selectedAddressToEdit.value = address;
    showAddAddressForm.value = true;
};

const handleDelete = async (addressId: number) => {
    const result = await confirm({
        title: 'Delete Address?',
        text: 'Are you sure you want to delete this address?',
        icon: 'warning',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
    });

    if (result.isConfirmed) {
        router.delete(`/user-addresses/${addressId}`, {
            onSuccess: () => {
                toast.fire({
                    icon: 'success',
                    title: 'Address deleted successfully!',
                });
            },
        });
    }
};
</script>

<template>
    <div class="rounded-lg border bg-card">
        <!-- Add/Edit Address Form -->
        <AddAddressForm
            :open="showAddAddressForm"
            :address="selectedAddressToEdit"
            @update:open="showAddAddressForm = $event"
            @success="router.reload({ only: ['addresses'] })"
        />

        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold">My Addresses</h2>
                <Button @click="openAddModal" size="sm">
                    <Plus class="mr-2 h-4 w-4" />
                    Add New Address
                </Button>
            </div>

            <div v-if="addresses.length > 0" class="space-y-4">
                <div
                    v-for="address in addresses"
                    :key="address.id"
                    class="rounded-lg border p-4"
                >
                    <div class="flex items-start justify-between">
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
                                <p class="mt-1 text-sm text-muted-foreground">
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
                        <div class="flex items-center gap-2">
                            <Button
                                variant="ghost"
                                size="icon"
                                @click="openEditModal(address)"
                                class="text-muted-foreground hover:text-primary"
                            >
                                <Pencil class="h-4 w-4" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                @click="handleDelete(address.id)"
                                class="text-destructive hover:text-destructive"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="py-12 text-center text-muted-foreground">
                <MapPin class="mx-auto h-12 w-12 opacity-50" />
                <p class="mt-2">No addresses saved yet</p>
                <Button
                    @click="openAddModal"
                    variant="outline"
                    class="mt-4"
                >
                    <Plus class="mr-2 h-4 w-4" />
                    Add Your First Address
                </Button>
            </div>
        </div>
    </div>
</template>
