<script setup lang="ts">
import {
    create,
    destroy,
    edit,
    updateStatus,
} from '@/actions/App/Http/Controllers/Cms/Shop/ShopController';
import Heading from '@/components/Heading.vue';
import ResourceTable from '@/components/ResourceTable.vue';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import { ValidationEnum } from '@/enums/global.enum';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import { ShopDataItem } from '@/types/cms/shop';
import { Head, router } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';
import dayjs from 'dayjs';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{
    data: PaginationItem<ShopDataItem>;
    orderBy?: string;
    order?: 'asc' | 'desc';
    search?: string;
    paginate?: number;
    resource: string;
}>();

const { confirm, toast } = useSwal();
const { hasPermission } = usePermission();

const title = 'Shops';
const description = 'Manage the shops available in this platform.';

const columns = [
    { label: 'Name', key: 'name', sortable: true },
    { label: 'Phone', key: 'phone', sortable: true },
    { label: 'Email', key: 'email', sortable: true },
    { label: 'Address', key: 'address', sortable: true },
    { label: 'Status', key: 'status', sortable: true },
    { label: 'Created At', key: 'created_at', sortable: true },
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
        title: 'Settings',
        href: '#',
    },
    {
        title: title,
        href: '#',
    },
];

const onStatusChange = (row: ShopDataItem, value: any) => {
    if (value === null) {
        return;
    }

    router.patch(
        updateStatus({ shop: row.slug }).url,
        {
            status: Number(value),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.fire({
                    icon: 'success',
                    title: 'Status updated successfully.',
                });
            },
        },
    );
};
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
                    max-width="7xl"
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
                    <div class="flex flex-col gap-1">
                        <span class="font-medium">{{ row.name }}</span>
                        <span class="text-sm text-muted-foreground">{{
                            row.slug
                        }}</span>
                    </div>
                </template>
                <template #status="{ row }">
                    <div
                        v-if="hasPermission('update' + resource)"
                        class="w-32"
                        @click.stop
                    >
                        <Select
                            :model-value="String(row.status)"
                            @update:model-value="
                                (value) => onStatusChange(row, value)
                            "
                        >
                            <SelectTrigger class="h-8">
                                <SelectValue placeholder="Select status" />
                            </SelectTrigger>
                            <SelectContent>
                                <template
                                    v-for="status in ValidationEnum"
                                    :key="status.value"
                                >
                                    <SelectItem :value="String(status.value)">
                                        {{ status.label }}
                                    </SelectItem>
                                </template>
                            </SelectContent>
                        </Select>
                    </div>
                    <span
                        v-else
                        :class="{
                            'rounded-full px-2 py-1 text-sm font-medium': true,
                            'bg-yellow-100 text-yellow-800': row.status == 0,
                            'bg-green-100 text-green-800': row.status == 1,
                            'bg-red-100 text-red-800': row.status == 2,
                        }"
                    >
                        {{
                            row.status == 0
                                ? 'Pending'
                                : row.status == 1
                                  ? 'Approved'
                                  : 'Rejected'
                        }}
                    </span>
                </template>
                <template #created_at="{ row }">
                    {{ dayjs(row.created_at).format('DD MMMM YYYY H:m:s') }}
                </template>
                <template #actions="{ row }">
                    <div class="flex items-center justify-center gap-2">
                        <ModalLink
                            :href="edit({ shop: row.slug }).url"
                            slideover
                            max-width="7xl"
                            v-if="hasPermission('update' + resource)"
                        >
                            <Button variant="ghost" size="icon">
                                <Pencil class="h-4 w-4" />
                            </Button>
                        </ModalLink>
                        <Button
                            variant="ghost"
                            size="icon"
                            v-if="hasPermission('delete' + resource)"
                            @click="
                                confirm({
                                    title: 'Delete Shop?',
                                    text: 'This action cannot be undone.',
                                    icon: 'warning',
                                    confirmButtonText: 'Yes, delete it!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        router.delete(
                                            destroy({ shop: row.slug }).url,
                                            {
                                                preserveScroll: true,
                                                preserveState: true,
                                                onSuccess: () => {
                                                    toast.fire({
                                                        icon: 'success',
                                                        title: 'Shop deleted successfully.',
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
