<?php
namespace MySample\MyWallet\Resource\Page;

use BEAR\Resource\Exception\ParameterException;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * 保有残高を扱うリソースクラス
 */
class Balances extends ResourceObject
{
    use ResourceInject;

    /**
     * 任意の取引所に保有している暗号通貨の残高を日本円で返すメソッド
     *
     * 各通貨の残高はティッカーがキーになっています。
     * `AMOUNT` は保有している暗号通貨の合計額です。
     *
     * @param string $currency
     *
     * @throws \Exception
     *
     * @return ResourceObject
     */
    public function onGet($currency = 'JPY') : ResourceObject
    {
        if (! empty($currency) && ! preg_match('!^[a-z]+\z!iu', $currency)) {
            throw new ParameterException('Argument $currency invalid.');
        }

        $balances = $this->resource->uri('app://self/exchanges/balances')->eager->request()->body;

        $tickers = $this->resource->uri('app://self/exchanges/tickers')->withQuery([
            'base' => 'USDT',
            'target' => implode(',', array_keys($balances))
        ])->eager->request()->body;

        $rates = $this->resource->uri('app://self/currencies/rates')->withQuery([
            'base' => 'USD',
            'symbols' => $currency
        ])->eager->request()->body['rates'];
        if (! array_key_exists($currency, $rates)) {
            throw new ParameterException(sprintf('Undefined currency: %s', $currency));
        }

        $totalAmount = 0;
        foreach ($tickers as $pair => $var) {
            $totalAmount += $balances[substr($pair, 5)] *= $var['last'] * $rates[$currency];
        }
        $balances['AMOUNT'] = $totalAmount;
        $this->body = $balances;

        return $this;
    }
}
