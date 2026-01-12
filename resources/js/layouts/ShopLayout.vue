<script setup lang="ts">
import CartDropdown from '@/components/CartDropdown.vue';
import ShopSearch from '@/components/ShopSearch.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { urlIsActive } from '@/lib/utils';
import { explore, home, login, register } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { Compass, FileText, Home, LogIn, User } from 'lucide-vue-next';

const page = usePage();
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
                        :href="explore.url()"
                        class="flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-primary"
                        :class="{
                            'bg-muted text-primary': urlIsActive(
                                explore.url(),
                                $page.url,
                            ),
                        }"
                    >
                        <Compass class="h-4 w-4" />
                        <span>Explore</span>
                    </Link>
                </div>

                <!-- Search (Tokohebat Style: Wide & Central) -->
                <ShopSearch />

                <!-- Right Actions (Divider | Auth/Cart) -->
                <div class="ml-4 flex items-center gap-2">
                    <!-- Mobile Search Toggle (Could implement later) -->

                    <!-- Cart -->
                    <CartDropdown />

                    <!-- User Menu / Auth Buttons -->
                    <div
                        class="ml-2 hidden items-center gap-2 border-l pl-4 md:flex"
                    >
                        <Link
                            v-if="page.props.auth.user"
                            :href="home.url()"
                            class="flex items-center gap-1 text-muted-foreground hover:text-primary"
                        >
                            <User class="h-5 w-5" />
                            <span class="hidden md:inline">Profile</span>
                        </Link>
                        <Link
                            v-if="!page.props.auth.user"
                            :href="login.url()"
                            class="flex items-center gap-1 text-muted-foreground hover:text-primary"
                        >
                            <Button variant="ghost" size="sm" class="font-bold">
                                Masuk
                            </Button>
                        </Link>
                        <Link
                            v-if="!page.props.auth.user"
                            :href="register.url()"
                            class="flex items-center gap-1 text-muted-foreground hover:text-primary"
                        >
                            <Button size="sm" class="font-bold">
                                Daftar
                            </Button>
                        </Link>
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
                    :href="explore.url()"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                    :class="{
                        'text-primary': urlIsActive(explore.url(), $page.url),
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
                <Link
                    v-if="page.props.auth.user"
                    href="/"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                >
                    <User class="h-6 w-6" />
                    <span class="text-[10px] font-medium">Profile</span>
                </Link>
                <Link
                    v-else
                    :href="login.url()"
                    class="flex flex-col items-center gap-1 text-muted-foreground hover:text-primary"
                >
                    <LogIn class="h-6 w-6" />
                    <span class="text-[10px] font-medium">Login</span>
                </Link>
            </div>
        </div>
    </div>
</template>
