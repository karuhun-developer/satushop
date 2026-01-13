<script setup lang="ts">
import {
    index,
    update,
} from '@/actions/App/Http/Controllers/Cms/Catalog/ProductController';
import ProductVariantSelector from '@/components/cms/catalog/product/ProductVariantSelector.vue';
import Heading from '@/components/Heading.vue';
import ImageUploadPreview from '@/components/ImageUploadPreview.vue';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import { Checkbox } from '@/components/ui/checkbox';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import MoneyInput from '@/components/ui/money-input/MoneyInput.vue';
import QuilTextEditor from '@/components/ui/quil-editor/QuilTextEditor.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import {
    ProductCategoryDataItem,
    ProductDataItem,
    ProductFlatDataItem,
} from '@/types/cms/catalog';
import { LocaleDataItem } from '@/types/cms/core';
import { Form, Head, router } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    product: ProductDataItem;
    flat: ProductFlatDataItem;
    productCategories: ProductCategoryDataItem[];
    locales: LocaleDataItem[];
    siblingFlats: ProductFlatDataItem[];
    currentCategoryIds: number[];
    currentVariantIds: number[];
}>();

const { toast } = useSwal();

const title = 'Edit Product ' + props.flat.name;
const description = 'Modify the details of the product.';

// Description and short description
const defaultDescription = ref<string>(props.flat.description || '');
const defaultShortDescription = ref<string>(props.flat.short_description || '');

// data for each locale
const names: Record<string, string> = {};
const descriptions = ref<Record<string, string>>({});
const shortDescriptions = ref<Record<string, string>>({});

// Initialize data from translations enteries
if (props.flat.translations) {
    props.flat.translations.forEach((translation) => {
        names[translation.locale] = translation.name;
        descriptions.value[translation.locale] = translation.description || '';
        shortDescriptions.value[translation.locale] =
            translation.short_description || '';
    });
}

const selectedCategoryIds = ref<number[]>(props.currentCategoryIds || []);

