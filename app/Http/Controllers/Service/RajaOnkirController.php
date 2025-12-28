<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Traits\WithReturnResponse;
use Illuminate\Http\Request;

class RajaOnkirController extends Controller
{
    use WithReturnResponse;

    public function __construct(
        public readonly RajaOngkirService $rajaOngkirService,
    ) {}

    public function provinces()
    {
        return $this->responseWithSuccess(
            data: $this->rajaOngkirService->getListProvinces()
        );
    }

    public function cities(int $provinceId)
    {
        return $this->responseWithSuccess(
            data: $this->rajaOngkirService->getListCities(provinceId: $provinceId)
        );
    }

    public function districts(int $cityId)
    {
        return $this->responseWithSuccess(
            data: $this->rajaOngkirService->getListDistricts(cityId: $cityId)
        );
    }

    public function subDistricts(int $districtId)
    {
        return $this->responseWithSuccess(
            data: $this->rajaOngkirService->getListSubDistricts(districtId: $districtId)
        );
    }

    public function cost(Request $request)
    {
        $request->validate([
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'diameter' => 'nullable|numeric|min:0',
        ]);

        $costs = $this->rajaOngkirService->calculateShippingCost(
            origin: $request->input('origin'),
            destination: $request->input('destination'),
            weight: $request->input('weight'),
            courier: $request->input('courier'),
            height: $request->input('height'),
            width: $request->input('width'),
            length: $request->input('length'),
            diameter: $request->input('diameter'),
        );

        return $this->responseWithSuccess(
            data: $costs,
        );
    }
}
