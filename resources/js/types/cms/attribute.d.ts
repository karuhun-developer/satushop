export interface AttributeFamilyDataItem {
    id: number;
    code: string;
    name: string;
    status: boolean;
    created_at: string;
    updated_at: string;
    groups?: AttributeGroupItem[];
}

export interface AttributeDataItem {
    id: number;
    code: string;
    name: string;
    order: number;
    status: boolean;
    created_at: string;
    updated_at: string;
    translations?: AttributeDataTranslationItem[];
    options?: AttributeOptionDataItem[];
    groups?: AttributeGroupItem[];
}

export interface AttributeDataTranslationItem {
    id: number;
    attribute_id: number;
    locale: string;
    name?: string;
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
    name?: string;
    created_at: string;
    updated_at: string;
    attribute_option?: AttributeOptionDataItem;
}

export interface AttributeGroupItem {
    id: number;
    attribute_family_id: number;
    attribute_id: number;
    created_at: string;
    updated_at: string;
    attribute?: AttributeDataItem;
    attribute_family?: AttributeFamilyDataItem;
}
