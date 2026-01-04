import type { UseInfiniteQueryReturnType } from '@tanstack/vue-query';
import { useInfiniteQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { computed, MaybeRef, unref } from 'vue';

export interface ShopServiceItem {
    id: number;
    name: string;
}

export function useShopQuery() {
    const fetchShops = (
        search: MaybeRef<string> = '',
        paginate: number = 10,
        options: { enabled?: MaybeRef<boolean> } = {},
    ): UseInfiniteQueryReturnType<any, Error> => {
        return useInfiniteQuery({
            queryKey: ['fetchShops', search],
            queryFn: async ({ pageParam = 1 }) => {
                const searchValue = unref(search);
                const res = await axios.get('/service/shop/shop', {
                    params: {
                        search: searchValue,
                        page: pageParam,
                        paginate,
                    },
                });

                if (res.status !== 200) {
                    throw new Error('Failed to fetch shops');
                }

                // API returns { code, message, data: { current_page, data: [], ... } }
                return res.data.data;
            },
            getNextPageParam: (lastPage: any) => {
                if (lastPage.next_page_url) {
                    return lastPage.current_page + 1;
                }
                return undefined;
            },
            initialPageParam: 1,
            enabled: computed(() => {
                const isEnabled = unref(options.enabled);
                return isEnabled !== undefined ? isEnabled : true;
            }),
        });
    };

    return {
        fetchShops,
    };
}
