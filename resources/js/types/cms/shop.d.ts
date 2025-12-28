export interface ShopDataItem {
    id: number;
    name: string;
    slug: string;
    phone?: string;
    email?: string;
    postal_code?: string;
    address?: string;
    latitude: number;
    longitude: number;
    rating: number;
    total_reviews: number;
    logo_url?: string;
    banner_url?: string;
    translations?: ShopTranslationDataItem[];
    rajaongkir_province_id?: number;
    rajaongkir_city_id?: number;
    rajaongkir_district_id?: number;
    status: number;
    created_at: string;
    updated_at: string;
}

export interface ShopTranslationDataItem {
    id: number;
    shop_id: number;
    locale: string;
    name?: string;
    description?: string;
    created_at: string;
    updated_at: string;
}
