<?php

namespace App\Http\Controllers\Cms\Core;

use App\Actions\Cms\Core\Currency\DeleteCurrencyAction;
use App\Actions\Cms\Core\Currency\StoreCurrencyAction;
use App\Actions\Cms\Core\Currency\UpdateCurrencyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Core\Currency\StoreCurrencyRequest;
use App\Http\Requests\Cms\Core\Currency\UpdateCurrencyRequest;
use App\Models\Core\Currency;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Currency::class;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view'.$this->resource);

        $order = $request?->order ?? 'desc';
        $orderBy = $request?->orderBy ?? 'created_at';
        $paginate = $request?->paginate ?? 10;
        $searchBySpecific = $request?->searchBySpecific ?? '';
        $search = $request?->search ?? '';
        $model = $this->getDataWithFilter(
            model: new Currency,
            searchBy: [
                'code',
                'name',
                'is_default',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/core/currency/Index', [
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

        return inertia('cms/core/currency/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request, StoreCurrencyAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/core/currency/Edit', [
            'currency' => $currency,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency, UpdateCurrencyAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($currency, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency, DeleteCurrencyAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($currency);

        return back();
    }
}
