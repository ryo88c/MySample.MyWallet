<?php
namespace MySample\MyWallet;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use MySample\MyWallet\Client\ApiClientAdapterInterface;
use MySample\MyWallet\Client\CurrencyApiClientAdapter;
use MySample\MyWallet\Client\ExchangeApiClientAdapter;

class ApiAdapterInjectionTest extends TestCase
{
    public function testApiAdapterInjection()
    {
        $injector = new Injector(new FakeApiAdapterInjectionModule());
        $injectedObject = $injector->getInstance(FakeApiAdapterInjectedInterface::class);

        $this->assertInstanceOf(ApiClientAdapterInterface::class, $injectedObject->currencyApi);
        $this->assertInstanceOf(CurrencyApiClientAdapter::class, $injectedObject->currencyApi);
        $this->assertInstanceOf(ApiClientAdapterInterface::class, $injectedObject->exchangeApi);
        $this->assertInstanceOf(ExchangeApiClientAdapter::class, $injectedObject->exchangeApi);
    }
}
