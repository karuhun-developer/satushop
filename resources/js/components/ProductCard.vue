<script setup lang="ts">
import { show } from '@/actions/App/Http/Controllers/Main/ProductController';
import { Card, CardContent } from '@/components/ui/card';
import { formatCurrency } from '@/lib/utils';
import { ProductFlatDataItem } from '@/types/cms/catalog';
import { Link } from '@inertiajs/vue3';
import { ShoppingBag, Star } from 'lucide-vue-next';

const props = defineProps<{
    product: ProductFlatDataItem;
}>();
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
                <!-- Placeholder for product image if available in product_details -->
                <img
                    v-if="product.image_1"
                    :src="product.image_1"
                    :alt="product.image_1"
                    class="h-full w-full object-cover"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center text-muted-foreground"
                >
                    <ShoppingBag class="h-15 w-15 opacity-20" />
                </div>
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
        </Card>
    </Link>
</template>
