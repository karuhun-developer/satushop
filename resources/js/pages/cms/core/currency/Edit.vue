<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Cms/Core/CurrencyController';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RadioGroup from '@/components/ui/radio-group/RadioGroup.vue';
import RadioGroupItem from '@/components/ui/radio-group/RadioGroupItem.vue';
import { useSwal } from '@/composables/useSwal';
import { CurrencyDataItem } from '@/types/cms/core';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

defineProps<{
    currency: CurrencyDataItem;
}>();

const { toast } = useSwal();
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Edit Currency
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Edit the currency details.
            </p>

            <Form
                v-bind="update.form({ currency: currency.id })"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Currency updated.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="code">Code</Label>
                    <InputDescription>
                        The code for the currency (e.g., '$', '€').
                    </InputDescription>
                    <Input
                        id="code"
                        name="code"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="currency.code"
                    />
                    <InputError :message="errors.code" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <InputDescription>
                        The display name for the currency (e.g., 'USD', 'EUR').
                    </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        :default-value="currency.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="is_default">Is Default</Label>
                    <InputDescription>
                        Set whether this currency is the default currency.
                    </InputDescription>
                    <RadioGroup
                        name="is_default"
                        :default-value="currency.is_default ? '1' : '0'"
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
