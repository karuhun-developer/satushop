<?php

namespace App\Http\Controllers\Cms\Shop;

use App\Actions\Cms\Shop\Shop\DeleteShopAction;
use App\Actions\Cms\Shop\Shop\StoreShopAction;
use App\Actions\Cms\Shop\Shop\UpdateShopAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Shop\Shop\StoreShopRequest;
use App\Http\Requests\Cms\Shop\Shop\UpdateShopRequest;
use App\Models\Shop\Shop;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShopController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Shop::class;

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

        // Query
        $model = Shop::query()
            ->with('media');

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'slug',
                'phone',
                'email',
                'address',
                'rating',
                'total_reviews',
                'status',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/shop/shop/Index', [
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

        return inertia('cms/shop/shop/Create', [
            'locales' => getLocales(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request, StoreShopAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        Gate::authorize('update'.$this->resource);

        // Load image url
        $shop->logo_url = $shop->getFirstMediaUrl('logo');
        $shop->banner_url = $shop->getFirstMediaUrl('banner');
        $shop->load('translations');

        return inertia('cms/shop/shop/Edit', [
            'locales' => getLocales(),
            'shop' => $shop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop, UpdateShopAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($shop, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop, DeleteShopAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($shop);

        return back();
    }
}
