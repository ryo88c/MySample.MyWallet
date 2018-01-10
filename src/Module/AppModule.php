<?php
namespace MySample\MyWallet\Module;

use BEAR\Package\PackageModule;
use josegonzalez\Dotenv\Loader as Dotenv;
use MySample\MyWallet\Client\ApiClientAdapterInterface;
use MySample\MyWallet\Client\CurrencyApiClientAdapter;
use MySample\MyWallet\Client\ExchangeApiClientAdapter;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        Dotenv::load([
            'filepath' => dirname(dirname(__DIR__)) . '/.env',
            'toEnv' => true
        ]);
        $this->install(new PackageModule);

        $this->bind()->annotatedWith('exchange_config')
             ->toInstance([
                 'poloniex' => [
                     'api_key' => $_ENV['POLONIEX_API_KEY'],
                     'api_secret' => $_ENV['POLONIEX_API_SECRET']
                 ]
             ]);

        $this->bind(ApiClientAdapterInterface::class)
             ->annotatedWith('exchangeApi')
             ->to(ExchangeApiClientAdapter::class);

        $this->bind(ApiClientAdapterInterface::class)
             ->annotatedWith('currencyApi')
             ->to(CurrencyApiClientAdapter::class);
    }
}
