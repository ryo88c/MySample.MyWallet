<?php
namespace MySample\MyWallet;

use Ray\Di\AbstractModule;
use MySample\MyWallet\Client\ApiClientAdapterInterface;
use MySample\MyWallet\Client\CurrencyApiClientAdapter;
use MySample\MyWallet\Client\ExchangeApiClientAdapter;

class FakeApiAdapterInjectionModule extends AbstractModule
{
    public function configure()
    {
        $this->bind()->annotatedWith('exchange_config')
             ->toInstance([
                 'poloniex' => [
                     'api_key' => 'key',
                     'api_secret' => 'secret'
                 ]
             ]);

        $this->bind(ApiClientAdapterInterface::class)
             ->annotatedWith('exchangeApi')
             ->to(ExchangeApiClientAdapter::class);

        $this->bind(ApiClientAdapterInterface::class)
             ->annotatedWith('currencyApi')
             ->to(CurrencyApiClientAdapter::class);

        $this->bind(FakeApiAdapterInjectedInterface::class)->to(FakeApiAdapterInjectedObject::class);
    }
}