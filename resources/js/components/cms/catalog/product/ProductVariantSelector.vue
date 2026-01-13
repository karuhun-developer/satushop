<script setup lang="ts">
import { editModal } from '@/actions/App/Http/Controllers/Cms/Catalog/ProductController';
import Button from '@/components/ui/button/Button.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { ProductFlatDataItem } from '@/types/cms/catalog';
import { ModalLink } from '@inertiaui/modal-vue';
import { Pencil } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    currentVariants: number[];
    siblings: ProductFlatDataItem[];
}>();

const selectedVariantIds = ref<number[]>([...props.currentVariants]);
</script>

<template>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <th
                        class="w-10 px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        <!-- Select All can be added here if needed -->
                    </th>
                    <th
                        class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        Image
                    </th>
                    <th
                        class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white"
                    >
                        Product Details
                    </th>
                    <th
                        class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white"
                    >
                        Price
                    </th>
                    <th
                        class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white"
                    >
                        Stock
                    </th>
                    <th
                        class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="siblings.length === 0">
                    <tr>
                        <td
                            colspan="5"
                            class="py-8 text-center text-gray-500 dark:text-gray-400"
                        >
                            No other variants available.
                        </td>
                    </tr>
                </template>
                <tr
                    v-for="variant in siblings"
                    :key="variant.id"
                    class="border-b border-gray-200 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-800"
                    :class="{
                        'bg-blue-50/50 dark:bg-blue-900/10':
                            selectedVariantIds.includes(variant.id),
                    }"
                >
                    <td class="px-4 py-2 align-top">
                        <Checkbox
                            :id="`variant-select-${variant.id}`"
                            :checked="selectedVariantIds.includes(variant.id)"
                            :default-value="
                                selectedVariantIds.includes(variant.id)
                            "
                            @update:model-value="
                                (value) =>
                                    value
                                        ? selectedVariantIds.push(variant.id)
                                        : selectedVariantIds.splice(
                                              selectedVariantIds.indexOf(
                                                  variant.id,
                                              ),
                                              1,
                                          )
                            "
                        />
                    </td>
                    <td class="w-20 px-4 py-2 align-top">
                        <div
                            class="h-16 w-16 overflow-hidden rounded border border-gray-200 bg-white p-1 dark:border-gray-700 dark:bg-gray-800"
                        >
                            <img
                                v-if="variant.image_1"
                                :src="variant.image_1"
                                :alt="variant.name"
                                class="h-full w-full object-contain"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-400 dark:bg-gray-800"
                            >
                                No Img
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 align-top">
                        <label
                            :for="`variant-select-${variant.id}`"
                            class="cursor-pointer"
                        >
                            <div
                                class="font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ variant.name }}
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400"
                            >
                                SKU: {{ variant.sku }}
                            </div>
                        </label>
                    </td>
                    <td class="px-4 py-2 text-right align-top">
                        {{
                            new Intl.NumberFormat('id-ID', {
                                currency: 'IDR',
                            }).format(Number(variant.price))
                        }}
                    </td>
                    <td class="px-4 py-2 text-right align-top">
                        {{ variant.stock }}
                    </td>
                    <td class="px-4 py-2 text-right align-top">
                        <ModalLink
                            :href="editModal({ product: variant.slug }).url"
                            max-width="7xl"
                        >
                            <Button variant="ghost" size="icon" type="button">
                                <Pencil class="h-4 w-4" />
                            </Button>
                        </ModalLink>
                    </td>
                </tr>
            </tbody>
        </table>
        <input
            v-for="id in selectedVariantIds"
            :key="id"
            type="hidden"
            name="variants[]"
            :value="id"
        />
    </div>
</template>
