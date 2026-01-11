<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MoneyInput from '@/components/ui/money-input/MoneyInput.vue';
import { Separator } from '@/components/ui/separator';
import { Skeleton } from '@/components/ui/skeleton';
import { explore } from '@/routes';
import { category } from '@/routes/explore';
import { AttributeDataItem } from '@/types/cms/attribute';
import { ProductCategoryDataItem } from '@/types/cms/catalog';
import { Deferred, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    currentCategory?: ProductCategoryDataItem;
    categories?: ProductCategoryDataItem[];
    attributes?: AttributeDataItem[];
    filters?: {
        attribute_option_id?: string[] | number[];
        price_min?: string;
        price_max?: string;
        search?: string;
    };
}>();

// Filter State
const selectedAttributeOptions = ref<number[]>(
    props.filters?.attribute_option_id?.map(Number) || [],
);
const priceRange = ref<{ min: string | number; max: string | number }>({
    min: props.filters?.price_min || '',
    max: props.filters?.price_max || '',
});

// Manual Filter Application
const applyFilters = () => {
    const params: any = {};
    if (selectedAttributeOptions.value.length) {
        params.attribute_option_id = selectedAttributeOptions.value;
    }
    if (priceRange.value.min) params.price_min = priceRange.value.min;
    if (priceRange.value.max) params.price_max = priceRange.value.max;

    // Merge with existing params (like search)
    const currentParams = new URLSearchParams(window.location.search);
    if (currentParams.has('search')) {
        params.search = currentParams.get('search');
    }

    router.get(window.location.pathname, params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const toggleAttributeOption = (optionId: number) => {
    const index = selectedAttributeOptions.value.indexOf(optionId);
    if (index === -1) {
        selectedAttributeOptions.value.push(optionId);
    } else {
        selectedAttributeOptions.value.splice(index, 1);
    }
};

const resetFilters = () => {
    selectedAttributeOptions.value = [];
    priceRange.value.min = '';
    priceRange.value.max = '';

    // Check if we have search param to preserve
    const currentParams = new URLSearchParams(window.location.search);
    const search = currentParams.get('search');

    const params: any = {};
    if (search) params.search = search;

    router.get(window.location.pathname, params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <aside class="hidden space-y-6 md:block">
        <!-- Categories (Deferred) -->
        <Deferred data="categories">
            <template #fallback>
                <div class="space-y-4">
                    <h3 class="font-semibold">Categories</h3>
                    <div class="space-y-2">
                        <Skeleton v-for="i in 5" :key="i" class="h-6 w-full" />
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
                                currentCategory === null ||
                                currentCategory === undefined,
                        }"
                        :href="explore.url()"
                    >
                        <span class="text-sm"> All Categories </span>
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
                        <Skeleton v-for="i in 3" :key="i" class="h-10 w-full" />
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
                            class="cursor-pointer rounded-md border px-3 py-1 text-sm transition-colors"
                            :class="{
                                'bg-primary text-primary-foreground hover:bg-primary/90':
                                    selectedAttributeOptions.includes(opt.id),
                                'bg-background hover:bg-muted':
                                    !selectedAttributeOptions.includes(opt.id),
                            }"
                            @click="toggleAttributeOption(opt.id)"
                        >
                            {{ opt.name }}
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="space-y-4">
                    <h3 class="font-semibold">Price Range</h3>
                    <div class="flex items-center gap-2">
                        <div class="grid gap-1">
                            <Label for="min-price" class="text-xs">Min</Label>
                            <MoneyInput
                                id="min-price"
                                v-model="priceRange.min"
                                placeholder="0"
                                class="h-8"
                            />
                        </div>
                        <span class="mt-4 text-muted-foreground">-</span>
                        <div class="grid gap-1">
                            <Label for="max-price" class="text-xs">Max</Label>
                            <MoneyInput
                                id="max-price"
                                v-model="priceRange.max"
                                placeholder="Max"
                                class="h-8"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <Button class="w-full" @click="applyFilters">
                        Apply Filters
                    </Button>
                    <Button
                        variant="outline"
                        class="w-full"
                        @click="resetFilters"
                        v-if="
                            selectedAttributeOptions.length ||
                            priceRange.min ||
                            priceRange.max
                        "
                    >
                        Reset Filters
                    </Button>
                </div>
            </div>
        </Deferred>
    </aside>
</template>
