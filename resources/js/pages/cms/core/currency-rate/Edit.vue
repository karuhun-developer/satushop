<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Cms/Core/CurrencyRateController';
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
import { CurrencyDataItem } from '@/types/cms/core/currency';
import { CurrencyRateDataItem } from '@/types/cms/core/currency-rate';
import { Form, usePage } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

defineProps<{
    currencies: CurrencyDataItem[];
    currencyRate: CurrencyRateDataItem;
}>();

const page = usePage();
const { toast } = useToast();
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Edit Currency Rate
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Edit the currency rate details.
            </p>

            <Form
                v-bind="update.form({ currency_rate: currencyRate.id })"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Currency Rate updated.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="source_currency">Source Currency</Label>
                    <InputDescription>
                        The source currency for the currency rate.
                    </InputDescription>
                    <Input
                        id="source_currency"
                        name="source_currency"
                        type="text"
                        class="mt-1 block w-full"
                        :default-value="`${page.props.defaultCurrency.code} -  ${page.props.defaultCurrency.name}`"
                        disabled
                    />
                </div>

                <div class="grid gap-2">
                    <Label for="target_currency_id">Target Currency</Label>
                    <InputDescription>
                        The target currency for the currency rate.
                    </InputDescription>
                    <Select
                        name="target_currency_id"
                        :default-value="currencyRate.target_currency_id"
                    >
                        <SelectTrigger
                            id="target_currency_id"
                            class="mt-1 w-full"
                        >
                            <SelectValue placeholder="Select a currency" />
                        </SelectTrigger>
                        <SelectContent>
                            <template
                                v-for="currency in currencies"
                                :key="currency.id"
                            >
                                <SelectItem :value="currency.id">
                                    {{ currency.code }} - {{ currency.name }}
                                </SelectItem>
                            </template>
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.direction" />
                </div>

                <div class="grid gap-2">
                    <Label for="rate">Rate</Label>
                    <InputDescription>
                        The exchange rate from source to target currency.
                    </InputDescription>
                    <Input
                        id="rate"
                        name="rate"
                        type="number"
                        step="any"
                        class="mt-1 block w-full"
                        required
                        :default-value="currencyRate.rate"
                    />
                    <InputError :message="errors.rate" />
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
