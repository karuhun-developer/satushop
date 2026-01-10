<script setup lang="ts">
import ProductCard from '@/components/ProductCard.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
} from '@/components/ui/carousel';
import EmptyState from '@/components/ui/empty-state/EmptyState.vue';
import { Skeleton } from '@/components/ui/skeleton';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { index as shopIndex } from '@/routes/shop';
import { Deferred, Head, Link } from '@inertiajs/vue3';
import Autoplay from 'embla-carousel-autoplay';
import { Compass, ShoppingBag } from 'lucide-vue-next';

defineProps<{
    categories?: any[];
    bestProducts?: any[];
    newProducts?: any[];
    bestSellers?: any[];
}>();
</script>

<template>
    <Head title="Welcome to E-Commerce" />

    <ShopLayout>
        <!-- Home Container -->
        <div class="container mx-auto space-y-8 px-4 py-6">
            <!-- Hero Section -->
            <section class="grid grid-cols-1 gap-4 lg:grid-cols-12">
                <!-- Main Banner -->
                <div class="lg:col-span-12">
                    <Carousel
                        class="w-full"
                        :plugins="[
                            Autoplay({
                                delay: 5000,
                            }),
                        ]"
                    >
                        <CarouselContent>
                            <!-- Slide 1 -->
                            <CarouselItem>
                                <div
                                    class="relative h-[200px] w-full overflow-hidden rounded-xl md:h-[400px]"
                                >
                                    <!-- Background Image -->
                                    <div class="absolute inset-0">
                                        <img
                                            src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=1920&h=1080"
                                            alt="Hero Image"
                                            class="h-full w-full object-cover"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/40"
                                        ></div>
                                    </div>

                                    <!-- Content Overlay -->
                                    <div
                                        class="relative z-10 container mx-auto flex h-full flex-col items-start justify-center space-y-2 px-4 text-white md:space-y-4 md:px-8"
                                    >
                                        <Badge
                                            class="w-fit border-white/20 bg-white/20 px-2 py-0.5 text-xs text-white backdrop-blur-sm hover:bg-white/30 md:px-3 md:py-1 md:text-sm"
                                        >
                                            New Season
                                        </Badge>
                                        <h1
                                            class="max-w-xl text-xl font-extrabold tracking-tight md:text-5xl"
                                        >
                                            Summer Collection
                                        </h1>
                                        <p
                                            class="hidden max-w-lg text-base text-white/90 md:block md:text-lg"
                                        >
                                            Discover the latest trends in
                                            fashion and lifestyle.
                                        </p>
                                        <div class="flex gap-4 pt-2">
                                            <Link :href="shopIndex.url()">
                                                <Button
                                                    size="sm"
                                                    class="md:size-default rounded-full bg-white px-4 text-xs text-black hover:bg-white/90 md:px-8 md:text-sm"
                                                >
                                                    Shop Now
                                                </Button>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </CarouselItem>
                            <!-- Slide 2 -->
                            <CarouselItem>
                                <div
                                    class="relative h-[200px] w-full overflow-hidden rounded-xl md:h-[400px]"
                                >
                                    <div class="absolute inset-0 bg-black">
                                        <img
                                            src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&q=80&w=1920&h=1080"
                                            alt="Headphones"
                                            class="h-full w-full object-cover opacity-80"
                                        />
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"
                                        ></div>
                                    </div>
                                    <div
                                        class="relative z-10 container mx-auto flex h-full flex-col items-start justify-center space-y-2 px-4 text-white md:space-y-4 md:px-8"
                                    >
                                        <Badge
                                            class="w-fit border-none bg-primary px-2 py-0.5 text-xs text-primary-foreground md:px-3 md:py-1 md:text-sm"
                                        >
                                            Tech Deals
                                        </Badge>
                                        <h1
                                            class="max-w-xl text-xl font-extrabold tracking-tight md:text-5xl"
                                        >
                                            Next Gen Audio
                                        </h1>
                                        <p
                                            class="hidden max-w-lg text-base text-white/90 md:block md:text-lg"
                                        >
                                            Experience sound like never before.
                                        </p>
                                        <div class="flex gap-4 pt-2">
                                            <Button
                                                size="sm"
                                                class="md:size-default rounded-full px-4 text-xs md:px-8 md:text-sm"
                                            >
                                                Listen Now
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </CarouselItem>
                        </CarouselContent>
                    </Carousel>
                </div>
            </section>

            <!-- Categories (Pills) -->
            <section>
                <h3 class="mb-4 text-lg font-bold">Kategori Pilihan</h3>
                <Deferred data="categories">
                    <template #fallback>
                        <div class="flex gap-3 overflow-hidden">
                            <Skeleton
                                v-for="i in 6"
                                :key="i"
                                class="h-10 w-32 rounded-full"
                            />
                        </div>
                    </template>

                    <div class="no-scrollbar flex gap-3 overflow-x-auto pb-4">
                        <Link
                            v-for="cat in categories"
                            :key="cat.id"
                            href="#"
                            class="flex flex-shrink-0 items-center gap-2 rounded-full border bg-white px-4 py-2 shadow-sm transition-all hover:border-primary hover:text-primary"
                        >
                            <img
                                :src="cat.image"
                                class="h-6 w-6 rounded-full object-cover"
                            />
                            <span
                                class="text-sm font-medium whitespace-nowrap"
                                >{{ cat.name }}</span
                            >
                        </Link>
                    </div>
                </Deferred>
            </section>

            <!-- Best Products -->
            <section>
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1 rounded-full bg-primary"></div>
                    <h2 class="text-xl font-bold">Produk Terbaik</h2>
                </div>

                <Deferred data="bestProducts">
                    <template #fallback>
                        <div
                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                        >
                            <div v-for="i in 6" :key="i" class="space-y-3">
                                <Skeleton
                                    class="aspect-square w-full rounded-lg"
                                />
                                <div class="space-y-2 px-1">
                                    <Skeleton class="h-4 w-full" />
                                    <Skeleton class="h-4 w-2/3" />
                                </div>
                            </div>
                        </div>
                    </template>

                    <div
                        v-if="bestProducts && bestProducts.length > 0"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                    >
                        <ProductCard
                            v-for="product in bestProducts"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                    <EmptyState
                        v-else
                        title="No Products Found"
                        description="We couldn't find any best products at the moment."
                        :icon="ShoppingBag"
                        class="min-h-[200px]"
                    />
                </Deferred>
            </section>

            <!-- New Products -->
            <section>
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1 rounded-full bg-primary"></div>
                    <h2 class="text-xl font-bold">Produk Terbaru</h2>
                    <span
                        class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-600"
                        >New Arrival</span
                    >
                </div>

                <Deferred data="newProducts">
                    <template #fallback>
                        <div
                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                        >
                            <div v-for="i in 6" :key="i" class="space-y-3">
                                <Skeleton
                                    class="aspect-square w-full rounded-lg"
                                />
                                <div class="space-y-2 px-1">
                                    <Skeleton class="h-4 w-full" />
                                    <Skeleton class="h-4 w-2/3" />
                                </div>
                            </div>
                        </div>
                    </template>

                    <div
                        v-if="newProducts && newProducts.length > 0"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                    >
                        <ProductCard
                            v-for="product in newProducts"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                    <EmptyState
                        v-else
                        title="No Products Found"
                        description="We couldn't find any new products at the moment."
                        :icon="ShoppingBag"
                        class="min-h-[200px]"
                    />
                </Deferred>
            </section>

            <!-- Best Sellers -->
            <section>
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1 rounded-full bg-primary"></div>
                    <h2 class="text-xl font-bold">Paling Laris</h2>
                    <span
                        class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-600"
                        >Hot</span
                    >
                </div>

                <Deferred data="bestSellers">
                    <template #fallback>
                        <div
                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                        >
                            <div v-for="i in 6" :key="i" class="space-y-3">
                                <Skeleton
                                    class="aspect-square w-full rounded-lg"
                                />
                                <div class="space-y-2 px-1">
                                    <Skeleton class="h-4 w-full" />
                                    <Skeleton class="h-4 w-2/3" />
                                </div>
                            </div>
                        </div>
                    </template>

                    <div
                        v-if="bestSellers && bestSellers.length > 0"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6"
                    >
                        <ProductCard
                            v-for="product in bestSellers"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                    <EmptyState
                        v-else
                        title="No Products Found"
                        description="We couldn't find any best selling products at the moment."
                        :icon="ShoppingBag"
                        class="min-h-[200px]"
                    />
                </Deferred>
            </section>
        </div>

        <!-- Load More -->
        <div class="mt-8 pb-12 text-center">
            <Link :href="shopIndex.url()">
                <Button
                    variant="outline"
                    size="lg"
                    class="min-w-[200px] rounded-lg border-primary text-primary hover:bg-green-50"
                >
                    <Compass class="h-6 w-6" />
                    Explore
                </Button>
            </Link>
        </div>

        <!-- Newsletter / CTA -->
        <section class="container mx-auto px-4 py-24">
            <div
                class="relative overflow-hidden rounded-3xl bg-primary p-8 text-center text-primary-foreground md:p-16"
            >
                <div class="relative z-10 mx-auto max-w-2xl space-y-6">
                    <h2 class="text-3xl font-bold md:text-4xl">
                        Join our Community
                    </h2>
                    <p class="text-lg text-primary-foreground/80">
                        Subscribe to our newsletter to receive exclusive offers,
                        latest news and updates.
                    </p>
                    <div
                        class="mx-auto flex max-w-md flex-col gap-3 sm:flex-row"
                    >
                        <input
                            type="email"
                            placeholder="Enter your email"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        />
                        <Button
                            variant="secondary"
                            class="bg-white text-black hover:bg-white/90"
                        >
                            Subscribe
                        </Button>
                    </div>
                </div>
            </div>
        </section>
    </ShopLayout>
</template>
