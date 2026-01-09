<?php

namespace App\Http\Controllers\Cms\Attribute;

use App\Actions\Cms\Attribute\AttributeFamily\DeleteAttributeFamilyAction;
use App\Actions\Cms\Attribute\AttributeFamily\StoreAttributeFamilyAction;
use App\Actions\Cms\Attribute\AttributeFamily\UpdateAttributeFamilyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Attribute\AttributeFamily\StoreAttributeFamilyRequest;
use App\Http\Requests\Cms\Attribute\AttributeFamily\UpdateAttributeFamilyRequest;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeFamily;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttributeFamilyController extends Controller
{
    use WithGetFilterData;

    protected string $resource = AttributeFamily::class;

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
            model: new AttributeFamily,
            searchBy: [
                'code',
                'name',
                'status',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/attribute/attribute-family/Index', [
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

        return inertia('cms/attribute/attribute-family/Create', [
            'attributes' => Attribute::where('status', true)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeFamilyRequest $request, StoreAttributeFamilyAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $action->handle($request->validated());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(AttributeFamily $attributeFamily)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttributeFamily $attributeFamily)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/attribute/attribute-family/Edit', [
            'attributes' => Attribute::where('status', true)->get(),
            'attributeFamily' => $attributeFamily->load('groups'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeFamilyRequest $request, AttributeFamily $attributeFamily, UpdateAttributeFamilyAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($attributeFamily, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttributeFamily $attributeFamily, DeleteAttributeFamilyAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($attributeFamily);

        return back();
    }
}
