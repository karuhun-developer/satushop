import { CurrencyDataItem } from './currency';

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
