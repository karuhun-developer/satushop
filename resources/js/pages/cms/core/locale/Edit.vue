<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Cms/Core/LocaleController';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RadioGroup from '@/components/ui/radio-group/RadioGroup.vue';
import RadioGroupItem from '@/components/ui/radio-group/RadioGroupItem.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { useToast } from '@/composables/useToast';
import { LocaleDataItem } from '@/types/cms/core/locale';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

defineProps<{
    locale: LocaleDataItem;
}>();

const { toast } = useToast();
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Edit Locale
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Edit the locale details.
            </p>

            <Form
                v-bind="update.form({ locale: locale.id })"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Locale updated.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Code</Label>
                    <InputDescription>
                        The code for the locale (e.g., 'en', 'fr').
                    </InputDescription>
                    <Input
                        id="code"
                        name="code"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="locale.code"
                    />
                    <InputError :message="errors.code" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <InputDescription>
                        The display name for the locale (e.g., 'English',
                        'French').
                    </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="locale.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="direction">Direction</Label>
                    <InputDescription>
                        The text direction for the locale ('ltr' or 'rtl').
                    </InputDescription>
                    <Select name="direction" :default-value="locale.direction">
                        <SelectTrigger id="direction" class="mt-1 w-full">
                            <SelectValue placeholder="Select a direction" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="ltr"
                                >Left to Right (LTR)</SelectItem
                            >
                            <SelectItem value="rtl"
                                >Right to Left (RTL)</SelectItem
                            >
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.direction" />
                </div>

                <div class="grid gap-2">
                    <Label for="is_default">Is Default</Label>
                    <InputDescription>
                        Set whether this locale is the default locale.
                    </InputDescription>
                    <RadioGroup
                        name="is_default"
                        :default-value="locale.is_default ? '1' : '0'"
                    >
                        <div class="flex items-center space-x-2">
                            <RadioGroupItem id="is_default_true" value="1" />
                            <Label for="is_default_true">Default</Label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <RadioGroupItem id="is_default_false" value="0" />
                            <Label for="is_default_false">Not Default</Label>
                        </div>
                    </RadioGroup>
                    <InputError :message="errors.is_default" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save class="mr-2 h-4 w-4" />
                        Save Changes
                    </Button>
                </div>
            </Form>
        </div>
    </Modal>
</template>
