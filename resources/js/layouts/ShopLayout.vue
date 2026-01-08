<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ShoppingCart, Menu, Search, User, X, Trash2 } from 'lucide-vue-next';
import { home } from '@/routes';
import { index as shopIndex } from '@/routes/shop';
import { index as cartIndex } from '@/routes/cart';
import { index as checkoutIndex } from '@/routes/checkout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Sheet, SheetContent, SheetTrigger, SheetHeader, SheetTitle } from '@/components/ui/sheet';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';

const isMobileMenuOpen = ref(false);

const cartItems = ref([
    {
        id: 1,
        name: 'Premium Wireless Headphones',
        price: 2500000,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=200&h=200'
    },
    {
        id: 2,
        name: 'Minimalist Watch',
        price: 1200000,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=200&h=200'
    }
]);

const cartTotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
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
    <!-- Top Bar (Optional for Premium feel) -->
    <div class="bg-primary text-primary-foreground text-xs py-2 text-center">
      Free shipping on all orders over Rp 500.000
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between gap-4">
        
        <!-- Mobile Menu Trigger -->
        <Sheet>
            <SheetTrigger as-child>
                <Button variant="ghost" size="icon" class="md:hidden">
                    <Menu class="h-5 w-5" />
                    <span class="sr-only">Toggle menu</span>
                </Button>
            </SheetTrigger>
            <SheetContent side="left">
                <SheetHeader>
                    <SheetTitle class="text-left text-lg font-bold">Menu</SheetTitle>
                </SheetHeader>
                <div class="grid gap-4 py-4">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="link.href"
                        class="text-sm font-medium hover:text-primary transition-colors"
                    >
                        {{ link.name }}
                    </Link>
                </div>
            </SheetContent>
        </Sheet>

        <!-- Logo -->
        <div class="flex-shrink-0">
          <Link :href="home.url()" class="text-xl font-bold tracking-tight flex items-center gap-2">
            <div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center text-primary-foreground">
                E
            </div>
            <span>E-Commerce</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
            <Link 
                v-for="link in navLinks" 
                :key="link.name" 
                :href="link.href"
                class="hover:text-primary transition-colors"
                :class="{ 'text-primary': $page.url === link.href }"
            >
                {{ link.name }}
            </Link>
        </nav>

        <!-- Search (Visual Only for now) -->
        <div class="hidden md:flex flex-1 max-w-sm ml-auto relative">
           <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
           <Input 
                type="search" 
                placeholder="Search products..." 
                class="pl-8 w-full bg-muted/50 focus-visible:bg-background transition-colors"
            />
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-2">
            <!-- Mobile Search Toggle (Could implement later) -->
            
            <!-- Cart -->
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" class="relative">
                        <ShoppingCart class="h-5 w-5" />
                        <Badge v-if="cartItems.length > 0" class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 text-[10px]">
                            {{ cartItems.length }}
                        </Badge>
                        <span class="sr-only">Open cart</span>
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-80 p-0">
                    <DropdownMenuLabel class="font-normal p-4">
                        <div class="flex flex-col space-y-1">
                            <p class="text-sm font-medium leading-none">Shopping Cart</p>
                            <p class="text-xs leading-none text-muted-foreground">{{ cartItems.length }} items</p>
                        </div>
                    </DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    
                    <!-- Items -->
                    <div class="p-4 space-y-4 max-h-[300px] overflow-y-auto">
                        <div v-for="item in cartItems" :key="item.id" class="flex gap-4">
                            <div class="h-12 w-12 rounded-md bg-muted overflow-hidden flex-shrink-0">
                                <img :src="item.image" :alt="item.name" class="h-full w-full object-cover" />
                            </div>
                            <div class="flex-1 space-y-1 min-w-0">
                                <p class="text-sm font-medium leading-none truncate">{{ item.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ item.quantity }} x {{ formatPrice(item.price) }}</p>
                            </div>
                        </div>
                         <div v-if="cartItems.length === 0" class="py-6 text-center text-sm text-muted-foreground">
                            Your cart is empty
                        </div>
                    </div>

                    <DropdownMenuSeparator v-if="cartItems.length > 0" />
                    
                    <!-- Footer -->
                    <div v-if="cartItems.length > 0" class="p-4 pt-2 space-y-4">
                        <div class="flex justify-between text-sm font-medium">
                            <span>Total</span>
                            <span>{{ formatPrice(cartTotal) }}</span>
                        </div>
                        <div class="grid gap-2">
                            <DropdownMenuItem as-child>
                                <Link :href="cartIndex.url()" class="w-full cursor-pointer">
                                    <Button variant="outline" class="w-full" size="sm">View Cart</Button>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem as-child>
                                <Link :href="checkoutIndex.url()" class="w-full cursor-pointer">
                                    <Button class="w-full" size="sm">Checkout</Button>
                                </Link>
                            </DropdownMenuItem>
                        </div>
                    </div>
                </DropdownMenuContent>
            </DropdownMenu>

            <!-- User Menu -->
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon">
                        <User class="h-5 w-5" />
                        <span class="sr-only">User menu</span>
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>My Account</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem>Profile</DropdownMenuItem>
                    <DropdownMenuItem>Orders</DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem>Log out</DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main>
        <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t bg-muted/40 mt-auto">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                     <h4 class="text-sm font-semibold">About Us</h4>
                     <p class="text-sm text-muted-foreground">
                        Premium functional apparel for the modern lifestyle. Quality materials, sustainable production.
                     </p>
                </div>
                <div>
                     <h4 class="text-sm font-semibold mb-4">Shop</h4>
                     <ul class="space-y-2 text-sm text-muted-foreground">
                        <li><a href="#" class="hover:text-primary">All Products</a></li>
                        <li><a href="#" class="hover:text-primary">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-primary">Featured</a></li>
                     </ul>
                </div>
                <div>
                     <h4 class="text-sm font-semibold mb-4">Support</h4>
                     <ul class="space-y-2 text-sm text-muted-foreground">
                        <li><a href="#" class="hover:text-primary">FAQ</a></li>
                        <li><a href="#" class="hover:text-primary">Shipping</a></li>
                        <li><a href="#" class="hover:text-primary">Returns</a></li>
                     </ul>
                </div>
                <div>
                     <h4 class="text-sm font-semibold mb-4">Newsletter</h4>
                     <div class="flex gap-2">
                        <Input placeholder="Enter your email" class="max-w-[200px]" />
                        <Button>Subscribe</Button>
                     </div>
                </div>
            </div>
            <Separator class="my-8" />
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-muted-foreground">
                <p>&copy; 2024 E-Commerce Inc. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-primary">Privacy Policy</a>
                    <a href="#" class="hover:text-primary">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
  </div>
</template>
