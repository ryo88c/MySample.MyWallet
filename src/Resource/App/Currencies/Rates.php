<?php
namespace MySample\MyWallet\Resource\App\Currencies;

use BEAR\Resource\Exception\ParameterException;
use BEAR\Resource\ResourceObject;
use MySample\MyWallet\Injector\CurrencyApiInjector;

/**
 * 通貨の為替レートを扱うリソースクラス
 *
 * @link http://fixer.io/
 */
class Rates extends ResourceObject
{
    use CurrencyApiInjector;

    /**
     * 任意の通貨ペアに対する為替レートを返すメソッド
     *
     * `$base` にはベースとなる通貨のティッカーを指定します。
     * `$symbols` には為替レートを取得したいペア通貨のティッカーを指定します。
     * `$symbols` には複数のティッカーをカンマ区切りで指定することができます。
     *
     * 指定可能なティッカー
     * EUR, AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, GBP, HKD, HRK, HUF, IDR, ILS, INR,
     * JPY, KRW, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, USD, ZAR
     *
     * @param string $provider
     * @param string $base
     * @param string $symbols
     *
     * @throws \Exception
     *
     * @return ResourceObject
     */
    public function onGet(string $provider = 'fixer', string $base = 'USD', string $symbols = 'JPY') : ResourceObject
    {
        if (! empty($provider) && ! preg_match('!^[a-z]+\z!iu', $provider)) {
            throw new ParameterException('Argument $provider invalid.');
        }
        if (! empty($base) && ! preg_match('!^[a-z]+\z!iu', $base)) {
            throw new ParameterException('Argument $base invalid.');
        }
        if (! empty($symbols) && ! preg_match('!^[a-z,]+\z!iu', $symbols)) {
            throw new ParameterException('Argument $symbols invalid.');
        }

        $this->body = $this->currencyApi->provider($provider)->getLatest($base, $symbols);

        return $this;
    }
}
