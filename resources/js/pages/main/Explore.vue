<script setup lang="ts">
import ProductCard from '@/components/ProductCard.vue';
import { EmptyState } from '@/components/ui/empty-state';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { Skeleton } from '@/components/ui/skeleton';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { explore } from '@/routes';
import { category } from '@/routes/explore';
import { PaginationItem } from '@/types';
import { AttributeDataItem } from '@/types/cms/attribute';
import {
    ProductCategoryDataItem,
    ProductFlatDataItem,
} from '@/types/cms/catalog';
import { Deferred, Head, InfiniteScroll, Link } from '@inertiajs/vue3';
import { Loader2, ShoppingBag } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    currentCategory?: ProductCategoryDataItem;
    products: PaginationItem<ProductFlatDataItem>;
    categories?: ProductCategoryDataItem[];
    attributes?: AttributeDataItem[];
}>();

const searchTerm = ref('');
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
                <aside class="hidden space-y-6 md:block">
                    <!-- Search -->
                    <div>
                        <h3 class="mb-4 font-semibold">Search</h3>
                        <Input
                            v-model="searchTerm"
                            placeholder="Search products..."
                        />
                    </div>
                    <Separator />

                    <!-- Categories (Deferred) -->
                    <Deferred data="categories">
                        <template #fallback>
                            <div class="space-y-4">
                                <h3 class="font-semibold">Categories</h3>
                                <div class="space-y-2">
                                    <Skeleton
                                        v-for="i in 5"
                                        :key="i"
                                        class="h-6 w-full"
                                    />
                                </div>
                            </div>
                        </template>

                        <div v-if="categories">
                            <h3 class="mb-4 font-semibold">Categories</h3>
                            <div class="space-y-2">
                                <Link
                                    class="flex cursor-pointer items-center space-x-2 rounded-md p-2 hover:bg-muted/50"
                                    :class="{
                                        'bg-muted font-medium':
                                            currentCategory === null,
                                    }"
                                    :href="explore.url()"
                                >
                                    <span class="text-sm">
                                        All Categories
                                    </span>
                                </Link>
                                <Link
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    class="flex cursor-pointer items-center space-x-2 rounded-md p-2 hover:bg-muted/50"
                                    :class="{
                                        'bg-muted font-medium':
                                            currentCategory?.name === cat.name,
                                    }"
                                    :href="category.url(cat.slug)"
                                >
                                    <span class="text-sm">
                                        {{ cat.name }}
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </Deferred>

                    <Separator />

                    <!-- Attributes (Deferred) -->
                    <Deferred data="attributes">
                        <template #fallback>
                            <div class="space-y-4">
                                <h3 class="font-semibold">Filters</h3>
                                <div class="space-y-2">
                                    <Skeleton
                                        v-for="i in 3"
                                        :key="i"
                                        class="h-10 w-full"
                                    />
                                </div>
                            </div>
                        </template>

                        <div v-if="attributes" class="space-y-6">
                            <div v-for="attr in attributes" :key="attr.id">
                                <h3 class="mb-2 font-semibold">
                                    {{ attr.name }}
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <div
                                        v-for="opt in attr.options"
                                        :key="opt.id"
                                        class="cursor-pointer rounded-md border px-2 py-1 text-xs hover:border-primary"
                                    >
                                        {{ opt.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Deferred>
                </aside>

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
