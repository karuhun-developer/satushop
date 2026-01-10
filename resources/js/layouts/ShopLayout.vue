<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { urlIsActive } from '@/lib/utils';
import { home } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { index as checkoutIndex } from '@/routes/checkout';
import { index as shopIndex } from '@/routes/shop';
import { Link } from '@inertiajs/vue3';
import {
    Compass,
    FileText,
    Home,
    Search,
    ShoppingCart,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const isMobileMenuOpen = ref(false);

const cartItems = ref([
    {
        id: 1,
        name: 'Premium Wireless Headphones',
        price: 2500000,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=200&h=200',
    },
    {
        id: 2,
        name: 'Minimalist Watch',
        price: 1200000,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=200&h=200',
    },
]);

const cartTotal = computed(() => {
    return cartItems.value.reduce(
        (total, item) => total + item.price * item.quantity,
        0,
    );
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(price);
};

const navLinks = [
    { name: 'Home', href: home.url() },
    { name: 'Shop', href: shopIndex.url() },
    { name: 'Categories', href: '#' },
    { name: 'New Arrivals', href: '#' },
    { name: 'About', href: '#' },
];
</script>

<template>
    <div class="min-h-screen bg-background font-sans antialiased">
        <!-- Top Bar -->
        <div
            class="hidden border-b bg-muted/50 py-1 text-[11px] text-muted-foreground md:block"
        >
            <div
                class="relative container mx-auto flex items-center justify-between px-4"
            >
                <div class="flex gap-4">
                    <span class="cursor-pointer hover:text-primary"
                        >Tentang Tokohebat</span
                    >
                    <span class="cursor-pointer hover:text-primary"
                        >Mitra Tokohebat</span
                    >
                    <span class="cursor-pointer hover:text-primary"
                        >Mulai Berjualan</span
                    >
                    <!-- Categories (Kategori) & Explore -->
                </div>
                <!-- Explore Button Removed -->
                <div class="flex gap-4">
                    <span>Download Tokohebat App</span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-background">
            <div
                class="container mx-auto flex h-16 items-center justify-between gap-4 px-4"
            >
                <!-- Mobile Menu Trigger Removed -->

                <!-- Logo (Tokohebat Style) -->
                <div class="mr-2 flex-shrink-0 md:mr-4">
                    <Link
                        :href="home.url()"
                        class="flex items-center gap-2 text-2xl font-bold tracking-tight text-primary"
                    >
                        <span class="hidden md:inline">Tokohebat</span>
                        <span class="md:hidden">tkhb</span>
                    </Link>
                </div>

                <!-- Explore Button -->
                <div class="mr-4 hidden items-center md:flex">
                    <Link
                        :href="shopIndex.url()"
                        class="flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-primary"
                        :class="{
                            'bg-muted text-primary': urlIsActive(
                                shopIndex.url(),
                                $page.url,
                            ),
                        }"
                    >
                        <Compass class="h-4 w-4" />
                        <span>Explore</span>
                    </Link>
                </div>

                <!-- Search (Tokohebat Style: Wide & Central) -->
                <div class="relative flex max-w-3xl flex-1 gap-2">
                    <div class="group relative w-full">
                        <Search
                            class="absolute top-2.5 left-3 h-4 w-4 text-muted-foreground group-focus-within:text-primary"
                        />
                        <Input
                            type="search"
                            placeholder="Cari..."
                            class="h-10 w-full rounded-lg border-gray-200 pr-10 pl-10 transition-all focus-visible:border-primary focus-visible:ring-primary md:placeholder:text-muted-foreground"
                        />
                    </div>
                    <Button
                        size="icon"
                        variant="secondary"
                        class="bg-gray-100 text-gray-600 hover:bg-gray-200 md:w-auto md:px-4"
                    >
                        <Search class="h-4 w-4 md:hidden" />
                        <span class="hidden md:inline">Cari</span>
                    </Button>
                </div>

                <!-- Right Actions (Divider | Auth/Cart) -->
                <div class="ml-4 flex items-center gap-2">
                    <!-- Mobile Search Toggle (Could implement later) -->

                    <!-- Cart -->
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative"
                            >
                                <ShoppingCart class="h-5 w-5" />
                                <Badge
                                    v-if="cartItems.length > 0"
                                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center p-0 text-[10px]"
                                >
                                    {{ cartItems.length }}
                                </Badge>
                                <span class="sr-only">Open cart</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-80 p-0">
                            <DropdownMenuLabel class="p-4 font-normal">
                                <div class="flex flex-col space-y-1">
                                    <p class="text-sm leading-none font-medium">
                                        Shopping Cart
                                    </p>
                                    <p
                                        class="text-xs leading-none text-muted-foreground"
                                    >
                                        {{ cartItems.length }} items
                                    </p>
                                </div>
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />

                            <!-- Items -->
                            <div
                                class="max-h-[300px] space-y-4 overflow-y-auto p-4"
                            >
                                <div
                                    v-for="item in cartItems"
                                    :key="item.id"
                                    class="flex gap-4"
                                >
                                    <div
                                        class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-md bg-muted"
                                    >
                                        <img
                                            :src="item.image"
                                            :alt="item.name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1 space-y-1">
                                        <p
                                            class="truncate text-sm leading-none font-medium"
                                        >
                                            {{ item.name }}
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ item.quantity }} x
                                            {{ formatPrice(item.price) }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-if="cartItems.length === 0"
                                    class="py-6 text-center text-sm text-muted-foreground"
                                >
                                    Your cart is empty
                                </div>
                            </div>

                            <DropdownMenuSeparator
                                v-if="cartItems.length > 0"
                            />

                            <!-- Footer -->
                            <div
                                v-if="cartItems.length > 0"
                                class="space-y-4 p-4 pt-2"
                            >
                                <div
                                    class="flex justify-between text-sm font-medium"
                                >
                                    <span>Total</span>
                                    <span>{{ formatPrice(cartTotal) }}</span>
                                </div>
                                <div class="grid gap-2">
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="cartIndex.url()"
                                            class="w-full cursor-pointer"
                                        >
                                            <Button
                                                variant="outline"
                                                class="w-full"
                                                size="sm"
                                                >View Cart</Button
                                            >
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="checkoutIndex.url()"
                                            class="w-full cursor-pointer"
                                        >
                                            <Button class="w-full" size="sm"
                                                >Checkout</Button
                                            >
                                        </Link>
                                    </DropdownMenuItem>
                                </div>
                            </div>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <!-- User Menu / Auth Buttons -->
                    <div
                        class="ml-2 hidden items-center gap-2 border-l pl-4 md:flex"
                    >
                        <Button variant="ghost" size="sm" class="font-bold">
                            Masuk
                        </Button>
                        <Button size="sm" class="font-bold"> Daftar </Button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t bg-muted/40">
            <div class="container mx-auto px-4 py-8 md:py-12">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold">About Us</h4>
                        <p class="text-sm text-muted-foreground">
                            Premium functional apparel for the modern lifestyle.
                            Quality materials, sustainable production.
                        </p>
                    </div>
                    <div>
                        <h4 class="mb-4 text-sm font-semibold">Shop</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>
                                <a href="#" class="hover:text-primary"
                                    >All Products</a
                                >
                            </li>
                            <li>
                                <a href="#" class="hover:text-primary"
                                    >New Arrivals</a
                                >
                            </li>
                            <li>
                                <a href="#" class="hover:text-primary"
                                    >Featured</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-sm font-semibold">Support</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>
                                <a href="#" class="hover:text-primary">FAQ</a>
                            </li>
                            <li>
                                <a href="#" class="hover:text-primary"
                                    >Shipping</a
                                >
                            </li>
                            <li>
                                <a href="#" class="hover:text-primary"
                                    >Returns</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-sm font-semibold">Newsletter</h4>
                        <div class="flex gap-2">
                            <Input
                                placeholder="Enter your email"
                                class="max-w-[200px]"
                            />
                            <Button>Subscribe</Button>
                        </div>
                    </div>
                </div>
                <Separator class="my-8" />
                <div
                    class="flex flex-col items-center justify-between gap-4 text-xs text-muted-foreground md:flex-row"
                >
                    <p>&copy; 2024 E-Commerce Inc. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-primary"
                            >Privacy Policy</a
                        >
                        <a href="#" class="hover:text-primary"
                            >Terms of Service</a
                        >
                    </div>
                </div>
            </div>
        </footer>
        <!-- Mobile Bottom Navigation -->
        <div
            class="pb-safe fixed right-0 bottom-0 left-0 z-50 border-t bg-background md:hidden"
        >
            <div
                class="grid h-16 grid-cols-4 items-center justify-items-center"
            >
                <Link
                    :href="home.url()"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                    :class="{
                        'text-primary': home.url() === $page.url,
                    }"
                >
                    <Home class="h-6 w-6" />
                    <span class="text-[10px] font-medium">Home</span>
                </Link>
                <Link
                    :href="shopIndex.url()"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                    :class="{
                        'text-primary': urlIsActive(shopIndex.url(), $page.url),
                    }"
                >
                    <Compass class="h-6 w-6" />
                    <span class="text-[10px] font-medium">Explore</span>
                </Link>
                <Link
                    href="#"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                >
                    <FileText class="h-6 w-6" />
                    <span class="text-[10px] font-medium">History</span>
                </Link>

                <!-- Profile / Login -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button
                            class="flex flex-col items-center gap-1 text-muted-foreground outline-none hover:text-primary"
                        >
                            <User class="h-6 w-6" />
                            <span class="text-[10px] font-medium">Profile</span>
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="mb-2 w-56">
                        <DropdownMenuLabel>Account Action</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <div class="flex flex-col gap-2 p-2">
                            <Button class="w-full justify-start" variant="ghost"
                                >Masuk</Button
                            >
                            <Button
                                class="w-full justify-start bg-primary text-white"
                                >Daftar</Button
                            >
                        </div>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
    </div>
</template>
