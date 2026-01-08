<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { show as shopShow } from '@/routes/shop';
import ShopLayout from '@/layouts/ShopLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { Input } from '@/components/ui/input';
import { Star, ShoppingCart } from 'lucide-vue-next';
import { EmptyState } from '@/components/ui/empty-state';

interface Product {
    id: number;
    name: string;
    price: string;
    rating: number;
    category: string;
    image: string;
}

const props = defineProps<{
    products: Product[];
}>();

const searchTerm = ref('');
const selectedCategory = ref<string | null>(null);

const categories = ['Electronics', 'Fashion', 'Home & Living', 'Sports'];

const filteredProducts = computed(() => {
    return props.products.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(searchTerm.value.toLowerCase());
        const matchesCategory = selectedCategory.value ? product.category === selectedCategory.value : true;
        return matchesSearch && matchesCategory;
    });
});
</script>

<template>
    <Head title="Shop" />

    <ShopLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Shop</h1>
                    <p class="text-muted-foreground mt-1">Explore our premium collection</p>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                     <!-- Sort (Visual) -->
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Sidebar Filters -->
                <aside class="hidden md:block space-y-6">
                    <div>
                        <h3 class="font-semibold mb-4">Search</h3>
                        <Input v-model="searchTerm" placeholder="Search products..." />
                    </div>
                    <Separator />
                    <div>
                        <h3 class="font-semibold mb-4">Categories</h3>
                        <div class="space-y-2">
                             <div 
                                class="flex items-center space-x-2 cursor-pointer p-2 rounded-md hover:bg-muted/50"
                                :class="{ 'bg-muted font-medium': selectedCategory === null }"
                                @click="selectedCategory = null"
                             >
                                <span class="text-sm">All Categories</span>
                             </div>
                             <div 
                                v-for="category in categories"
                                :key="category"
                                class="flex items-center space-x-2 cursor-pointer p-2 rounded-md hover:bg-muted/50"
                                :class="{ 'bg-muted font-medium': selectedCategory === category }"
                                @click="selectedCategory = category"
                             >
                                <span class="text-sm">{{ category }}</span>
                             </div>
                        </div>
                    </div>
                    <Separator />
                    <div>
                        <h3 class="font-semibold mb-4">Price Range</h3>
                        <!-- Slider (Visual) -->
                        <div class="h-2 bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-primary w-2/3"></div>
                        </div>
                        <div class="flex justify-between text-xs text-muted-foreground mt-2">
                            <span>$0</span>
                            <span>$1000+</span>
                        </div>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="md:col-span-3">
                    <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                         <Card 
                            v-for="product in filteredProducts" 
                            :key="product.id"
                            class="group overflow-hidden border-none shadow-sm hover:shadow-md transition-shadow"
                        >
                            <div class="relative aspect-square bg-muted">
                                <img 
                                    :src="product.image" 
                                    :alt="product.name"
                                    class="object-cover w-full h-full"
                                />
                                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <Button variant="secondary" size="icon" class="rounded-full">
                                        <ShoppingCart class="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                            <CardContent class="p-4">
                                <div class="text-xs text-muted-foreground mb-1">{{ product.category }}</div>
                                <h3 class="font-medium truncate mb-1">
                                    <Link :href="shopShow.url({ id: product.id })" class="hover:underline">
                                        {{ product.name }}
                                    </Link>
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold">{{ product.price }}</span>
                                    <div class="flex items-center gap-1 text-xs text-muted-foreground">
                                        <Star class="h-3 w-3 fill-yellow-400 text-yellow-400" />
                                        <span>{{ product.rating }}</span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-else class="h-full flex items-center justify-center">
                         <EmptyState 
                            title="No products found"
                            description="Try adjusting your filters or search term."
                            actionLabel="Clear Filters"
                            @action="() => { searchTerm = ''; selectedCategory = null; }"
                         />
                    </div>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>
