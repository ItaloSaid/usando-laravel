<?php
namespace Tests\Feature;


use App\CurrencyExchange\DollarExchangeWithSpread;
use App\Data\InputData;
use App\Exceptions\CustomerNotFoundException;
use App\Fakes\MyQuotationApiFake;
use App\Models\Customer;
use App\Repositories\EloquentCustomerRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DolarExchangeWithSpreadTest extends TestCase
{
    use RefreshDatabase;
    public function testItShouldCalculateDollarExchangeWhenValidDateIsProvided()
    {

        Customer::factory()->create([
            'id' => 1,
            'name' => 'João',
            'email' => 'joao@gmail.com'
        ]);

        $inputData = new InputData(1, 30);
        $myQuotationApiFake = new MyQuotationApiFake();
        $customerRepository = new EloquentCustomerRepository();
        $sut = new DollarExchangeWithSpread($myQuotationApiFake, $customerRepository);
        $output = $sut->execute($inputData);

        $this->assertSame(1, $output['id']);
        $this->assertSame('João', $output['name']);
        $this->assertSame('joao@gmail.com', $output['email']);
        $this->assertEquals(5, $output['dollarQuotation']);
        $this->assertEquals(150, $output['amountWithoutSpread']);
        $this->assertEquals(155.25, $output['amountWithSpread']);
        $this->assertGreaterThan($output['amountWithoutSpread'], $output['amountWithSpread']);
    }

    public function testItShouldThrowAnExceptionWhenCustomerIsNotExists()
    {
        $this->expectException(CustomerNotFoundException::class);
        $inputData = new InputData(99999, 30);
        $myQuotationApiFake = new MyQuotationApiFake();
        $customerRepository = new EloquentCustomerRepository();
        $sut = new DollarExchangeWithSpread($myQuotationApiFake, $customerRepository);
        $sut->execute($inputData);
    }

    public function testItShouldReturnCorrectSpreadValueAccordingToTheAmountContributed()
    {
        Customer::factory()->create([
            'id' => 1,
            'name' => 'João',
            'email' => 'joao@gmail.com'
        ]);

        $inputData = new InputData(1, 30);
        $myQuotationApiFake = new MyQuotationApiFake();
        $customerRepository = new EloquentCustomerRepository();
        $sut = new DollarExchangeWithSpread($myQuotationApiFake, $customerRepository);
        $output = $sut->execute($inputData);

        $this->assertEquals(150, $output['amountWithoutSpread']);
        $this->assertEquals(155.25, $output['amountWithSpread']);
    }
}

