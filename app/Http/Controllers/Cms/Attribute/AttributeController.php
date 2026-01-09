<?php

namespace App\Http\Controllers\Cms\Attribute;

use App\Actions\Cms\Attribute\Attribute\DeleteAttributeAction;
use App\Actions\Cms\Attribute\Attribute\StoreAttributeAction;
use App\Actions\Cms\Attribute\Attribute\UpdateAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Attribute\Attribute\StoreAttributeRequest;
use App\Http\Requests\Cms\Attribute\Attribute\UpdateAttributeRequest;
use App\Models\Attribute\Attribute;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttributeController extends Controller
{
    use WithGetFilterData;

    protected string $resource = Attribute::class;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view'.$this->resource);

        $order = $request?->order ?? 'asc';
        $orderBy = $request?->orderBy ?? 'order';
        $paginate = $request?->paginate ?? 10;
        $searchBySpecific = $request?->searchBySpecific ?? '';
        $search = $request?->search ?? '';

        $model = $this->getDataWithFilter(
            model: new Attribute,
            searchBy: [
                'code',
                'name',
                'order',
                'status',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/attribute/attribute/Index', [
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

        return inertia('cms/attribute/attribute/Create', [
            'locales' => getLocales(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request, StoreAttributeAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/attribute/attribute/Edit', [
            'locales' => getLocales(),
            'attribute' => $attribute->load('translations', 'options.translations'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute, UpdateAttributeAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($attribute, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute, DeleteAttributeAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($attribute);

        return back();
    }
}
