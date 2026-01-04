<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Cms/Catalog/ProductController';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import ServerCombobox from '@/components/ui/combobox/ServerCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { useShopQuery } from '@/composables/query/useShopQuery';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import { ProductTypeEnum } from '@/enums/global.enum';
import { AttributeFamilyDataItem } from '@/types/cms/attribute';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Save } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    attributeFamilies: AttributeFamilyDataItem[];
}>();

const { toast } = useSwal();
const { hasRoles } = usePermission();

const isSuperAdmin = hasRoles(['superadmin']);

// Shop Selection
const { fetchShops } = useShopQuery();
const shopSearch = ref('');
const {
    data: shopData,
    fetchNextPage,
    hasNextPage,
    isFetching,
    isFetchingNextPage,
} = fetchShops(shopSearch, 10, {
    enabled: computed(() => shopSearch.value.length >= 3),
});

const shops = computed(() => {
    return shopData.value?.pages.flatMap((page: any) => page.data) || [];
});

const selectedShop = ref<any>(null);
const shopId = ref<number | null>(null);

watch(selectedShop, (val) => {
    shopId.value = val ? val.id : null;
});
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Create Product
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new product for the application.
            </p>

            <Form
                v-bind="store.form()"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Product created.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2" v-if="isSuperAdmin">
                    <Label for="shop">Shop</Label>
                    <InputDescription
                        >Select the shop to assign this product
                        to.</InputDescription
                    >
                    <ServerCombobox
                        :options="shops"
                        v-model="selectedShop"
                        :loading="isFetching || isFetchingNextPage"
                        @update:search-term="
                            (val: string) => (shopSearch = val)
                        "
                        :search-term="shopSearch"
                        @load-more="() => hasNextPage && fetchNextPage()"
                        :display-value="(option: any) => option?.name"
                        placeholder="Search for a shop..."
                        :min-characters="3"
                    />

                    <Card v-if="selectedShop" class="mt-4">
                        <CardHeader>
                            <CardTitle>{{ selectedShop.name }}</CardTitle>
                            <CardDescription>{{
                                selectedShop.email
                            }}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Phone:</span>
                                    <span class="ml-2 text-muted-foreground">{{
                                        selectedShop.phone
                                    }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Address:</span>
                                    <span class="ml-2 text-muted-foreground">{{
                                        selectedShop.address
                                    }}</span>
                                </div>
                                <div>
                                    <span class="font-medium"
                                        >Postal Code:</span
                                    >
                                    <span class="ml-2 text-muted-foreground">{{
                                        selectedShop.postal_code
                                    }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Input type="hidden" name="shop_id" :value="shopId" />
                    <InputError :message="errors.shop_id" />
                </div>
                <div class="grid gap-2">
                    <Label for="type">Type</Label>
                    <InputDescription> Product type. </InputDescription>
                    <Select name="type" default-value="flat">
                        <SelectTrigger id="type" class="mt-1 w-full">
                            <SelectValue placeholder="Select type" />
                        </SelectTrigger>
                        <SelectContent>
                            <template
                                v-for="type in ProductTypeEnum"
                                :key="type.value"
                            >
                                <SelectItem :value="type.value">
                                    {{ type.label }}
                                </SelectItem>
                            </template>
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.type" />
                </div>

                <div class="grid gap-2">
                    <Label for="attribute_family_id">Attribute Family</Label>
                    <InputDescription>
                        Select the attribute family for this product.
                    </InputDescription>
                    <Select name="attribute_family_id" default-value="">
                        <SelectTrigger
                            id="attribute_family_id"
                            class="mt-1 w-full"
                        >
                            <SelectValue
                                placeholder="Select attribute family"
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <template
                                v-for="family in props.attributeFamilies"
                                :key="family.id"
                            >
                                <SelectItem :value="family.id">
                                    {{ family.name }} ({{ family.code }})
                                </SelectItem>
                            </template>
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.attribute_family_id" />
                </div>

                <div class="grid gap-2">
                    <Label for="sku">SKU</Label>
                    <InputDescription>
                        Stock Keeping Unit, a unique identifier for the product.
                    </InputDescription>
                    <Input
                        id="sku"
                        name="sku"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.sku" />
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
