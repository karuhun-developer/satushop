<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductCategory;
use App\Models\Catalog\ProductFlat;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return inertia('main/Home', [
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
            'bestProducts' => inertia()->defer(fn () => ProductFlat::query()
                ->with('media', 'firstVariant.variantProduct', 'product')
                ->where('visible_individually', true)
                ->orderByDesc('rating')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $item->image_1 = $item->getFirstMediaUrl('image_1');
                    $item->makeHidden('media');

                    return $item;
                })
            ),
            'newProducts' => inertia()->defer(fn () => ProductFlat::query()
                ->with('media', 'firstVariant.variantProduct', 'product')
                ->where('visible_individually', true)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $item->image_1 = $item->getFirstMediaUrl('image_1');
                    $item->makeHidden('media');

                    return $item;
                })
            ),
            'bestSellers' => inertia()->defer(fn () => ProductFlat::query()
                ->with('media', 'firstVariant.variantProduct', 'product')
                ->where('visible_individually', true)
                ->orderByDesc('sold_count')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $item->image_1 = $item->getFirstMediaUrl('image_1');
                    $item->makeHidden('media');

                    return $item;
                })
            ),
        ]);
    }
}
