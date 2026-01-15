import { ProductFlatDataItem } from '@/types/cms/catalog';
import { useLocalStorage } from '@vueuse/core';
import { computed, ref } from 'vue';

export interface CartItem {
    id: number;
    shop_id: number;
    shop_name: string;
    name: string;
    price: number;
    quantity: number;
    weight: number;
    image: string;
    options?: Record<string, any>;
    category?: string;
}

export const useCartStore = () => {
    const items = useLocalStorage<CartItem[]>('cart-items', []);
    const shippingCosts = ref<Record<number, number>>({});

    const count = computed(() => {
        return items.value.reduce((acc, item) => acc + item.quantity, 0);
    });

    const subtotal = computed(() => {
        return items.value.reduce(
            (acc, item) => acc + item.price * item.quantity,
            0,
        );
    });

    const total = computed(() => {
        const shippingTotal = Object.values(shippingCosts.value).reduce(
            (acc, cost) => acc + cost,
            0,
        );
        return subtotal.value + shippingTotal;
    });

    const groupedItems = computed(() => {
        const groups: Record<number, { shop_name: string; items: CartItem[] }> =
            {};

        items.value.forEach((item) => {
            if (!groups[item.shop_id]) {
                groups[item.shop_id] = {
                    shop_name: item.shop_name,
                    items: [],
                };
            }
            groups[item.shop_id].items.push(item);
        });

        return groups;
    });

    const addItem = (
        product: ProductFlatDataItem,
        quantity: number = 1,
        options: Record<string, any> = {},
    ) => {
        const existingItemIndex = items.value.findIndex(
            (item) =>
                item.id === product.id &&
                JSON.stringify(item.options) === JSON.stringify(options),
        );

        if (existingItemIndex !== -1) {
            items.value[existingItemIndex].quantity += quantity;
        } else {
            items.value.push({
                id: product.id,
                shop_id: product.product?.shop_id || 0,
                shop_name: product.product?.shop?.name || 'Unknown Shop',
                name: product.name,
                price: Number(product?.price),
                quantity,
                weight: Number(product?.weight || 1000), // Default 1kg if missing
                image: product?.image_1 || '',
                options,
            });
        }
    };

    const removeItem = (id: number) => {
        const index = items.value.findIndex((item) => item.id === id);
        if (index !== -1) {
            items.value.splice(index, 1);
        }
    };

    const updateQuantity = (id: number, quantity: number) => {
        const index = items.value.findIndex((item) => item.id === id);
        if (index !== -1) {
            if (quantity > 0) {
                items.value[index].quantity = quantity;
            } else {
                removeItem(id);
            }
        }
    };

    const clearCart = () => {
        items.value = [];
    };

    return {
        items,
        count,
        subtotal,
        total,
        groupedItems,
        shippingCosts,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
    };
};
