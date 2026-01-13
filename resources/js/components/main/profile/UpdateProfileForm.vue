<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { useSwal } from '@/composables/useSwal';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
}

interface UpdateProfileFormProps {
    user: User;
}

const props = defineProps<UpdateProfileFormProps>();

const { toast } = useSwal();

const formName = ref(props.user.name);
const formEmail = ref(props.user.email);
const formPhone = ref(props.user.phone || '');

const errors = ref<Record<string, string>>({});
const processing = ref(false);

const handleSubmit = () => {
    processing.value = true;
    errors.value = {};

    router.put(
        '/my-profile',
        {
            name: formName.value,
            email: formEmail.value,
            phone: formPhone.value,
        },
        {
            onSuccess: () => {
                toast.fire({
                    icon: 'success',
                    title: 'Profile updated successfully!',
                });
            },
            onError: (err) => {
                errors.value = err;
            },
            onFinish: () => {
                processing.value = false;
            },
        },
    );
};
</script>

<template>
    <div class="rounded-lg border bg-card p-6">
        <h2 class="mb-4 text-xl font-semibold">Update Profile</h2>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="grid gap-2">
                <Label for="profile-name">Full Name</Label>
                <Input
                    id="profile-name"
                    v-model="formName"
                    type="text"
                    required
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="profile-email">Email</Label>
                <Input
                    id="profile-email"
                    v-model="formEmail"
                    type="email"
                    required
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="profile-phone">Phone Number</Label>
                <Input
                    id="profile-phone"
                    v-model="formPhone"
                    type="text"
                />
                <InputError :message="errors.phone" />
            </div>

            <div class="flex justify-end">
                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </form>
    </div>
</template>
