<?php
namespace MySample\MyWallet;

use MySample\MyWallet\Injector\CurrencyApiInjector;
use MySample\MyWallet\Injector\ExchangeApiInjector;

class FakeApiAdapterInjectedObject implements FakeApiAdapterInjectedInterface
{
    use CurrencyApiInjector;
    use ExchangeApiInjector;
}