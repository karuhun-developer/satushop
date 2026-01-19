<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    type CarouselApi,
} from '@/components/ui/carousel';
import { EmptyState } from '@/components/ui/empty-state';
import { Separator } from '@/components/ui/separator';
import { useCartStore } from '@/composables/useCartStore';
import { useSwal } from '@/composables/useSwal';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { formatCurrency } from '@/lib/utils';
import { explore } from '@/routes';
import { ProductFlatDataItem } from '@/types/cms/catalog';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Heart,
    ShieldCheck,
    ShoppingCart,
    Star,
    Truck,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    product?: ProductFlatDataItem;
}>();

const api = ref<CarouselApi>();
const activeIndex = ref(0);

const quantity = ref(1);
const selectedOptions = ref<Record<string, number>>({});

const cart = useCartStore();
const { toast } = useSwal();

const matchingVariant = computed(() => {
    if (!props.product?.variants?.length) return null;

    const match = props.product.variants.find((variant) => {
        const variantAttributes = variant.variant_product?.attributes;
        if (!variantAttributes) return false;

        // Check if all selected options match this variant
        const selectedEntries = Object.entries(selectedOptions.value);
        if (selectedEntries.length === 0) return false;

        return selectedEntries.every(([code, optionId]) => {
            // Find the attribute_id for this code from main product
            const mainAttr = props.product?.product?.attributes?.find(
                (attr) => attr.attribute?.code === code,
            );
            if (!mainAttr) return false;

            return variantAttributes.some(
                (attr) =>
                    attr.attribute_id === mainAttr.attribute_id &&
                    attr.attribute_option_id === optionId,
            );
        });
    });

    // console.log('Matching variant:', match);
    // console.log('Selected options:', selectedOptions.value);
    return match;
});

// Computed Images - Show all images from main product and all variants
const images = computed(() => {
    const imgs: string[] = [];

    // Always collect main product images first
    if (props.product) {
        for (let i = 1; i <= 10; i++) {
            const key = `image_${i}` as keyof ProductFlatDataItem;
            // @ts-ignore
            const img = props.product[key] as string | undefined;
            if (img && !imgs.includes(img)) {
                imgs.push(img);
            }
        }
    }

    // Add all variant images (from all variants, not just matching one)
    if (props.product?.variants) {
        props.product.variants.forEach((variant) => {
            const variantProduct = variant.variant_product;
            if (variantProduct) {
                for (let i = 1; i <= 10; i++) {
                    const key = `image_${i}` as keyof ProductFlatDataItem;
                    // @ts-ignore
                    const img = variantProduct[key] as string | undefined;
                    if (img && !imgs.includes(img)) {
                        imgs.push(img);
                    }
                }
            }
        });
    }

    return imgs.length > 0
        ? imgs
        : ['https://placehold.co/600x600?text=No+Image'];
});

// Computed Categories
const categories = computed(() => {
    if (!props.product?.categories?.length) return [];

    return props.product.categories
        .map((cat) => cat.product_category?.name)
        .filter((name): name is string => !!name);
});

// Computed Attributes (Variants) - Only show options that exist in variants
const groupedAttributes = computed(() => {
    if (!props.product?.variants?.length) return [];

    // Collect all unique attribute options from all variants
    const attributeOptionsMap = new Map<number, Set<number>>();

    props.product.variants.forEach((variant) => {
        variant.variant_product?.attributes?.forEach((attr) => {
            if (!attributeOptionsMap.has(attr.attribute_id)) {
                attributeOptionsMap.set(attr.attribute_id, new Set());
            }
            if (attr.attribute_option_id) {
                attributeOptionsMap
                    .get(attr.attribute_id)!
                    .add(attr.attribute_option_id);
            }
        });
    });

    // Build grouped attributes from main product attributes
    const grouped: Array<{
        id: number;
        code: string;
        name: string;
        options: Array<{ id: number; name: string }>;
    }> = [];

    attributeOptionsMap.forEach((optionIds, attributeId) => {
        // Find attribute info from main product
        const mainAttr = props.product?.product?.attributes?.find(
            (attr) => attr.attribute_id === attributeId,
        );
        if (!mainAttr?.attribute) return;

        // Get all options for this attribute from flat_attributes
        const flatAttrGroup = props.product?.flat_attributes?.[attributeId];
        if (!flatAttrGroup) return;

        const options = flatAttrGroup
            .filter(
                (attr) =>
                    attr.attribute_option_id &&
                    optionIds.has(attr.attribute_option_id),
            )
            .map((attr) => attr.attribute_option)
            .filter((opt): opt is NonNullable<typeof opt> => !!opt);

        // Remove duplicates
        const uniqueOptions = Array.from(
            new Map(options.map((item) => [item.id, item])).values(),
        );

        if (uniqueOptions.length > 0) {
            grouped.push({
                id: mainAttr.attribute.id,
                code: mainAttr.attribute.code,
                name: mainAttr.attribute.name,
                options: uniqueOptions,
            });
        }
    });

    return grouped;
});

