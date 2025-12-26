<?php

namespace App\Http\Controllers\Cms\Core;

use App\Actions\Cms\Core\Locale\DeleteLocaleAction;
use App\Actions\Cms\Core\Locale\StoreLocaleAction;
use App\Actions\Cms\Core\Locale\UpdateLocaleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Core\Locale\StoreLocaleRequest;
use App\Http\Requests\Cms\Core\Locale\UpdateLocaleRequest;
use App\Models\Core\Locale;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LocaleController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Locale::class;

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
            model: new Locale,
            searchBy: [
                'code',
                'name',
                'direction',
                'is_default',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/core/locale/Index', [
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

        return inertia('cms/core/locale/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocaleRequest $request, StoreLocaleAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Locale $locale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locale $locale)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/core/locale/Edit', [
            'locale' => $locale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocaleRequest $request, Locale $locale, UpdateLocaleAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($locale, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locale $locale, DeleteLocaleAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($locale);

        return back();
    }
}
