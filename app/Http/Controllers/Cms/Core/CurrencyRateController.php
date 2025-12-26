<?php

namespace App\Http\Controllers\Cms\Core;

use App\Actions\Cms\Core\CurrencyRate\DeleteCurrencyRateAction;
use App\Actions\Cms\Core\CurrencyRate\StoreCurrencyRateAction;
use App\Actions\Cms\Core\CurrencyRate\UpdateCurrencyRateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Core\CurrencyRate\StoreCurrencyRateRequest;
use App\Http\Requests\Cms\Core\CurrencyRate\UpdateCurrencyRateRequest;
use App\Models\Core\Currency;
use App\Models\Core\CurrencyRate;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CurrencyRateController extends Controller
{
    use WithGetFilterData;

    protected string $resource = CurrencyRate::class;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view'.$this->resource);

        $order = $request?->order ?? 'desc';
        $orderBy = $request?->orderBy ?? 'currency_rates.created_at';
        $paginate = $request?->paginate ?? 10;
        $searchBySpecific = $request?->searchBySpecific ?? '';
        $search = $request?->search ?? '';

        // Query
        $model = CurrencyRate::query()
            ->join('currencies', 'currency_rates.target_currency_id', '=', 'currencies.id')
            ->select(
                'currency_rates.*',
                'currencies.name as target_currency_name',
                'currencies.code as target_currency_code',
            );

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'code',
                'rate',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/core/currency-rate/Index', [
            'data' => $model,
            'order' => $order,
            'orderBy' => $orderBy,
            'paginate' => $paginate,
            'searchBySpecific' => $searchBySpecific,
            'search' => $search,
            'resource' => $this->resource,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create'.$this->resource);

        return inertia('cms/core/currency-rate/Create', [
            'currencies' => Currency::where('is_default', false)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRateRequest $request, StoreCurrencyRateAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CurrencyRate $currencyrate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CurrencyRate $currencyRate)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/core/currency-rate/Edit', [
            'currencyRate' => $currencyRate,
            'currencies' => Currency::where('is_default', false)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRateRequest $request, CurrencyRate $currencyRate, UpdateCurrencyRateAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($currencyRate, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CurrencyRate $currencyRate, DeleteCurrencyRateAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($currencyRate);

        return back();
    }
}
