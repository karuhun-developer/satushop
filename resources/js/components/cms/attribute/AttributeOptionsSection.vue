<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { LocaleDataItem } from '@/types/cms/core';
import { Plus } from 'lucide-vue-next';
import AttributeOptionsTable from './AttributeOptionsTable.vue';

defineProps<{
    options: any[];
    locales: LocaleDataItem[];
    errors: Record<string, string>;
}>();

const emit = defineEmits<{
    add: [];
    remove: [index: number];
}>();
</script>

<template>
    <div class="border-gray-200p-4 grid gap-4 rounded-lg border p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Options
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Add attribute options with translations
                </p>
            </div>
            <Button
                type="button"
                variant="secondary"
                class="bg-yellow-500 hover:bg-yellow-600"
                @click="emit('add')"
            >
                <Plus />
                Add Row
            </Button>
        </div>

        <AttributeOptionsTable
            :options="options"
            :locales="locales"
            :errors="errors"
            @remove="emit('remove', $event)"
        />
    </div>
</template>
