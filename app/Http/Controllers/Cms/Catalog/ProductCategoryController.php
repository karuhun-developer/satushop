<?php

namespace App\Http\Controllers\Cms\Catalog;

use App\Actions\Cms\Catalog\ProductCategory\DeleteProductCategoryAction;
use App\Actions\Cms\Catalog\ProductCategory\StoreProductCategoryAction;
use App\Actions\Cms\Catalog\ProductCategory\UpdateProductCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Catalog\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\Cms\Catalog\ProductCategory\UpdateProductCategoryRequest;
use App\Models\Catalog\ProductCategory;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
{
    use WithGetFilterData;

    protected string $resource = ProductCategory::class;

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
            model: ProductCategory::with('media'),
            searchBy: [
                'name',
                'status',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        // Load media
        $model->map(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');

            return $item;
        });

        return inertia('cms/catalog/product-category/Index', [
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

        return inertia('cms/catalog/product-category/Create', [
            'locales' => getLocales(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request, StoreProductCategoryAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        Gate::authorize('update'.$this->resource);

        // Load image url
        $productCategory->image = $productCategory->getFirstMediaUrl('image');
        $productCategory->load('translations');

        return inertia('cms/catalog/product-category/Edit', [
            'locales' => getLocales(),
            'productCategory' => $productCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory, UpdateProductCategoryAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($productCategory, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory, DeleteProductCategoryAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($productCategory);

        return back();
    }
}
