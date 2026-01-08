<script setup lang="ts">
import {
    create,
    destroy,
    edit,
} from '@/actions/App/Http/Controllers/Cms/Catalog/ProductController';
import Heading from '@/components/Heading.vue';
import ResourceTable from '@/components/ResourceTable.vue';
import { Button } from '@/components/ui/button';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import { ProductFlatDataItem } from '@/types/cms/catalog';
import { Head, Link, router } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';
import dayjs from 'dayjs';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{
    data: PaginationItem<ProductFlatDataItem>;
    orderBy?: string;
    order?: 'asc' | 'desc';
    search?: string;
    paginate?: number;
    resource: string;
}>();

const { confirm, toast } = useSwal();
const { hasPermission } = usePermission();

const title = 'Product';
const description =
    'Manage the product in your store, including their details and inventory.';

const columns = [
    {
        label: 'Name / SKU / Attribute Family',
        key: 'name',
        sortable: true,
    },
    {
        label: 'Image / Price / Quantity',
        key: 'price',
        sortable: true,
    },
    {
        label: 'Status / Type',
        key: 'type',
        sortable: true,
    },
    {
        label: 'Created At',
        key: 'created_at',
        sortable: true,
    },
    {
        label: 'Actions',
        key: 'actions',
        sortable: false,
        class: 'w-24 text-center',
    },
];

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Catalog',
        href: '#',
    },
    {
        title: title,
        href: '#',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="title" />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <Heading :title="title" :description="description" />
                <ModalLink
                    :href="create().url"
                    slideover
                    v-if="hasPermission('create' + resource)"
                >
                    <Button>
                        <Plus class="h-4 w-4" />
                        Create
                    </Button>
                </ModalLink>
            </div>
            <ResourceTable
                :data="data"
                :columns="columns"
                :order-by="orderBy"
                :order="order"
                :search="search"
                :paginate="paginate"
            >
                <template #name="{ row }">
                    <div class="flex items-center gap-3">
                        <div>
                            <div class="font-medium">{{ row.name }}</div>
                            <div class="text-sm text-muted-foreground">
                                SKU: {{ row.sku }} | Attribute Family:
                                {{ row.product?.attribute_family?.name }}
                            </div>
                        </div>
                    </div>
                </template>
                <template #price="{ row }">
                    <div class="flex flex-col gap-2">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded border border-dashed border-muted-foreground/30 bg-muted/50"
                        >
                            <img
                                v-if="row.image_1"
                                :src="row.image_1"
                                :alt="row.name"
                                class="h-16 w-16 rounded object-cover"
                            />
                            <span v-else class="text-xs text-muted-foreground">
                                No Image
                            </span>
                        </div>
                        <div class="space-y-1">
                            <div class="font-semibold text-destructive">
                                {{
                                    new Intl.NumberFormat('id-ID', {
                                        currency: 'IDR',
                                    }).format(row.price)
                                }}
                            </div>
                            <div
                                class="text-xs font-medium text-muted-foreground"
                            >
                                Stock: {{ row.stock ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </template>
                <template #type="{ row }">
                    <div class="flex flex-col gap-2">
                        <div>
                            <span
                                v-if="row.visible_individually"
                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400"
                            >
                                Visible
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800 dark:bg-gray-900/30 dark:text-gray-400"
                            >
                                Hidden
                            </span>
                        </div>
                        <div class="text-sm text-muted-foreground">
                            Type: {{ row.type ?? 'N/A' }}
                        </div>
                    </div>
                </template>
                <template #created_at="{ row }">
                    {{ dayjs(row.created_at).format('DD MMMM YYYY H:m:s') }}
                </template>
                <template #actions="{ row }">
                    <div class="flex items-center justify-center gap-2">
                        <Link
                            :href="edit({ product: row.slug }).url"
                            v-if="hasPermission('update' + resource)"
                        >
                            <Button variant="ghost" size="icon">
                                <Pencil class="h-4 w-4" />
                            </Button>
                        </Link>
                        <Button
                            variant="ghost"
                            size="icon"
                            v-if="hasPermission('delete' + resource)"
                            @click="
                                confirm({
                                    title: 'Delete Product ?',
                                    text: 'This action cannot be undone.',
                                    icon: 'warning',
                                    confirmButtonText: 'Yes, delete it!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        router.delete(
                                            destroy({
                                                product: row.slug,
                                            }).url,
                                            {
                                                preserveScroll: true,
                                                preserveState: true,
                                                onSuccess: () => {
                                                    toast.fire({
                                                        icon: 'success',
                                                        title: 'Product deleted successfully.',
                                                    });
                                                },
                                            },
                                        );
                                    }
                                })
                            "
                        >
                            <Trash2 class="h-4 w-4 text-destructive" />
                        </Button>
                    </div>
                </template>
            </ResourceTable>
        </div>
    </AppLayout>
</template>
