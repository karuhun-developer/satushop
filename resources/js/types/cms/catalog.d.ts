import {
    AttributeDataItem,
    AttributeFamilyDataItem,
    AttributeOptionDataItem,
} from './attribute';
import { ShopDataItem } from './shop';

export interface ProductCategoryDataItem {
    id: number;
    name: string;
    slug: string;
    status: boolean;
    image?: string;
    created_at: string;
    updated_at: string;
    translations?: ProductCategoryDataTranslationItem[];
}

export interface ProductCategoryDataTranslationItem {
    id: number;
    product_category_id: number;
    locale: string;
    name?: string;
    description?: string;
    created_at: string;
    updated_at: string;
    product_category?: ProductCategoryDataItem;
}

export interface ProductDataItem {
    id: number;
    shop_id: number;
    attribute_family_id: number;
    type: string;
    sku: string;
    created_at: string;
    updated_at: string;
    shop?: ShopDataItem;
    attribute_family?: AttributeFamilyDataItem;
}

export interface ProductFlatDataItem {
    id: number;
    product_id: number;
    sku: string;
    name: string;
    slug: string;
    short_description?: string;
    description?: string;
    // meta_data?: Record<string, unknown>;
    meta_data?: any;
    price: number | string;
    special_price?: number;
    special_price_start?: string;
    special_price_end?: string;
    weight?: number;
    length?: number;
    width?: number;
    height?: number;
    diameter?: number;
    stock?: number;
    type: string;
    rating: number;
    visible_individually: boolean;
    created_at: string;
    updated_at: string;
    image_1?: string;
    image_2?: string;
    image_3?: string;
    image_4?: string;
    image_5?: string;
    image_6?: string;
    image_7?: string;
    image_8?: string;
    image_9?: string;
    image_10?: string;
    product?: ProductDataItem;
    categories?: ProductFlatCategoryDataItem[];
    variants?: ProductVariantDataItem[];
    translations?: ProductFlatTranslationDataItem[];
}

export interface ProductFlatTranslationDataItem {
    id: number;
    product_flat_id: number;
    locale: string;
    name: string;
    short_description?: string;
    description?: string;
    created_at: string;
    updated_at: string;
    product_flat?: ProductFlatDataItem;
}

export interface ProductFlatCategoryDataItem {
    id: number;
    product_flat_id: number;
    product_category_id: number;
    created_at: string;
    updated_at: string;
    product_flat?: ProductFlatDataItem;
    product_category?: ProductCategoryDataItem;
}

export interface ProductAttributeDataItem {
    id: number;
    product_id: number;
    product_flat_id: number;
    attribute_id: number;
    attribute_option_id?: number;
    created_at: string;
    updated_at: string;
    product?: ProductDataItem;
    product_flat?: ProductFlatDataItem;
    attribute?: AttributeDataItem;
    attribute_option?: AttributeOptionDataItem;
}

export interface ProductVariantDataItem {
    id: number;
    product_id: number;
    parent_product_id: number;
    variant_product_id: number;
    created_at: string;
    updated_at: string;
    product?: ProductDataItem;
    parent_product?: ProductDataItem;
    variant_product?: ProductDataItem;
}