// Formatted Price
const formattedPrice = computed(() => {
    const productData = matchingVariant.value?.variant_product || props.product;
    const price = Number(productData?.price || 0);
    return formatCurrency(price);
});

// Product Title
const productTitle = computed(() => {
    return (
        matchingVariant.value?.variant_product?.name ||
        props.product?.name ||
        ''
    );
});

// Product Description
const productDescription = computed(() => {
    return (
        matchingVariant.value?.variant_product?.description ||
        props.product?.description ||
        ''
    );
});

// Watch changes to set default selections
watch(
    () => groupedAttributes.value,
    (attrs) => {
        if (attrs.length && Object.keys(selectedOptions.value).length === 0) {
            attrs.forEach((attr) => {
                if (attr.options.length) {
                    selectedOptions.value[attr.code] = attr.options[0].id;
                }
            });
        }
    },
    { immediate: true },
);

// Watch API changes
watch(api, (api) => {
    if (!api) return;

    api.on('select', () => {
        activeIndex.value = api.selectedScrollSnap();
    });
});

// Watch matching variant to auto-scroll to variant images
watch(matchingVariant, (newVariant) => {
    if (!newVariant?.variant_product || !api.value) return;

    // Find the index of the first image from this variant
    const variantFirstImage = newVariant.variant_product.image_1;
    if (!variantFirstImage) return;

    const imageIndex = images.value.indexOf(variantFirstImage);
    if (imageIndex !== -1) {
        api.value.scrollTo(imageIndex);
    }
});

const onThumbClick = (index: number) => {
    if (!api.value) return;
    api.value.scrollTo(index);
};

const toggleOption = (attrCode: string, optionId: number) => {
    // If clicking the same option, unselect it
    if (selectedOptions.value[attrCode] === optionId) {
        delete selectedOptions.value[attrCode];
    } else {
        selectedOptions.value[attrCode] = optionId;
    }
};

const addToCart = () => {
    const productToAdd =
        matchingVariant.value?.variant_product || props.product;
    if (!productToAdd) return;

    cart.addItem(productToAdd, quantity.value, selectedOptions.value);

    toast.fire({
        icon: 'success',
        title: 'Added to cart',
        text: `${productToAdd.name} has been added to your cart.`,
    });
};

const checkout = () => {
    const productToAdd =
        matchingVariant.value?.variant_product || props.product;
    if (!productToAdd) return;

    cart.addItem(productToAdd, quantity.value, selectedOptions.value);
};
</script>

