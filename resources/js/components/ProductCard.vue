<script setup lang="ts">
import { show } from '@/actions/App/Http/Controllers/Main/ProductController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useCartStore } from '@/composables/useCartStore';
import { useSwal } from '@/composables/useSwal';
import { formatCurrency } from '@/lib/utils';
import { ProductFlatDataItem } from '@/types/cms/catalog';
import { Link } from '@inertiajs/vue3';
import { ShoppingBag, Star } from 'lucide-vue-next';

const props = defineProps<{
    product: ProductFlatDataItem;
}>();

const cart = useCartStore();
const { toast } = useSwal();

const addToCart = (e: Event) => {
    e.preventDefault();
    e.stopPropagation();

    cart.addItem(
        {
            ...props.product,
            image: props.product?.image_1 || '',
        },
        1,
    );

    toast.fire({
        icon: 'success',
        title: 'Added to cart',
        text: `${props.product.name} has been added to your cart.`,
    });
};
</script>

<template>
    <Link
        :href="
            show.url({
                product: product.slug,
            })
        "
        class="group block cursor-pointer overflow-hidden rounded-lg border bg-white shadow-sm transition-shadow hover:shadow-md"
    >
        <Card class="h-full">
            <!-- Product Image -->
            <div class="relative aspect-square overflow-hidden bg-gray-100">
                <img
                    :src="product.image_1"
                    :alt="product.name"
                    class="h-full w-full object-cover"
                />
            </div>

            <!-- Product Details -->
            <CardContent class="space-y-1 p-3">
                <h3
                    class="line-clamp-2 text-sm leading-snug font-normal text-foreground"
                >
                    {{ product.name }}
                </h3>
                <div
                    class="text-base font-bold"
                    v-if="product.type == 'variable'"
                >
                    {{
                        formatCurrency(
                            Number(
                                product.first_variant?.variant_product?.price,
                            ),
                        )
                    }}
                </div>
                <div class="text-base font-bold" v-else>
                    {{ formatCurrency(Number(product.price)) }}
                </div>

                <div
                    class="flex items-center gap-1 text-[11px] text-muted-foreground"
                >
                    <div class="flex items-center gap-0.5">
                        <div v-if="product.rating">
                            <Star
                                class="h-3 w-3 fill-yellow-400 text-yellow-400"
                            />
                            <span>{{ product.rating }}</span>
                        </div>
                        <div v-else>
                            <span>Belum ada rating</span>
                        </div>
                    </div>
                    <span class="mx-0.5 max-[300px]:hidden">|</span>
                    <span>{{ product.sold_count ?? 0 }} terjual</span>
                </div>
            </CardContent>
            <div class="flex gap-2 p-3 pt-0">
                <Button
                    variant="secondary"
                    size="sm"
                    class="flex-1 gap-2 text-xs"
                    @click="addToCart"
                >
                    <ShoppingBag class="h-3.5 w-3.5" />
                    <span class="hidden sm:inline">Add</span>
                </Button>
                <Button size="sm" class="flex-1 text-xs"> Buy </Button>
            </div>
        </Card>
    </Link>
</template>
