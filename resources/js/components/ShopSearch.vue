<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { explore } from '@/routes';
import { router, usePage } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

// Get initial search from URL
const urlParams = new URLSearchParams(window.location.search);
const searchTerm = ref(urlParams.get('search') || '');

// Watch for URL changes to sync input if changed externally (e.g. back button)
// We need to watch the page props or url.
// However, popstate should update the page and component re-mounts or re-evals?
// If ShopLayout is persistent, we need to watch.
watch(
    () => usePage().url,
    (newUrl) => {
        // Parse search from new Url
        try {
            const url = new URL(newUrl, window.location.origin);
            const search = url.searchParams.get('search');
            if (search !== searchTerm.value) {
                searchTerm.value = search || '';
            }
        } catch (e) {
            // ignore
        }
    },
);

const handleSearch = () => {
    const currentUrl = new URL(window.location.href);
    const params = new URLSearchParams(currentUrl.search);

    if (searchTerm.value) {
        params.set('search', searchTerm.value);
    } else {
        params.delete('search');
    }

    // specific check: if not on explore page, go there.
    if (!window.location.pathname.startsWith('/explore')) {
        router.visit(explore.url(), {
            data: {
                search: searchTerm.value,
            },
        });
        return;
    }

    router.get(
        `${window.location.pathname}?${params.toString()}`,
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<template>
    <div class="relative flex max-w-3xl flex-1 gap-2">
        <div class="group relative w-full">
            <Search
                class="absolute top-2.5 left-3 h-4 w-4 text-muted-foreground group-focus-within:text-primary"
            />
            <Input
                type="search"
                v-model="searchTerm"
                @keydown.enter="handleSearch"
                placeholder="Cari..."
                class="h-10 w-full rounded-lg border-gray-200 pr-10 pl-10 transition-all focus-visible:border-primary focus-visible:ring-primary md:placeholder:text-muted-foreground"
            />
        </div>
        <Button
            size="icon"
            variant="secondary"
            class="bg-gray-100 text-gray-600 hover:bg-gray-200 md:w-auto md:px-4"
            @click="handleSearch"
        >
            <Search class="h-4 w-4 md:hidden" />
            <span class="hidden md:inline">Cari</span>
        </Button>
    </div>
</template>
