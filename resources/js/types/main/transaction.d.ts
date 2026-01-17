import { ProductFlatDataItem } from '../cms/catalog';
import { ShopDataItem } from '../cms/shop';

export interface Payment {
    id: number;
    driver: string;
    order_id: string;
    transaction_id?: string;
    payment_type: string;
    account_number?: string;
    account_code?: string;
    channel?: string;
    amount: number;
    expired_at?: string;
    paid_at?: string;
}

export interface TransactionDetail {
    id: number;
    product_flat_id: number;
    product_details?: ProductFlatDataItem;
    price: number;
    quantity: number;
    total: number;
    product_flat?: ProductFlatDataItem & { images_1?: string };
}

export interface TransactionShop {
    id: number;
    shop_id: number;
    shop?: ShopDataItem;
    receipt_number?: string;
    courier: string;
    shipment_details: any;
    amount: number;
    shipping_cost: number;
    total_amount: number;
    shipping_status: string;
    transaction_details: TransactionDetail[];
}

export interface Transaction {
    id: number;
    user_id: number;
    reference: string;
    ref_number: string;
    name: string;
    email: string;
    phone: string;
    address: string;
    postcode: string;
    rajaongkir_province_id: number;
    rajaongkir_city_id: number;
    rajaongkir_district_id: number;
    notes?: string;
    transaction_details_count?: number;
    payment_method: string;
    amount: number;
    shipping_cost: number;
    total_amount: number;
    status: number;
    payment_proof?: string;
    created_at: string;
    payment?: Payment;
    transaction_shops?: TransactionShop[];
}
