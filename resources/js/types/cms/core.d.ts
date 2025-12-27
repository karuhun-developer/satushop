export interface CurrencyDataItem {
    id: number;
    code: string;
    name: string;
    is_default: boolean;
    created_at: string;
    updated_at: string;
}

export interface CurrencyRateDataItem {
    id: number;
    target_currency_id: number;
    target_currency_code?: string;
    target_currency_name?: string;
    rate: number;
    created_at: string;
    updated_at: string;
    target_currency?: CurrencyDataItem;
}

export interface LocaleDataItem {
    id: number;
    code: string;
    name: string;
    direction: 'ltr' | 'rtl';
    is_default: boolean;
    created_at: string;
    updated_at: string;
}
