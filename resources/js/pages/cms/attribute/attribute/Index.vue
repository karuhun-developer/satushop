<script setup lang="ts">
import {
    create,
    destroy,
    edit,
} from '@/actions/App/Http/Controllers/Cms/Attribute/AttributeController';
import Heading from '@/components/Heading.vue';
import ResourceTable from '@/components/ResourceTable.vue';
import { Button } from '@/components/ui/button';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { useFilter } from '@/composables/useFilter';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import {
    AttributeDataItem,
    AttributeFamilyDataItem,
} from '@/types/cms/attribute';
import { Head, router } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';
import dayjs from 'dayjs';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{
    data: PaginationItem<AttributeDataItem>;
    attributeFamily: AttributeFamilyDataItem[];
    orderBy?: string;
    order?: 'asc' | 'desc';
    search?: string;
    paginate?: number;
    resource: string;
}>();

const { confirm, toast } = useSwal();
const { hasPermission } = usePermission();
const { updateParams } = useFilter();

const title = 'Attributes';
const description =
    'Manage the attributes used to define product characteristics.';

const columns = [
    {
        label: 'Attribute Family',
        key: 'attribute_families.name',
        sortable: true,
    },
    { label: 'Name', key: 'attributes.name', sortable: true },
    {
        label: 'Order',
        key: 'attributes.order',
        sortable: true,
    },
    {
        label: 'Status',
        key: 'attributes.status',
        sortable: true,
        class: 'text-center',
    },
    {
        label: 'Created At',
        key: 'attributes.created_at',
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
        title: 'Attributes',
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
                    max-width="7xl"
                    v-if="hasPermission('create' + resource)"
                >
                    <Button>
                        <Plus class="h-4 w-4" />
                        Create
                    </Button>
                </ModalLink>
            </div>
            <div class="flex flex-col gap-4">
                <!-- Filter attributes by family -->
                <div class="flex flex-col gap-2">
                    <span class="text-sm font-medium">Filter by Family:</span>
                    <Select
                        @update:model-value="
                            (v) =>
                                updateParams({
                                    attribute_family_id: v || null,
                                })
                        "
                    >
                        <SelectTrigger id="family-filter" class="w-56">
                            <SelectValue
                                placeholder="Select attribute family"
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="null">
                                -- All Families --
                            </SelectItem>
                            <template
                                v-for="family in attributeFamily"
                                :key="family.id"
                            >
                                <SelectItem :value="family.id">
                                    {{ family.name }} ({{ family.code }})
                                </SelectItem>
                            </template>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <ResourceTable
                :data="data"
                :columns="columns"
                :order-by="orderBy"
                :order="order"
                :search="search"
                :paginate="paginate"
            >
                <template #attribute_families.name="{ row }">
                    <div class="flex flex-col gap-1">
                        <span class="font-medium">{{
                            row.attribute_family_name
                        }}</span>
                        <span class="text-sm text-muted-foreground">{{
                            row.attribute_family_code
                        }}</span>
                    </div>
                </template>
                <template #attributes.name="{ row }">
                    <div class="flex flex-col gap-1">
                        <span class="font-medium">{{ row.name }}</span>
                        <span class="text-sm text-muted-foreground">{{
                            row.code
                        }}</span>
                    </div>
                </template>
                <template #attributes.order="{ row }">
                    {{ row.order }}
                </template>
                <template #attributes.status="{ row }">
                    <span
                        :class="{
                            'rounded-full px-2 py-1 text-sm font-medium': true,
                            'bg-green-100 text-green-800': row.status,
                            'bg-red-100 text-red-800': !row.status,
                        }"
                    >
                        {{ row.status ? 'Active' : 'Inactive' }}
                    </span>
                </template>
                <template #attributes.created_at="{ row }">
                    {{ dayjs(row.created_at).format('DD MMMM YYYY H:m:s') }}
                </template>
                <template #actions="{ row }">
                    <div class="flex items-center justify-center gap-2">
                        <ModalLink
                            :href="edit({ attribute: row.id }).url"
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
                                    title: 'Delete Attribute ?',
                                    text: 'This action cannot be undone.',
                                    icon: 'warning',
                                    confirmButtonText: 'Yes, delete it!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        router.delete(
                                            destroy({
                                                attribute: row.id,
                                            }).url,
                                            {
                                                preserveScroll: true,
                                                preserveState: true,
                                                onSuccess: () => {
                                                    toast.fire({
                                                        icon: 'success',
                                                        title: 'Attribute deleted successfully.',
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
