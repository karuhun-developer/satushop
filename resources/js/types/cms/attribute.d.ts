export interface AttributeFamilyDataItem {
    id: number;
    code: string;
    name: string;
    status: boolean;
    created_at: string;
    updated_at: string;
}

export interface AttributeDataItem {
    id: number;
    attribute_family_id: number;
    attribute_family_name?: string;
    attribute_family_code?: string;
    code: string;
    name: string;
    order: number;
    status: boolean;
    created_at: string;
    updated_at: string;
    family?: AttributeFamilyDataItem;
    translations?: AttributeDataTranslationItem[];
    options?: AttributeOptionDataItem[];
}

export interface AttributeDataTranslationItem {
    id: number;
    attribute_id: number;
    locale: string;
    name: string;
    created_at: string;
    updated_at: string;
    attribute?: AttributeDataItem;
}

export interface AttributeOptionDataItem {
    id: number;
    attribute_id: number;
    name: string;
    order: number;
    status: boolean;
    created_at: string;
    updated_at: string;
    attribute?: AttributeDataItem;
    translations?: AttributeOptionDataTranslationItem[];
}

export interface AttributeOptionDataTranslationItem {
    id: number;
    attribute_option_id: number;
    locale: string;
    name: string;
    created_at: string;
    updated_at: string;
    attribute_option?: AttributeOptionDataItem;
}
