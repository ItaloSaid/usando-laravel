<?php

namespace App\Repositories;

use App\Models\Customer;

class EloquentCustomerRepository implements CustomerRepository
{
    public function findByID(int $id): ?Customer
    {
        return Customer::find($id);
    }
}

