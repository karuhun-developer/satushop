import {
    CityDataItem,
    DistrictDataItem,
    ProvinceDataItem,
    SubdistrictDataItem,
} from '@/types/service/rajaongkir';
import type { UseQueryReturnType } from '@tanstack/vue-query';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { computed, MaybeRef, unref } from 'vue';

export function useRajaongkirQuery() {
    const fetchProvinces = (
        staleTime: number = 0,
    ): UseQueryReturnType<ProvinceDataItem[], Error> => {
        return useQuery({
            queryKey: ['fetchProvinces'],
            queryFn: async () => {
                const res = await axios.get('/service/rajaongkir/provinces');

                if (res.status !== 200) {
                    throw new Error('Failed to fetch provinces');
                }

                return res.data.data;
            },
            staleTime,
        });
    };

    const fetchCities = (
        provinceId: MaybeRef<number>,
        staleTime: number = 0,
    ): UseQueryReturnType<CityDataItem[], Error> => {
        return useQuery({
            queryKey: ['fetchCities', provinceId],
            queryFn: async () => {
                const id = unref(provinceId);
                if (!id) return [];

                const res = await axios.get(
                    `/service/rajaongkir/cities/${id}`,
                );
                if (res.status !== 200) {
                    throw new Error('Failed to fetch cities');
                }

                return res.data.data;
            },
            enabled: computed(() => !!unref(provinceId)),
            staleTime,
        });
    };

    const fetchDistricts = (
        cityId: MaybeRef<number>,
        staleTime: number = 0,
    ): UseQueryReturnType<DistrictDataItem[], Error> => {
        return useQuery({
            queryKey: ['fetchDistricts', cityId],
            queryFn: async () => {
                const id = unref(cityId);
                if (!id) return [];

                const res = await axios.get(
                    `/service/rajaongkir/districts/${id}`,
                );
                if (res.status !== 200) {
                    throw new Error('Failed to fetch districts');
                }

                return res.data.data;
            },
            enabled: computed(() => !!unref(cityId)),
            staleTime,
        });
    };

    const fetchSubdistricts = (
        districtId: MaybeRef<number>,
        staleTime: number = 0,
    ): UseQueryReturnType<SubdistrictDataItem[], Error> => {
        return useQuery({
            queryKey: ['fetchSubdistricts', districtId],
            queryFn: async () => {
                const id = unref(districtId);
                if (!id) return [];

                const res = await axios.get(
                    `/service/rajaongkir/sub-districts/${id}`,
                );

                if (res.status !== 200) {
                    throw new Error('Failed to fetch subdistricts');
                }

                return res.data.data;
            },
            enabled: computed(() => !!unref(districtId)),
            staleTime,
        });
    };

    const fetchShippingCosts = (
        originId: number,
        destinationId: number,
        weight: number,
        courier: string,
        staleTime: number = 0,
    ): UseQueryReturnType<any, Error> => {
        return useQuery({
            queryKey: [
                'fetchShippingCosts',
                originId,
                destinationId,
                weight,
                courier,
            ],
            queryFn: async () => {
                const res = await axios.post('/service/rajaongkir/cost', {
                    originId,
                    destinationId,
                    weight,
                    courier,
                });

                if (res.status !== 200) {
                    throw new Error('Failed to fetch shipping costs');
                }

                return res.data.data;
            },
            staleTime,
        });
    };

    return {
        fetchProvinces,
        fetchCities,
        fetchDistricts,
        fetchSubdistricts,
        fetchShippingCosts,
    };
}
