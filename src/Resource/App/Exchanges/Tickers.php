<?php
namespace MySample\MyWallet\Resource\App\Exchanges;

use BEAR\Resource\Exception\ParameterException;
use BEAR\Resource\ResourceObject;
use MySample\MyWallet\Injector\ExchangeApiInjector;

/**
 * 通貨の情報を扱うリソースクラス
 *
 * @link https://poloniex.com/support/api/
 */
class Tickers extends ResourceObject
{
    use ExchangeApiInjector;

    /**
     * 通貨の情報を返すメソッド
     *
     * `$provider` で対象となる取引所を指定します。
     * `$base` にはベースとなる通貨のティッカーを指定します。
     * `$target` には為替レートを取得したいペア通貨のティッカーを指定します。
     * `$target` には複数のティッカーをカンマ区切りで指定することができます。
     *
     * 指定可能なティッカー
     * AMP, ARDR, BCH, BCN, BCY, BELA, BLK, BTC, BTCD, BTM, BTS, BURST, CLAM, CVC, DASH, DCR, DGB, DOGE, EMC2,
     * ETC, ETH, EXP, FCT, FLDC, FLO, GAME, GAS, GNO, GNT, GRC, HUC, LBC, LSK, LTC, MAID, NAV, NEOS, NMC, NXC,
     * NXT, OMG, OMNI, PASC, PINK, POT, PPC, RADS, REP, RIC, SBD, SC, STEEM, STORJ, STR, STRAT, SYS, USDT, VIA,
     * VRC, VTC, XBC, XCP, XEM, XMR, XPM, XRP, XVC, ZEC, ZRX
     *
     * @param string      $provider
     * @param string      $base
     * @param string|null $target
     *
     * @throws \Exception
     *
     * @return ResourceObject
     */
    public function onGet(string $provider = 'poloniex', string $base = 'ALL', string $target = null) : ResourceObject
    {
        if (! empty($provider) && ! preg_match('!^[a-z]+\z!iu', $provider)) {
            throw new ParameterException('Argument $provider invalid.');
        }
        if (! empty($base) && ! preg_match('!^[a-z]+\z!iu', $base)) {
            throw new ParameterException('Argument $base invalid.');
        }
        if (! empty($target) && ! preg_match('!^[a-z,]+\z!iu', $target)) {
            throw new ParameterException('Argument $target invalid.');
        }

        $this->body = $this->exchangeApi->provider($provider)->getTickers($base, $target);

        return $this;
    }
}
