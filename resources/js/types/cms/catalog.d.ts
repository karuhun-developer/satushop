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
