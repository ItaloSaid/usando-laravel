<?php

namespace App\CurrencyExchange;

use App\Data\InputData;
use App\Exceptions\CustomerNotFoundException;
use App\Repositories\CustomerRepository;
use App\Models\Customer;
use App\Services\DollarRealQuotationApi;
use InvalidArgumentException;

// Importe o modelo Eloquent Customer

class DollarExchangeWithSpread
{
    public function __construct(
        private readonly DollarRealQuotationApi $dollarRealQuotationApi,
        private readonly CustomerRepository $customerRepository  // Atualize o tipo aqui
    )
    {
    }

    public function execute(InputData $inputData) : array
    {
        dump($inputData);
        if(!isset($inputData->customerId)){
            throw new InvalidArgumentException();
        }

        $customer = $this->customerRepository->findByID($inputData->customerId);
        if(is_null($customer)){
            throw new CustomerNotFoundException();
        }

        //5 R$
        $dollarRealQuotation = $this->dollarRealQuotationApi->getQuotation();
        $amountWithSpread = 0;

        if ($inputData->amountUsd < 100) {
            $amountWithSpread = $inputData->amountUsd * ($dollarRealQuotation + ($dollarRealQuotation * 0.035));
        }

        if ($inputData->amountUsd >= 100 && $inputData->amountUsd < 200)  {
            $amountWithSpread = $inputData->amountUsd * ($dollarRealQuotation + ($dollarRealQuotation * 0.025));
        }

        if ($inputData->amountUsd >= 200)  {
            $amountWithSpread = $inputData->amountUsd * ($dollarRealQuotation + ($dollarRealQuotation * 0.015));
        }


        return [
            'id' => $customer['id'],
            'name' => $customer['name'],
            'email' => $customer['email'],
            'dollarQuotation' => $dollarRealQuotation,
            'amountWithoutSpread' => $inputData->amountUsd * $dollarRealQuotation,
            'amountWithSpread' =>  $amountWithSpread,
        ];
    }
}
