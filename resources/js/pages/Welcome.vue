<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
} from '@/components/ui/carousel';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { index as shopIndex, show as shopShow } from '@/routes/shop';
import { Head, Link } from '@inertiajs/vue3';
import Autoplay from 'embla-carousel-autoplay';
import { ArrowRight, Star } from 'lucide-vue-next';

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
        <section class="relative w-full bg-background">
            <div class="w-full">
                <Carousel
                    class="w-full bg-black"
                    :plugins="[
                        Autoplay({
                            delay: 10000,
                        }),
                    ]"
                >
                    <CarouselContent>
                        <!-- Slide 1 -->
                        <CarouselItem>
                            <div
                                class="relative h-[600px] w-full overflow-hidden"
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
                                <div
                                    class="relative z-10 container mx-auto flex h-full flex-col items-start justify-center space-y-6 px-4 text-white"
                                >
                                    <Badge
                                        class="w-fit border-white/20 bg-white/20 px-3 py-1 text-white backdrop-blur-sm hover:bg-white/30"
                                    >
                                        New Season
                                    </Badge>
                                    <h1
                                        class="max-w-3xl text-5xl font-extrabold tracking-tight md:text-7xl"
                                    >
                                        Summer Collection 2024
                                    </h1>
                                    <p
                                        class="max-w-2xl text-lg text-white/90 md:text-xl"
                                    >
                                        Discover the latest trends in fashion
                                        and lifestyle. Premium quality for the
                                        modern individual.
                                    </p>
                                    <div class="flex gap-4 pt-4">
                                        <Link :href="shopIndex.url()">
                                            <Button
                                                size="lg"
                                                class="rounded-full bg-white px-8 text-black hover:bg-white/90"
                                            >
                                                Shop Now
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </CarouselItem>
                        <!-- Slide 2 (Mockup) -->
                        <CarouselItem>
                            <div
                                class="relative h-[600px] w-full overflow-hidden"
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
                                    class="relative z-10 container mx-auto flex h-full flex-col items-start justify-center space-y-6 px-4 text-white"
                                >
                                    <Badge
                                        class="w-fit border-none bg-primary px-3 py-1 text-primary-foreground"
                                    >
                                        Tech Deals
                                    </Badge>
                                    <h1
                                        class="max-w-3xl text-5xl font-extrabold tracking-tight md:text-7xl"
                                    >
                                        Next Gen Audio
                                    </h1>
                                    <p
                                        class="max-w-2xl text-lg text-white/90 md:text-xl"
                                    >
                                        Experience sound like never before with
                                        our premium audio collection.
                                    </p>
                                    <div class="flex gap-4 pt-4">
                                        <Button
                                            size="lg"
                                            class="rounded-full px-8"
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
                                    :href="shopShow.url({ id: product.id })"
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
