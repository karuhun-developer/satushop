<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Cms/Attribute/AttributeFamilyController';
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
import { useToast } from '@/composables/useToast';
import { CommonStatusEnum } from '@/enums/global.enum';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

const { toast } = useToast();
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Create Attribute Family
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new attribute family for the application.
            </p>

            <Form
                v-bind="store.form()"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Attribute Family created.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Code</Label>
                    <InputDescription>
                        The code for the attribute family (e.g., 'clothing',
                        'electronics').
                    </InputDescription>
                    <Input
                        id="code"
                        name="code"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.code" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <InputDescription>
                        The display name for the attribute family (e.g.,
                        'Clothing', 'Electronics').
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
                    <Label for="status">Status</Label>
                    <InputDescription>
                        Status of the menu item (Active or Inactive).
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
