<?php

namespace App\Http\Controllers\Cms\User;

use App\Actions\Cms\User\UserAddress\DeleteUserAddressAction;
use App\Actions\Cms\User\UserAddress\StoreUserAddressAction;
use App\Actions\Cms\User\UserAddress\UpdateUserAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\User\UserAddress\StoreUserAddressRequest;
use App\Http\Requests\Cms\User\UserAddress\UpdateUserAddressRequest;
use App\Models\User\UserAddress;
use App\Traits\WithGetFilterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserAddressController extends Controller
{
    use WithGetFilterData;

    protected string $resource = UserAddress::class;

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
            model: new UserAddress,
            searchBy: [
                'name',
                'phone',
                'address',
                'postcode',
            ],
            order: $order,
            orderBy: $orderBy,
            paginate: $paginate,
            searchBySpecific: $searchBySpecific,
            s: $search,
        );

        return inertia('cms/user/user-address/Index', [
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

        return inertia('cms/user/user-address/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserAddressRequest $request, StoreUserAddressAction $action)
    {
        Gate::authorize('create'.$this->resource);

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $action->handle($data);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAddress $userAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAddress $userAddress)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/user/user-address/Edit', [
            'userAddress' => $userAddress,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserAddressRequest $request, UserAddress $userAddress, UpdateUserAddressAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($userAddress, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $userAddress, DeleteUserAddressAction $action)
    {
        Gate::authorize('delete'.$this->resource);

        $action->handle($userAddress);

        return back();
    }
}
