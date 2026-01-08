<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { index as shopIndex } from '@/routes/shop';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Star, Truck, ShieldCheck, ArrowLeft, ShoppingCart, Heart } from 'lucide-vue-next';
import { EmptyState } from '@/components/ui/empty-state';

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

</script>

<template>
    <Head :title="product ? product.name : 'Product Not Found'" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
             <div v-if="product" class="space-y-8">
                <!-- Breadcrumb / Back -->
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Link :href="shopIndex.url()" class="hover:text-foreground flex items-center gap-1">
                        <ArrowLeft class="h-4 w-4" /> Back to Shop
                    </Link>
                    <span>/</span>
                    <span class="text-foreground">{{ product.category }}</span>
                    <span>/</span>
                    <span class="truncate max-w-[200px]">{{ product.name }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                     <!-- Image Gallery -->
                     <div class="space-y-4">
                        <div class="bg-muted rounded-2xl overflow-hidden aspect-square relative border">
                             <img 
                                :src="product.images[0]" 
                                :alt="product.name" 
                                class="object-cover w-full h-full"
                             />
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div 
                                v-for="(img, idx) in product.images" 
                                :key="idx" 
                                class="bg-muted rounded-xl aspect-square overflow-hidden cursor-pointer border hover:ring-2 hover:ring-primary transition-all"
                            >
                                <img :src="img" class="object-cover w-full h-full" />
                            </div>
                        </div>
                     </div>

                     <!-- Product Info -->
                     <div class="space-y-6">
                        <div>
                            <Badge variant="outline" class="mb-2">{{ product.category }}</Badge>
                            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ product.name }}</h1>
                            <div class="flex items-center gap-4 mt-2">
                                <div class="flex items-center gap-1">
                                    <Star class="h-5 w-5 fill-yellow-400 text-yellow-400" />
                                    <span class="font-medium">{{ product.rating }}</span>
                                    <span class="text-muted-foreground">(120 reviews)</span>
                                </div>
                                <Separator orientation="vertical" class="h-5" />
                                <span class="text-green-600 font-medium">In Stock</span>
                            </div>
                        </div>

                        <div class="text-3xl font-bold text-primary">
                            {{ product.price }}
                        </div>

                        <p class="text-muted-foreground leading-relaxed">
                            {{ product.description }}
                        </p>

                        <Separator />

                        <!-- Variants -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium mb-2">Color</h4>
                                <div class="flex gap-2">
                                    <div 
                                        v-for="color in colors" 
                                        :key="color"
                                        class="h-8 w-8 rounded-full border-2 cursor-pointer transition-all"
                                        :class="selectedColor === color ? 'ring-2 ring-offset-2 ring-primary border-transparent' : 'border-input hover:border-primary'"
                                        :style="{ backgroundColor: color.toLowerCase() }"
                                        @click="selectedColor = color"
                                        :title="color"
                                    ></div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium mb-2">Size</h4>
                                <div class="flex gap-2">
                                    <Button 
                                        v-for="size in sizes" 
                                        :key="size"
                                        variant="outline"
                                        size="sm"
                                        :class="{ 'bg-primary text-primary-foreground border-primary': selectedSize === size }"
                                        @click="selectedSize = size"
                                    >
                                        {{ size }}
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <div class="flex items-center border rounded-md">
                                <Button variant="ghost" size="icon" @click="quantity > 1 && quantity--">-</Button>
                                <span class="w-12 text-center">{{ quantity }}</span>
                                <Button variant="ghost" size="icon" @click="quantity++">+</Button>
                            </div>
                            <Button size="lg" class="flex-1 gap-2">
                                <ShoppingCart class="h-5 w-5" /> Add to Cart
                            </Button>
                            <Button size="lg" variant="outline" class="px-3">
                                <Heart class="h-5 w-5" />
                            </Button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 text-sm">
                            <div class="flex items-center gap-2 text-muted-foreground">
                                <Truck class="h-4 w-4" /> Free Shipping
                            </div>
                            <div class="flex items-center gap-2 text-muted-foreground">
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
                    @action="$inertia.visit(shopIndex.url())"
                 />
             </div>
        </div>
    </ShopLayout>
</template>
