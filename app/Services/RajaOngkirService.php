<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    private string $cachePrefix = 'rajaongkir:service:';

    private int $defaultCacheTtl = 60; // in minutes

    /**
     * Create a new service instance.
     */
    public function __construct(
        public ?string $shippingCostApiKey = null,
        public ?string $shippingDeliveryApiKey = null,
        public string $shippingCostBaseUrl = 'https://rajaongkir.komerce.id/api/v1/',
    ) {
        // Load API keys from configuration if not provided
        $this->shippingCostApiKey = config('services.rajaongkir.shipping_cost_api_key', $this->shippingCostApiKey);
        $this->shippingDeliveryApiKey = config('services.rajaongkir.shipping_delivery_api_key', $this->shippingDeliveryApiKey);
    }

    /**
     * Shipping Cost API - Get List of Provinces
     */
    public function getListProvinces(): array
    {
        try {
            return Cache::remember($this->cachePrefix.'provinces', now()->addMinutes($this->defaultCacheTtl), function () {
                $response = Http::withHeaders([
                    'key' => $this->shippingCostApiKey,
                ])->get($this->shippingCostBaseUrl.'destination/province');

                // Check for successful response
                if ($response->failed()) {
                    throw new \Exception('Failed to fetch provinces: '.$response->body());
                }

                return $response->json()['data'] ?? [];
            });
        } catch (\Exception $e) {
            Log::error('Error fetching provinces from RajaOngkir: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Shipping Cost API - Get List of Cities by Province ID
     */
    public function getListCities(int $provinceId): array
    {
        try {
            return Cache::remember($this->cachePrefix.'cities:province:'.$provinceId, now()->addMinutes($this->defaultCacheTtl), function () use ($provinceId) {
                $response = Http::withHeaders([
                    'key' => $this->shippingCostApiKey,
                ])->get($this->shippingCostBaseUrl.'destination/city/'.$provinceId);

                // Check for successful response
                if ($response->failed()) {
                    throw new \Exception('Failed to fetch cities: '.$response->body());
                }

                return $response->json()['data'] ?? [];
            });
        } catch (\Exception $e) {
            Log::error('Error fetching cities from RajaOngkir: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Shipping Cost API - Get District by City ID
     */
    public function getListDistricts(int $cityId): array
    {
        try {
            return Cache::remember($this->cachePrefix.'districts:city:'.$cityId, now()->addMinutes($this->defaultCacheTtl), function () use ($cityId) {
                $response = Http::withHeaders([
                    'key' => $this->shippingCostApiKey,
                ])->get($this->shippingCostBaseUrl.'destination/district/'.$cityId);

                // Check for successful response
                if ($response->failed()) {
                    throw new \Exception('Failed to fetch districts: '.$response->body());
                }

                return $response->json()['data'] ?? [];
            });
        } catch (\Exception $e) {
            Log::error('Error fetching districts from RajaOngkir: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Shipping Cost API - Get Subdistrict by District ID
     */
    public function getListSubDistricts(int $districtId): array
    {
        try {
            return Cache::remember($this->cachePrefix.'subdistricts:district:'.$districtId, now()->addMinutes($this->defaultCacheTtl), function () use ($districtId) {
                $response = Http::withHeaders([
                    'key' => $this->shippingCostApiKey,
                ])->get($this->shippingCostBaseUrl.'destination/sub-district/'.$districtId);

                // Check for successful response
                if ($response->failed()) {
                    throw new \Exception('Failed to fetch subdistricts: '.$response->body());
                }

                return $response->json()['data'] ?? [];
            });
        } catch (\Exception $e) {
            Log::error('Error fetching subdistricts from RajaOngkir: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Shipping Cost API - Calculate Shipping Cost
     */
    public function calculateShippingCost(
        int $origin,
        int $destination,
        int $weight = 1, // in grams
        ?float $height = 0,
        ?float $width = 0,
        ?float $length = 0,
        ?float $diameter = 0
    ): array {
        try {
            return Cache::remember($this->cachePrefix.'shipping-cost:origin:'.$origin.':destination:'.$destination.':weight:'.$weight.':height:'.$height.':width:'.$width.':length:'.$length.':diameter:'.$diameter, now()->addMinutes($this->defaultCacheTtl), function () use (
                $origin,
                $destination,
                $weight,
                $height,
                $width,
                $length,
                $diameter,
            ) {
                $response = Http::withHeaders([
                    'key' => $this->shippingCostApiKey,
                ])
                    ->asForm()
                    ->post($this->shippingCostBaseUrl.'calculate/district/domestic-cost', [
                        'origin' => $origin,
                        'destination' => $destination,
                        'courier' => 'jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse',
                        'weight' => $weight,
                        'height' => $height,
                        'width' => $width,
                        'length' => $length,
                        'diameter' => $diameter,
                        'price' => 'lowest',
                    ]);

                // Check for successful response
                if ($response->failed()) {
                    throw new \Exception('Failed to calculate shipping cost: '.$response->body());
                }

                return $response->json()['data'] ?? [];
            });
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost from RajaOngkir: '.$e->getMessage());
            throw $e;
        }
    }
}
