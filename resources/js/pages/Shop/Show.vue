<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { EmptyState } from '@/components/ui/empty-state';
import { Separator } from '@/components/ui/separator';
import { useCartStore } from '@/composables/useCartStore';
import { useSwal } from '@/composables/useSwal';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { explore } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Heart,
    ShieldCheck,
    ShoppingCart,
    Star,
    Truck,
} from 'lucide-vue-next';
import { ref } from 'vue';

interface Product {
    id: number;
    name: string;
    price: string;
    description: string;
    rating: number;
    category: string;
    images: string[];
}

const props = defineProps<{
    product?: Product;
}>();

const quantity = ref(1);
const selectedColor = ref('Black');
const selectedSize = ref('M');

const colors = ['Black', 'White', 'Blue'];
const sizes = ['S', 'M', 'L', 'XL'];

const cart = useCartStore();
const { toast } = useSwal();

const addToCart = () => {
    if (!props.product) return;

    cart.addItem(props.product, quantity.value, {
        color: selectedColor.value,
        size: selectedSize.value,
    });

    toast.fire({
        icon: 'success',
        title: 'Added to cart',
        text: `${props.product.name} has been added to your cart.`,
    });
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
                    <span class="text-foreground">{{ product.category }}</span>
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
                            <img
                                :src="product.images[0]"
                                :alt="product.name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div
                                v-for="(img, idx) in product.images"
                                :key="idx"
                                class="aspect-square cursor-pointer overflow-hidden rounded-xl border bg-muted transition-all hover:ring-2 hover:ring-primary"
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
                            <Badge variant="outline" class="mb-2">{{
                                product.category
                            }}</Badge>
                            <h1
                                class="text-3xl font-bold tracking-tight md:text-4xl"
                            >
                                {{ product.name }}
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
                                        >(120 reviews)</span
                                    >
                                </div>
                                <Separator orientation="vertical" class="h-5" />
                                <span class="font-medium text-green-600"
                                    >In Stock</span
                                >
                            </div>
                        </div>

                        <div class="text-3xl font-bold text-primary">
                            {{ product.price }}
                        </div>

                        <p class="leading-relaxed text-muted-foreground">
                            {{ product.description }}
                        </p>

                        <Separator />

                        <!-- Variants -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="mb-2 text-sm font-medium">Color</h4>
                                <div class="flex gap-2">
                                    <div
                                        v-for="color in colors"
                                        :key="color"
                                        class="h-8 w-8 cursor-pointer rounded-full border-2 transition-all"
                                        :class="
                                            selectedColor === color
                                                ? 'border-transparent ring-2 ring-primary ring-offset-2'
                                                : 'border-input hover:border-primary'
                                        "
                                        :style="{
                                            backgroundColor:
                                                color.toLowerCase(),
                                        }"
                                        @click="selectedColor = color"
                                        :title="color"
                                    ></div>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-2 text-sm font-medium">Size</h4>
                                <div class="flex gap-2">
                                    <Button
                                        v-for="size in sizes"
                                        :key="size"
                                        variant="outline"
                                        size="sm"
                                        :class="{
                                            'border-primary bg-primary text-primary-foreground':
                                                selectedSize === size,
                                        }"
                                        @click="selectedSize = size"
                                    >
                                        {{ size }}
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
                                <ShoppingCart class="h-5 w-5" /> Add to Cart
                            </Button>
                            <Button size="lg" variant="outline" class="px-3">
                                <Heart class="h-5 w-5" />
                            </Button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 text-sm">
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
