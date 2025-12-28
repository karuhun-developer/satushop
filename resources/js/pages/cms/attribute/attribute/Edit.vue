<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Cms/Attribute/AttributeController';
import AttributeOptionsSection from '@/components/cms/attribute/AttributeOptionsSection.vue';
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
import {
    AttributeDataItem,
    AttributeFamilyDataItem,
} from '@/types/cms/attribute';
import { LocaleDataItem } from '@/types/cms/core';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    attribute: AttributeDataItem;
    attributeFamily: AttributeFamilyDataItem[];
    locales: LocaleDataItem[];
}>();

const { toast } = useSwal();

// Options state - initialize from existing options
const options = ref(
    props.attribute.options?.map((option) => ({
        id: option.id,
        name: option.name,
        order: option.order,
        status: option.status ? 1 : 0,
        translations: {
            ...option.translations?.reduce(
                (acc: Record<string, string>, translation: any) => {
                    acc[translation.locale] = translation.name;
                    return acc;
                },
                {} as Record<string, string>,
            ),
        },
    })) || [],
);

const addOption = () => {
    options.value.push({
        id: Date.now(),
        name: '',
        order: options.value.length + 1,
        status: 1,
        translations: {
            ...props.locales.reduce(
                (acc: Record<string, string>, locale: LocaleDataItem) => {
                    acc[locale.code] = '';
                    return acc;
                },
                {} as Record<string, string>,
            ),
        },
    });
};

const removeOption = (index: number) => {
    options.value.splice(index, 1);

    // Reorder remaining options
    options.value = options.value.map((option, idx) => ({
        ...option,
        order: idx + 1,
    }));
};

// Get attribute transactions for easier access
const translations = ref(
    props.attribute.translations?.reduce(
        (acc: Record<string, any>, translation) => {
            acc[translation.locale] = translation;
            return acc;
        },
        {} as Record<string, any>,
    ),
);
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Edit Attribute
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Edit the attribute details.
            </p>

            <Form
                v-bind="update.form({ attribute: attribute.id })"
                class="space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Attribute updated.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="attribute_family_id"
                            >Attribute Family</Label
                        >
                        <InputDescription>
                            Select the attribute family this attribute belongs
                            to.
                        </InputDescription>
                        <Select
                            name="attribute_family_id"
                            :default-value="attribute.attribute_family_id"
                        >
                            <SelectTrigger
                                id="attribute_family_id"
                                class="mt-1 w-full"
                            >
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
                        <InputError :message="errors.status" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="code">Code</Label>
                        <InputDescription>
                            Attribute code used internally (e.g., 'color',
                            'size').
                        </InputDescription>
                        <Input
                            id="code"
                            name="code"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            :default-value="attribute.code"
                        />
                        <InputError :message="errors.code" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Default Name</Label>
                        <InputDescription>
                            Default Display name of the attribute (e.g.,
                            'Color', 'Size').
                        </InputDescription>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            :default-value="attribute.name"
                        />
                        <InputError :message="errors.name" />
                    </div>
                </div>

                <template v-for="locale in locales" :key="locale.id">
                    <div class="grid gap-2">
                        <Label :for="`name_${locale.code}`"
                            >Name ({{ locale.name }})</Label
                        >
                        <InputDescription>
                            Translated Display name of the attribute in
                            {{ locale.name }}.
                        </InputDescription>
                        <Input
                            :id="`name_${locale.code}`"
                            :name="`translations[${locale.code}][name]`"
                            type="text"
                            class="mt-1 block w-full"
                            :placeholder="`Enter name in ${locale.name}`"
                            :default-value="
                                translations?.[locale.code]?.name || ''
                            "
                        />
                        <InputError
                            :message="
                                errors[`translations.${locale.code}.name`]
                            "
                        />
                    </div>
                </template>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="order">Order</Label>
                        <InputDescription>
                            Display order of the attribute in listings.
                        </InputDescription>
                        <Input
                            id="order"
                            name="order"
                            type="number"
                            class="mt-1 block w-full"
                            required
                            :default-value="attribute.order"
                        />
                        <InputError :message="errors.order" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="status">Status</Label>
                        <InputDescription>
                            Status of the attribute (Active or Inactive).
                        </InputDescription>
                        <Select
                            name="status"
                            :default-value="Number(attribute.status)"
                        >
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
                </div>

                <!-- Options Section -->
                <AttributeOptionsSection
                    :options="options"
                    :locales="locales"
                    :errors="errors"
                    @add="addOption"
                    @remove="removeOption"
                />

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
