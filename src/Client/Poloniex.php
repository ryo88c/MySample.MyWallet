<?php
namespace MySample\MyWallet\Client;

/**
 * Poloniex API クライアントクラス
 *
 * @link https://poloniex.com/support/api/
 * @link https://poloniex.com/apiKeys
 */
class Poloniex extends AbstractApiClient implements ApiClientInterface
{
    /**
     * Trading API の URL
     */
    const TRADING_API_URL = 'https://poloniex.com/tradingApi';

    /**
     * Public API の URL
     */
    const PUBLIC_API_URL = 'https://poloniex.com/public';

    /**
     * @var string 環境変数 POLONIEX_API_KEY の値
     */
    private $apiKey;

    /**
     * @var string 環境変数 POLONIEX_API_SECRET の値
     */
    private $apiSecret;

    /**
     * コンストラクタ
     *
     * @param string $apiKey    環境変数 `POLONIEX_API_KEY` に指定された値
     * @param string $apiSecret 環境変数 `POLONIEX_API_SECRET` に指定された値
     */
    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * 通貨ペアの情報を返すメソッド
     *
     * `$base` と `$target` 両方共 `null` を指定した場合、全ての通貨ペアの情報が返却されます。
     * `$base` だけが指定されている場合、その通貨に対する全ての通貨ペアの情報が返却されます。
     * `$target` には複数のティッカーをカンマ区切りで指定することができます。
     * その場合、指定した全てのティッカーに対する通貨ペア情報が返却されます。
     *
     * @param string      $base
     * @param string|null $target
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getTickers(string $base = 'ALL', string $target = null) : array
    {
        $base = strtoupper($base);
        $target = empty($target) ? [] : explode(',', strtoupper($target));
        $pairs = $this->request('public', ['command' => 'returnTicker']);
        if ($base === 'ALL') {
            return $pairs;
        }

        $tickers = [];
        $regexp = empty($target) ? sprintf('!^%s_!', $base) : sprintf('!^%s_(%s)\z!', $base, implode('|', $target));
        foreach ($pairs as $key => $var) {
            if (preg_match($regexp, $key)) {
                $var['last'] = (float) $var['last'];
                $tickers[$key] = $var;
            }
        }

        if (empty($tickers)) {
            throw new \Exception('Currency pair not found.');
        }

        return $tickers;
    }

    /**
     * 保有残高を返すメソッド
     *
     * `$currencies` に `null` を指定した場合、保有している全ての通貨の残高が返却されます。
     * `$currencies` には複数のティッカーをカンマ区切りで指定することができます。
     * その場合、指定した全てのティッカーに対する残高が返却されます。
     * 返却される残高の数値はその通貨における保有量です。
     *
     * @param string $currencies
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getBalances(string $currencies = 'ALL') : array
    {
        $balances = [];
        $allBalances = $this->request('trading', ['command' => 'returnBalances']);
        $currencies = explode(',', $currencies);
        foreach ($allBalances as $ticker => $balance) {
            if (empty((float) $balance)) {
                continue;
            }
            if ($currencies[0] === 'ALL' || in_array($ticker, $currencies, true)) {
                $balances[$ticker] = (float) $balance;
            }
        }

        return $balances;
    }

    /**
     * API に HTTP リクエストするメソッド
     *
     * リクエストする先に応じてベースの URL が異なるため、`$endpoint` でどちらを呼び出すか制御しています。
     * このメソッドで直接 Web API を呼び出しているわけではありません。
     *
     * @param string $endpoint
     * @param array  $args
     *
     * @throws \Exception
     *
     * @return array
     */
    private function request(string $endpoint, array $args) : array
    {
        if ($endpoint === 'trading') {
            return $this->request2trading($args);
        }

        return $this->request2public($args);
    }

    /**
     * Public API に HTTP リクエストするメソッド
     *
     * @param array $params
     *
     * @throws \Exception
     *
     * @return array
     */
    private function request2public(array $params) : array
    {
        $ch = $this->client();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s?%s', self::PUBLIC_API_URL, http_build_query($params)));
        $response = $this->parseResponse(curl_exec($ch));

        return $response['body'];
    }

    /**
     * Trading API に HTTP リクエストするメソッド
     *
     * @param array $params
     *
     * @throws \Exception
     *
     * @return array
     */
    private function request2trading(array $params) : array
    {
        $params['nonce'] = (int) microtime(true) * 1000;
        $postBody = http_build_query($params);
        $sign = hash_hmac('sha512', $postBody, $this->apiSecret);
        $headers = ['Key: ' . $this->apiKey, 'Sign: ' . $sign];

        $ch = $this->client();
        curl_setopt($ch, CURLOPT_URL, self::TRADING_API_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = $this->parseResponse(curl_exec($ch));

        return $response['body'];
    }
}