<template>
    <Head :title="product ? product.name : 'Product Not Found'" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <div v-if="product" class="space-y-8">
                <!-- Breadcrumb / Back -->
                <div
                    class="flex items-center gap-2 text-sm text-muted-foreground"
                >
                    <Link
                        :href="explore.url()"
                        class="flex items-center gap-1 hover:text-foreground"
                    >
                        <ArrowLeft class="h-4 w-4" /> Back to Shop
                    </Link>
                    <span>/</span>
                    <span class="max-w-[200px] truncate">{{
                        product.name
                    }}</span>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:gap-12">
                    <!-- Image Gallery -->
                    <div class="space-y-4">
                        <div
                            class="relative aspect-square overflow-hidden rounded-2xl border bg-muted"
                        >
                            <Carousel
                                class="h-full w-full"
                                @init-api="(val) => (api = val)"
                            >
                                <CarouselContent>
                                    <CarouselItem
                                        v-for="(img, idx) in images"
                                        :key="idx"
                                    >
                                        <div
                                            class="flex h-full items-center justify-center p-0"
                                        >
                                            <img
                                                :src="img"
                                                :alt="product.name"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                    </CarouselItem>
                                </CarouselContent>
                            </Carousel>
                        </div>
                        <div
                            v-if="images.length > 1"
                            class="grid grid-cols-4 gap-4"
                        >
                            <div
                                v-for="(img, idx) in images"
                                :key="idx"
                                class="aspect-square cursor-pointer overflow-hidden rounded-xl border bg-muted transition-all hover:ring-2 hover:ring-primary"
                                :class="{
                                    'ring-2 ring-primary': activeIndex === idx,
                                }"
                                @click="onThumbClick(idx)"
                            >
                                <img
                                    :src="img"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-6">
                        <div>
                            <div
                                v-if="categories.length > 0"
                                class="mb-2 flex flex-wrap gap-2"
                            >
                                <Badge
                                    v-for="(cat, idx) in categories"
                                    :key="idx"
                                    variant="outline"
                                >
                                    {{ cat }}
                                </Badge>
                            </div>
                            <h1
                                class="text-3xl font-bold tracking-tight text-foreground md:text-4xl"
                            >
                                {{ productTitle }}
                            </h1>
                            <div class="mt-2 flex items-center gap-4">
                                <div class="flex items-center gap-1">
                                    <Star
                                        class="h-5 w-5 fill-yellow-400 text-yellow-400"
                                    />
                                    <span class="font-medium">{{
                                        product.rating
                                    }}</span>
                                    <span class="text-muted-foreground"
                                        >({{ product.sold_count }} sold)</span
                                    >
                                </div>
                                <Separator orientation="vertical" class="h-5" />
                                <span class="font-medium text-green-600"
                                    >In Stock</span
                                >
                            </div>
                        </div>

                        <div class="text-3xl font-bold text-primary">
                            {{ formattedPrice }}
                        </div>

                        <div
                            class="prose text-muted-foreground"
                            v-html="productDescription"
                        ></div>

                        <Separator />

                        <!-- Dynamic Variants (Attributes) -->
                        <div v-if="groupedAttributes.length" class="space-y-4">
                            <div
                                v-for="attr in groupedAttributes"
                                :key="attr.id"
                            >
                                <h4 class="mb-2 text-sm font-medium capitalize">
                                    {{ attr.name }}
                                </h4>

                                <div class="flex flex-wrap gap-2">
                                    <!-- Options -->
                                    <Button
                                        v-for="opt in attr.options"
                                        :key="opt.id"
                                        type="button"
                                        :variant="
                                            selectedOptions[attr.code] ===
                                            opt.id
                                                ? 'default'
                                                : 'outline'
                                        "
                                        size="sm"
                                        class="min-w-[3rem]"
                                        @click="toggleOption(attr.code, opt.id)"
                                    >
                                        {{ opt.name }}
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <div class="flex items-center rounded-md border">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="quantity > 1 && quantity--"
                                    >-</Button
                                >
                                <span class="w-12 text-center">{{
                                    quantity
                                }}</span>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="quantity++"
                                    >+</Button
                                >
                            </div>
                            <Button
                                size="lg"
                                class="flex-1 gap-2"
                                @click="addToCart"
                            >
                                <ShoppingCart class="h-5 w-5" />
                                Add to Cart
                            </Button>
                            <Button
                                size="lg"
                                variant="secondary"
                                class="flex-1 gap-2"
                            >
                                <ShieldCheck class="h-5 w-5" />
                                Buy Now
                            </Button>
                            <Button size="lg" variant="outline" class="px-3">
                                <Heart class="h-5 w-5" />
                            </Button>
                        </div>

                        <div
                            class="flex items-center justify-between gap-4 pt-6 text-sm"
                        >
                            <div
                                class="flex items-center gap-2 text-muted-foreground"
                            >
                                <Truck class="h-4 w-4" /> Free Shipping
                            </div>
                            <div
                                class="flex items-center gap-2 text-muted-foreground"
                            >
                                <ShieldCheck class="h-4 w-4" /> 2 Year Warranty
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State (Edge Case) -->
            <div v-else class="py-20">
                <EmptyState
                    title="Product Not Found"
                    description="The product you are looking for does not exist or has been removed."
                    actionLabel="Back to Shop"
                    @action="$inertia.visit(explore.url())"
                />
            </div>
        </div>
    </ShopLayout>
</template>
