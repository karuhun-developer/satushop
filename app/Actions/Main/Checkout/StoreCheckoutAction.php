<?php

namespace App\Actions\Cms\Management\Role;

use App\Models\Transaction\Transaction;

class StoreRoleAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Transaction
    {
        return Transaction::create($data);
    }
}
