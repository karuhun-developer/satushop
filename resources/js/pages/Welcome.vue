<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as shopIndex, show as shopShow } from '@/routes/shop';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { ArrowRight, Star } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from '@/components/ui/carousel';

// Static Data
const categories = [
    {
        name: 'Electronics',
        image: 'https://images.unsplash.com/photo-1498049860654-af1a5c5668ba?auto=format&fit=crop&q=80&w=300&h=200',
    },
    {
        name: 'Fashion',
        image: 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&q=80&w=300&h=200',
    },
    {
        name: 'Home & Living',
        image: 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&q=80&w=300&h=200',
    },
    {
        name: 'Sports',
        image: 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&q=80&w=300&h=200',
    },
];

const featuredProducts = [
    {
        id: 1,
        name: 'Premium Wireless Headphones',
        price: 'Rp 2.500.000',
        rating: 4.8,
        category: 'Electronics',
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=400&h=400',
    },
    {
        id: 2,
        name: 'Minimalist Watch',
        price: 'Rp 1.200.000',
        rating: 4.5,
        category: 'Fashion',
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=400&h=400',
    },
    {
        id: 3,
        name: 'Ergonomic Office Chair',
        price: 'Rp 3.500.000',
        rating: 4.9,
        category: 'Home & Living',
        image: 'https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?auto=format&fit=crop&q=80&w=400&h=400',
    },
    {
        id: 4,
        name: 'Running Shoes Force',
        price: 'Rp 1.800.000',
        rating: 4.7,
        category: 'Sports',
        image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400&h=400',
    },
];
</script>

<template>
    <Head title="Welcome to E-Commerce" />

    <ShopLayout>
        <!-- Hero Section -->
        <section class="relative w-full bg-muted/20">
            <div class="container mx-auto px-4 py-8 md:py-12">
                <Carousel class="mx-auto w-full max-w-6xl">
                    <CarouselContent>
                        <!-- Slide 1 -->
                        <CarouselItem>
                            <div
                                class="grid items-center gap-8 overflow-hidden rounded-3xl border bg-card shadow-sm md:grid-cols-2"
                            >
                                <div class="space-y-6 p-8 md:p-12">
                                    <Badge class="px-3 py-1" variant="secondary"
                                        >New Season</Badge
                                    >
                                    <h1
                                        class="text-4xl font-extrabold tracking-tight text-foreground md:text-6xl"
                                    >
                                        Summer Collection 2024
                                    </h1>
                                    <p class="text-lg text-muted-foreground">
                                        Discover the latest trends in fashion
                                        and lifestyle. Premium quality for the
                                        modern individual.
                                    </p>
                                    <div class="flex gap-4 pt-4">
                                        <Link :href="shopIndex.url()">
                                            <Button
                                                size="lg"
                                                class="rounded-full px-8"
                                                >Shop Now</Button
                                            >
                                        </Link>
                                        <Button
                                            size="lg"
                                            variant="outline"
                                            class="rounded-full px-8"
                                            >View Lookbook</Button
                                        >
                                    </div>
                                </div>
                                <div class="relative h-[400px] w-full bg-muted">
                                    <img
                                        src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800&h=800"
                                        alt="Hero Image"
                                        class="absolute inset-0 h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </CarouselItem>
                        <!-- Slide 2 (Mockup) -->
                        <CarouselItem>
                            <div
                                class="grid items-center gap-8 overflow-hidden rounded-3xl border bg-card shadow-sm md:grid-cols-2"
                            >
                                <div class="space-y-6 p-8 md:p-12">
                                    <Badge class="px-3 py-1" variant="secondary"
                                        >Tech Deals</Badge
                                    >
                                    <h1
                                        class="text-4xl font-extrabold tracking-tight text-foreground md:text-6xl"
                                    >
                                        Next Gen Audio
                                    </h1>
                                    <p class="text-lg text-muted-foreground">
                                        Experience sound like never before with
                                        our premium audio collection.
                                    </p>
                                    <div class="flex gap-4 pt-4">
                                        <Button
                                            size="lg"
                                            class="rounded-full px-8"
                                            >Listen Now</Button
                                        >
                                    </div>
                                </div>
                                <div class="relative h-[400px] w-full bg-muted">
                                    <img
                                        src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&q=80&w=800&h=800"
                                        alt="Headphones"
                                        class="absolute inset-0 h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                    <CarouselPrevious class="left-4" />
                    <CarouselNext class="right-4" />
                </Carousel>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="container mx-auto px-4 py-16">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold tracking-tight">
                    Shop by Category
                </h2>
                <Link
                    href="#"
                    class="flex items-center text-sm font-medium text-primary hover:underline"
                >
                    View All <ArrowRight class="ml-1 h-4 w-4" />
                </Link>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <Link
                    v-for="cat in categories"
                    :key="cat.name"
                    href="#"
                    class="group relative aspect-[4/3] overflow-hidden rounded-xl bg-muted"
                >
                    <img
                        :src="cat.image"
                        :alt="cat.name"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/40 transition-opacity group-hover:bg-black/50"
                    >
                        <span
                            class="border-b-2 border-transparent text-lg font-bold tracking-wide text-white transition-colors group-hover:border-white"
                        >
                            {{ cat.name }}
                        </span>
                    </div>
                </Link>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="bg-muted/30 py-16">
            <div class="container mx-auto px-4">
                <div class="mb-12 space-y-2 text-center">
                    <h2 class="text-3xl font-bold tracking-tight">
                        Featured Products
                    </h2>
                    <p class="text-muted-foreground">
                        Handpicked items just for you
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-4"
                >
                    <Card
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="group border-none bg-transparent shadow-none"
                    >
                        <div
                            class="relative mb-4 aspect-square overflow-hidden rounded-xl border bg-card"
                        >
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div class="absolute top-3 right-3">
                                <Button
                                    variant="secondary"
                                    size="icon"
                                    class="rounded-full opacity-0 shadow-sm transition-opacity group-hover:opacity-100"
                                >
                                    <Star class="h-4 w-4" />
                                </Button>
                            </div>
                            <div
                                class="absolute right-0 bottom-4 left-0 translate-y-4 px-4 opacity-0 transition-opacity duration-300 group-hover:translate-y-0 group-hover:opacity-100"
                            >
                                <Link
                                    :href="
                                        shopShow.url({ id: product.id })
                                    "
                                    class="w-full"
                                >
                                    <Button
                                        class="w-full rounded-full shadow-lg"
                                        >View Details</Button
                                    >
                                </Link>
                            </div>
                        </div>
                        <CardContent class="space-y-1 p-0">
                            <div class="text-xs text-muted-foreground">
                                {{ product.category }}
                            </div>
                            <h3 class="truncate font-medium">
                                {{ product.name }}
                            </h3>
                            <div class="flex items-center gap-1 text-sm">
                                <Star
                                    class="h-3 w-3 fill-primary text-primary"
                                />
                                <span class="text-muted-foreground">{{
                                    product.rating
                                }}</span>
                            </div>
                            <div class="text-lg font-bold">
                                {{ product.price }}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="mt-12 text-center">
                    <Link :href="shopIndex.url()">
                        <Button
                            variant="outline"
                            size="lg"
                            class="min-w-[200px] rounded-full"
                        >
                            Load More Products
                        </Button>
                    </Link>
                </div>
            </div>
        </section>

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
