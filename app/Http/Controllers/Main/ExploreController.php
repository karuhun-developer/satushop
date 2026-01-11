<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Attribute\Attribute;
use App\Models\Catalog\ProductCategory;
use App\Models\Catalog\ProductFlat;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        return inertia('main/Explore', [
            'products' => inertia()->scroll(ProductFlat::query()
                ->with('media')
                ->when($request->input('attribute_option_id'), function ($query, $attributeOptionIds) {
                    $query->whereHas('product.attributes', function ($q) use ($attributeOptionIds) {
                        $q->whereIn('attribute_option_id', (array) $attributeOptionIds);
                    });
                })
                ->when($request->input('price_min'), function ($query, $priceMin) {
                    $query->where('price', '>=', $priceMin);
                })
                ->when($request->input('price_max'), function ($query, $priceMax) {
                    $query->where('price', '<=', $priceMax);
                })
                ->when($request->input('search'), function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
                })
                ->where('visible_individually', true)
                ->orderByDesc('rating')
                ->cursorPaginate(12)
                ->through(function ($item) {
                    $item->image_1 = $item->getFirstMediaUrl('image_1');
                    $item->makeHidden('media');
                    return $item;
                })),
            'categories' => inertia()->defer(fn () => ProductCategory::query()
                ->with('media')
                ->where('status', true)
                ->get()
                ->map(function ($item) {
                    $item->image = $item->getFirstMediaUrl('image');
                    $item->makeHidden('media');
                    return $item;
                })
            ),
            'attributes' => inertia()->defer(fn () => Attribute::query()
                ->with('options')
                ->get()
            ),
            'filters' => $request->only(['attribute_option_id', 'price_min', 'price_max', 'search']),
        ]);
    }

    public function category(Request $request, ProductCategory $category)
    {
        return inertia('main/Explore', [
            'currentCategory' => $category,
            'products' => inertia()->scroll(ProductFlat::query()
                ->with('media')
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('product_category_id', $category->id);
                })
                ->when($request->input('attribute_option_id'), function ($query, $attributeOptionIds) {
                    $query->whereHas('product.attributes', function ($q) use ($attributeOptionIds) {
                        $q->whereIn('attribute_option_id', (array) $attributeOptionIds);
                    });
                })
                ->when($request->input('price_min'), function ($query, $priceMin) {
                    $query->where('price', '>=', $priceMin);
                })
                ->when($request->input('price_max'), function ($query, $priceMax) {
                    $query->where('price', '<=', $priceMax);
                })
                ->when($request->input('search'), function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
                })
                ->where('visible_individually', true)
                ->orderByDesc('rating')
                ->cursorPaginate(12)
                ->through(function ($item) {
                    $item->image_1 = $item->getFirstMediaUrl('image_1');
                    $item->makeHidden('media');
                    return $item;
                })),
            'categories' => inertia()->defer(fn () => ProductCategory::query()
                ->with('media')
                ->where('status', true)
                ->get()
                ->map(function ($item) {
                    $item->image = $item->getFirstMediaUrl('image');
                    $item->makeHidden('media');
                    return $item;
                })
            ),
            'attributes' => inertia()->defer(fn () => Attribute::query()
                ->with('options')
                ->get()
            ),
            'filters' => $request->only(['attribute_option_id', 'price_min', 'price_max', 'search']),
        ]);
    }
}
