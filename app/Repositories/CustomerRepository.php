<?php

namespace App\Repositories;

use App\Models\Customer;

interface CustomerRepository
{
    public function findByID(int $id): ?Customer;
}