const selectedVariantIds = ref<number[]>(props.currentVariantIds || []);
const visibleIndividually = ref(Number(props.flat.visible_individually));

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Catalog',
        href: '#',
    },
    {
        title: 'Product',
        href: index().url,
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
            </div>

            <Form
                v-bind="update.form({ product: flat.slug })"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Product updated.',
                        });
                        router.visit(index().url);
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Default Name</Label>
                    <InputDescription>
                        Default Display product name.
                    </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="flat.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="sku">SKU</Label>
                    <InputDescription>
                        Stock Keeping Unit identifier for the product.
                    </InputDescription>
                    <Input
                        id="sku"
                        name="sku"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        :default-value="flat.sku"
                    />
                    <InputError :message="errors.sku" />
                </div>

                <div class="grid gap-2">
                    <Label for="price">Price</Label>
                    <InputDescription>
                        The selling price of the product.
                    </InputDescription>
                    <MoneyInput
                        id="price"
                        name="price"
                        :default-value="flat.price"
                    />
                    <InputError :message="errors.price" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="description">Default Description</Label>
                        <InputDescription>
                            Default Display product description.
                        </InputDescription>
                        <Input
                            id="description"
                            name="description"
                            type="hidden"
                            :default-value="flat.description"
                            :value="defaultDescription"
                        />
                        <QuilTextEditor
                            :content="flat.description"
                            @update:content="
                                (value) => {
                                    defaultDescription = value;
                                }
                            "
                        />
                        <InputError :message="errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="short_description"
                            >Default Short Description</Label
                        >
                        <InputDescription>
                            Default Display product short description.
                        </InputDescription>
                        <Input
                            id="short_description"
                            name="short_description"
                            type="hidden"
                            :default-value="flat.short_description"
                            :value="defaultShortDescription"
                        />
                        <QuilTextEditor
                            :content="flat.short_description"
                            @update:content="
                                (value) => {
                                    defaultShortDescription = value;
                                }
                            "
                        />
                        <InputError :message="errors.short_description" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <template
                        v-for="locale in locales"
                        :key="`name_${locale.id}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`name_${locale.code}`"
                                >Name ({{ locale.name }})</Label
                            >
                            <InputDescription>
                                The product category name in
                                {{ locale.name }}.
                            </InputDescription>
                            <Input
                                :id="`name_${locale.code}`"
                                :name="`translations[${locale.code}][name]`"
                                type="text"
                                class="mt-1 block w-full"
                                :placeholder="`Enter name in ${locale.name}`"
                                :default-value="names[locale.code]"
                                autofocus
                            />
                            <InputError
                                :message="
                                    errors[`translations.${locale.code}.name`]
                                "
                            />
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <template
                        v-for="locale in locales"
                        :key="`editor_${locale.code}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`description_${locale.code}`"
                                >Description ({{ locale.name }})</Label
                            >
                            <InputDescription>
                                The product category description in
                                {{ locale.name }}.
                            </InputDescription>
                            <Input
                                :id="`description_${locale.code}`"
                                :name="`translations[${locale.code}][description]`"
                                type="hidden"
                                :default-value="descriptions[locale.code]"
                                :value="descriptions[locale.code]"
                            />
                            <QuilTextEditor
                                :content="descriptions[locale.code]"
                                @update:content="
                                    (value) => {
                                        descriptions[locale.code] = value;
                                    }
                                "
                            />
                            <InputError
                                :message="
                                    errors[
                                        `translations.${locale.code}.description`
                                    ]
                                "
                            />
                        </div>
                    </template>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <template
                        v-for="locale in locales"
                        :key="`short_editor_${locale.code}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`short_description_${locale.code}`"
                                >Short Description ({{ locale.name }})</Label
                            >
                            <InputDescription>
                                The product category description in
                                {{ locale.name }}.
                            </InputDescription>
                            <Input
                                :id="`short_description_${locale.code}`"
                                :name="`translations[${locale.code}][short_description]`"
                                type="hidden"
                                :default-value="shortDescriptions[locale.code]"
                                :value="shortDescriptions[locale.code]"
                            />
                            <QuilTextEditor
                                :content="shortDescriptions[locale.code]"
                                @update:content="
                                    (value) => {
                                        shortDescriptions[locale.code] = value;
                                    }
                                "
                            />
                            <InputError
                                :message="
                                    errors[
                                        `translations.${locale.code}.description`
                                    ]
                                "
                            />
                        </div>
                    </template>
                </div>

                <div class="grid gap-2">
                    <Label for="visible_individually"
                        >Visible Individually</Label
                    >
                    <InputDescription>
                        Visable the product to be viewed individually.
                    </InputDescription>
                    <Select
                        name="visible_individually"
                        v-model="visibleIndividually"
                        :default-value="Number(flat.visible_individually)"
                    >
                        <SelectTrigger id="status" class="mt-1 w-full">
                            <SelectValue placeholder="Select status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem :value="1">
                                Visible Individually
                            </SelectItem>
                            <SelectItem :value="0"
                                >Not Visible Individually</SelectItem
                            >
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.status" />
                </div>

                <div class="grid gap-2">
                    <Label for="meta_title">Meta Title</Label>
                    <InputDescription>
                        The meta title for SEO purposes.
                    </InputDescription>
                    <Input
                        id="meta_title"
                        name="meta_data[meta_title]"
                        type="text"
                        class="mt-1 block w-full"
                        :default-value="flat.meta_data?.meta_title || ''"
                    />
                    <InputError :message="errors.meta_data" />
                </div>

                <div class="grid gap-2">
                    <Label for="meta_description">Meta Description</Label>
                    <InputDescription>
                        The meta description for SEO purposes.
                    </InputDescription>
                    <Textarea
                        id="meta_description"
                        name="meta_data[meta_description]"
                        class="mt-1 block w-full"
                        rows="3"
                        :default-value="flat.meta_data?.meta_description || ''"
                    />
                    <InputError :message="errors.meta_data" />
                </div>

                <div class="grid gap-2">
                    <Label for="meta_keywords">Meta Keywords</Label>
                    <InputDescription>
                        The meta keywords for SEO purposes (comma separated).
                    </InputDescription>
                    <Input
                        id="meta_keywords"
                        name="meta_data[meta_keywords]"
                        type="text"
                        class="mt-1 block w-full"
                        :default-value="flat.meta_data?.meta_keywords || ''"
                    />
                    <InputError :message="errors.meta_data" />
                </div>

                <div class="grid gap-2">
                    <Label>Categories</Label>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                        <div
                            v-for="category in productCategories"
                            :key="category.id"
                            class="mt-2 flex items-center gap-2"
                        >
                            <Checkbox
                                :id="`category-${category.id}`"
                                :value="category.id"
                                :default-value="
                                    selectedCategoryIds.includes(category.id)
                                "
                                @update:model-value="
                                    (value) =>
                                        value
                                            ? selectedCategoryIds.push(
                                                  category.id,
                                              )
                                            : selectedCategoryIds.splice(
                                                  selectedCategoryIds.indexOf(
                                                      category.id,
                                                  ),
                                                  1,
                                              )
                                "
                            />
                            <Label :for="`category-${category.id}`">
                                {{ category.name }}
                            </Label>
                        </div>
                        <input
                            v-for="id in selectedCategoryIds"
                            :key="id"
                            type="hidden"
                            name="categories[]"
                            :value="id"
                        />
                    </div>
                    <InputError :message="errors.categories" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="logo">Main Image</Label>
                        <InputDescription>
                            Upload the product category image (Max 5MB).
                        </InputDescription>
                        <ImageUploadPreview
                            input-id="image_1"
                            input-name="image_1"
                            label=""
                            description="Upload your main product image here."
                            accept="image/*"
                            :max-size="5"
                            preview-height="200px"
                            :errors="errors.logo"
                            :initial-preview="flat.image_1"
                        />
                    </div>

                    <template
                        v-for="imageNumber in [2, 3, 4, 5, 6]"
                        :key="`image_${imageNumber}`"
                    >
                        <div class="grid gap-2">
                            <Label :for="`image_${imageNumber}`"
                                >Image {{ imageNumber }}</Label
                            >
                            <InputDescription>
                                Upload product image (Max 5MB).
                            </InputDescription>
                            <ImageUploadPreview
                                :input-id="`image_${imageNumber}`"
                                :input-name="`image_${imageNumber}`"
                                label=""
                                :description="`Upload your product image ${imageNumber} here.`"
                                accept="image/*"
                                :max-size="5"
                                preview-height="200px"
                                :errors="errors[`image_${imageNumber}`]"
                                :initial-preview="flat[`image_${imageNumber}`]"
                            />
                        </div>
                    </template>
                </div>

                <div
                    class="grid gap-2"
                    v-if="['variable', 'bundle'].includes(flat.type)"
                >
                    <Label>Product Variants</Label>
                    <InputDescription>
                        Select visible individual products to be variants of
                        this product.
                    </InputDescription>
                    <ProductVariantSelector
                        :current-variants="selectedVariantIds"
                        :siblings="siblingFlats"
                    />
                    <InputError :message="errors.variants" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save />
                        Save Changes
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
