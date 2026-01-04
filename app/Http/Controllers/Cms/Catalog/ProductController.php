<?php

namespace App\Http\Controllers\Cms\Catalog;

use App\Actions\Cms\Catalog\Product\DeleteProductAction;
use App\Actions\Cms\Catalog\Product\StoreProductAction;
use App\Actions\Cms\Catalog\Product\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Catalog\Product\StoreProductRequest;
use App\Http\Requests\Cms\Catalog\Product\UpdateProductRequest;
use App\Models\Attribute\AttributeFamily;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFlat;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Product::class;

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
        $model = $this->getDataWithFilter(
            model: ProductFlat::with('product.attributeFamily', 'media'),
            searchBy: [
                'sku',
                'name',
                'slug',
                'price',
                'stock',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        // Load media
        $model->map(function ($item) {
            $item->image_1 = $item->getFirstMediaUrl('image_1');

            return $item;
        });

        return inertia('cms/catalog/product/Index', [
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

        return inertia('cms/catalog/product/Create', [
            'attributeFamilies' => AttributeFamily::where('status', true)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, StoreProductAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductFlat $product)
    {
        Gate::authorize('update'.$this->resource);

        // Load image url
        $product->image = $product->getFirstMediaUrl('image');
        $product->load('translations');

        return inertia('cms/catalog/product/Edit', [
            'locales' => getLocales(),
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, ProductFlat $product, UpdateProductAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($product, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductFlat $product, DeleteProductAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($product);

        return back();
    }
}
