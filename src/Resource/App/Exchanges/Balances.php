<?php
namespace MySample\MyWallet\Resource\App\Exchanges;

use BEAR\Resource\Exception\ParameterException;
use BEAR\Resource\ResourceObject;
use MySample\MyWallet\Injector\ExchangeApiInjector;

/**
 * 保有残高を扱うリソースクラス
 *
 * @link https://poloniex.com/support/api/
 */
class Balances extends ResourceObject
{
    use ExchangeApiInjector;

    /**
     * 保有残高を返すメソッド
     *
     * `$provider` で対象となる取引所を指定します。
     * 任意の通貨の残高のみ取得したい場合は `$currencies` にティッカーを指定します。
     * `$currencies` には複数のティッカーをカンマ区切りで指定することができます。
     * 指定された通貨のうち、保有している通貨の残高を返します。
     *
     * 指定可能なティッカー
     * AMP, ARDR, BCH, BCN, BCY, BELA, BLK, BTC, BTCD, BTM, BTS, BURST, CLAM, CVC, DASH, DCR, DGB, DOGE, EMC2,
     * ETC, ETH, EXP, FCT, FLDC, FLO, GAME, GAS, GNO, GNT, GRC, HUC, LBC, LSK, LTC, MAID, NAV, NEOS, NMC, NXC,
     * NXT, OMG, OMNI, PASC, PINK, POT, PPC, RADS, REP, RIC, SBD, SC, STEEM, STORJ, STR, STRAT, SYS, USDT, VIA,
     * VRC, VTC, XBC, XCP, XEM, XMR, XPM, XRP, XVC, ZEC, ZRX
     *
     * @param string $provider
     * @param string $currencies
     *
     * @throws \Exception
     *
     * @return ResourceObject
     */
    public function onGet(string $provider = 'poloniex', string $currencies = 'ALL') : ResourceObject
    {
        if (! empty($provider) && ! preg_match('!^[a-z]+\z!iu', $provider)) {
            throw new ParameterException('Argument $provider invalid.');
        }
        if (! empty($currencies) && ! preg_match('!^[a-z,]+\z!iu', $currencies)) {
            throw new ParameterException('Argument $currencies invalid.');
        }

        $this->body = $this->exchangeApi->provider($provider)->getBalances($currencies);

        return $this;
    }
}
