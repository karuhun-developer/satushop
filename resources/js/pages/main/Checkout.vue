<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Main/CheckoutController';
import CheckoutForm from '@/components/main/checkout/CheckoutForm.vue';
import OrderSummary from '@/components/main/checkout/OrderSummary.vue';
import { EmptyState } from '@/components/ui/empty-state';
import { useCartStore } from '@/composables/useCartStore';
import { useSwal } from '@/composables/useSwal';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { explore } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { ShoppingBag } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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

interface CheckoutPageProps {
    userAddresses: UserAddress[];
}

defineProps<CheckoutPageProps>();

const { toast } = useSwal();
const cart = useCartStore();

const hasItems = computed(() => cart.items.value.length > 0);
const destinationDistrictId = ref<number | null>(null);
</script>

<template>
    <Head title="Checkout" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="mb-8 text-3xl font-bold">Checkout</h1>

            <!-- Checkout Content -->
            <Form
                v-if="hasItems"
                v-bind="store.form()"
                class="grid gap-8 lg:grid-cols-3"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Order placed successfully!',
                        });
                        cart.clearCart();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <!-- Checkout Form (Left Side) -->
                <div class="lg:col-span-2">
                    <CheckoutForm
                        :errors="errors"
                        :user-addresses="userAddresses"
                        @update:destinationDistrictId="
                            destinationDistrictId = $event
                        "
                    />
                </div>

                <!-- Order Summary (Right Side) -->
                <div class="lg:col-span-1">
                    <OrderSummary
                        :errors="errors"
                        :processing="processing"
                        :destination-district-id="destinationDistrictId"
                    />
                </div>
            </Form>

            <!-- Empty State -->
            <div v-else class="py-20">
                <EmptyState
                    title="Your cart is empty"
                    description="Add some products to your cart to proceed with checkout."
                    actionLabel="Continue Shopping"
                    @action="$inertia.visit(explore.url())"
                >
                    <template #icon>
                        <ShoppingBag class="h-12 w-12 text-muted-foreground" />
                    </template>
                </EmptyState>
            </div>
        </div>
    </ShopLayout>
</template>
