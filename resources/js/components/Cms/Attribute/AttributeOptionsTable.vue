<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { CommonStatusEnum } from '@/enums/global.enum';
import { LocaleDataItem } from '@/types/cms/core';
import { Trash2 } from 'lucide-vue-next';

defineProps<{
    options: any[];
    locales: LocaleDataItem[];
    errors: Record<string, string>;
}>();

const emit = defineEmits<{
    remove: [index: number];
}>();
</script>

<template>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <th
                        class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        Admin Name
                    </th>
                    <template v-for="locale in locales" :key="locale.id">
                        <th
                            class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                        >
                            {{ locale.name }}({{ locale.code }})
                        </th>
                    </template>
                    <th
                        class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        Order
                    </th>
                    <th
                        class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        Status
                    </th>
                    <th class="w-24 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="options.length === 0">
                    <tr>
                        <td
                            colspan="10"
                            class="py-8 text-center text-gray-500 dark:text-gray-400"
                        >
                            No options added yet. Click "Add Row" to add one.
                        </td>
                    </tr>
                </template>
                <template v-for="(option, index) in options" :key="option.id">
                    <tr
                        class="border-b border-gray-200 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-2">
                            <Input v-model="option.id" type="hidden" />
                            <Input
                                v-model="option.name"
                                :name="`options[${index}][name]`"
                                type="text"
                                placeholder="Enter admin name"
                                class="w-full"
                            />
                            <InputError
                                :message="errors[`options.${index}.name`]"
                            />
                        </td>
                        <template v-for="locale in locales" :key="locale.id">
                            <td class="px-4 py-2">
                                <Input
                                    v-model="option.translations[locale.code]"
                                    :name="`options[${index}][translations][${locale.code}][name]`"
                                    type="text"
                                    :placeholder="`Enter in ${locale.name}`"
                                    class="w-full"
                                />
                                <InputError
                                    :message="
                                        errors[
                                            `options.${index}.translations.${locale.code}.name`
                                        ]
                                    "
                                />
                            </td>
                        </template>
                        <td class="px-4 py-2">
                            <Input
                                v-model="option.order"
                                :name="`options[${index}][order]`"
                                type="number"
                                placeholder="Order"
                                class="w-full"
                            />
                            <InputError
                                :message="errors[`options.${index}.order`]"
                            />
                        </td>
                        <td class="px-4 py-2">
                            <Select
                                :name="`options[${index}][status]`"
                                :default-value="option.status"
                            >
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <template
                                        v-for="commnStatus in CommonStatusEnum"
                                        :key="commnStatus.value"
                                    >
                                        <SelectItem :value="commnStatus.value">
                                            {{ commnStatus.label }}
                                        </SelectItem>
                                    </template>
                                </SelectContent>
                            </Select>
                            <InputError
                                :message="errors[`options.${index}.status`]"
                            />
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    @click="emit('remove', index)"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
