<script setup lang="ts">
import ExploreFilters from '@/components/main/explore/ExploreFilters.vue';
import ProductCard from '@/components/ProductCard.vue';
import { EmptyState } from '@/components/ui/empty-state';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { PaginationItem } from '@/types';
import { AttributeDataItem } from '@/types/cms/attribute';
import {
    ProductCategoryDataItem,
    ProductFlatDataItem,
} from '@/types/cms/catalog';
import { Head, InfiniteScroll } from '@inertiajs/vue3';
import { Loader2, ShoppingBag } from 'lucide-vue-next';

defineProps<{
    currentCategory?: ProductCategoryDataItem;
    products: PaginationItem<ProductFlatDataItem>;
    categories?: ProductCategoryDataItem[];
    attributes?: AttributeDataItem[];
    filters?: {
        attribute_option_id?: string[] | number[];
        price_min?: string;
        price_max?: string;
    };
}>();
</script>

<template>
    <Head title="Shop" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div
                class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center"
            >
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Shop</h1>
                    <p class="mt-1 text-muted-foreground">
                        Explore our premium collection
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <!-- Sidebar Filters -->
                <ExploreFilters
                    :current-category="currentCategory"
                    :categories="categories"
                    :attributes="attributes"
                    :filters="filters"
                />

                <!-- Product Grid -->
                <div class="md:col-span-3">
                    <InfiniteScroll data="products">
                        <div
                            v-if="products.data.length > 0"
                            class="grid grid-cols-1 gap-3 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <ProductCard
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                            />
                        </div>

                        <!-- Empty State -->
                        <div
                            v-if="products.data.length === 0"
                            class="flex h-full items-center justify-center"
                        >
                            <EmptyState
                                title="No products found"
                                description="We couldn't find any products."
                                :icon="ShoppingBag"
                            />
                        </div>

                        <template #loading>
                            <div class="flex justify-center py-8">
                                <Loader2
                                    class="h-6 w-6 animate-spin text-muted-foreground"
                                />
                            </div>
                        </template>
                    </InfiniteScroll>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>
