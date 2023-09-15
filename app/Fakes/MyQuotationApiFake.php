<?php

namespace App\Fakes;

use App\Services\DollarRealQuotationApi;

class MyQuotationApiFake implements DollarRealQuotationApi
{
    public function getQuotation(): float
    {
        return 5.00;
    }
}

