<?php
namespace MySample\MyWallet\Client;

/**
 * Fixer API クライアントクラス
 *
 * @link http://fixer.io/
 */
class Fixer extends AbstractApiClient implements ApiClientInterface
{
    /**
     * API の URL
     */
    const API_URL = 'https://api.fixer.io/';

    /**
     * 任意の通貨ペアに対する直近の為替レート
     *
     * `$base` と `$symbols` 両方共 `null` が渡された場合、EUR に対する全ての通貨ペアの為替レートが返却されます。
     * `$symbols` には複数のティッカーをカンマ区切りで指定することができます。
     * その場合、指定した全てのティッカーに対する為替レートが返却されます。
     *
     * @param string|null $base
     * @param string|null $symbols
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getLatest(string $base = null, string $symbols = null)
    {
        return $this->request('latest', ['base' => $base, 'symbols' => $symbols]);
    }

    /**
     * API に HTTP リクエストするメソッド
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @throws \Exception
     *
     * @return array
     */
    private function request(string $endpoint, array $params) : array
    {
        $ch = $this->client();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s?%s', self::API_URL, $endpoint, http_build_query($params)));
        $response = $this->parseResponse(curl_exec($ch));

        return $response['body'];
    }
}
