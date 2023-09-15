<?php

namespace App\Services;

interface DollarRealQuotationApi
{
    public function getQuotation(): float;
}
