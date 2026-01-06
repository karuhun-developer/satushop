<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Cms/Catalog/ProductCategoryController';
import ImageUploadPreview from '@/components/ImageUploadPreview.vue';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { useSwal } from '@/composables/useSwal';
import { CommonStatusEnum } from '@/enums/global.enum';
import { LocaleDataItem } from '@/types/cms/core';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    locales: LocaleDataItem[];
}>();

const { toast } = useSwal();

// Descriptions for each locale
const descriptions = ref<Record<string, string>>({});
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Create Product Category
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new product category for the application.
            </p>

            <Form
                v-bind="store.form()"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Product Category created.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Default Name</Label>
                    <InputDescription>
                        Default Display product category name.
                    </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="logo">Image</Label>
                    <InputDescription>
                        Upload the product category image (Max 5MB).
                    </InputDescription>
                    <ImageUploadPreview
                        input-id="image"
                        input-name="image"
                        label=""
                        description="Upload your product category image here."
                        accept="image/*"
                        :max-size="5"
                        preview-height="200px"
                        :errors="errors.logo"
                    />
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
                                :value="descriptions[locale.code]"
                            />
                            <QuillEditor
                                toolbar="minimal"
                                theme="snow"
                                content-type="html"
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

                <div class="grid gap-2">
                    <Label for="status">Status</Label>
                    <InputDescription>
                        Status of the product category (Active or Inactive).
                    </InputDescription>
                    <Select name="status" :default-value="1">
                        <SelectTrigger id="status" class="mt-1 w-full">
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
                    <InputError :message="errors.status" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save />
                        Save Changes
                    </Button>
                </div>
            </Form>
        </div>
    </Modal>
</template>
