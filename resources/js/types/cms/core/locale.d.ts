export interface LocaleDataItem {
    id: number;
    code: string;
    name: string;
    direction: 'ltr' | 'rtl';
    is_default: boolean;
    created_at: string;
    updated_at: string;
}
