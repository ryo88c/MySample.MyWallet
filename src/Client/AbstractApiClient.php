<?php
namespace MySample\MyWallet\Client;

/**
 * API クライアントクラス用抽象クラス
 *
 * API のクライアントクラスのアプリケーションロジックが実装されます。
 */
abstract class AbstractApiClient
{
    /**
     * cUrl のリソースを返すメソッド
     *
     * @return resource
     */
    protected function client()
    {
        static $ch;
        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
        }

        return $ch;
    }

    /**
     * レスポンスをパースするメソッド
     *
     * レスポンスをステータスコード、レスポンスヘッダー、レスポンスボディに分割して返します。
     * レスポンスボディは JSON としてパースされます。
     *
     * @param string $bareResponse
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function parseResponse(string $bareResponse) : array
    {
        $ch = $this->client();
        if ($bareResponse === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = json_decode(trim(substr($bareResponse, $headerSize)), true);
        $headers = [];
        $statusCode = null;
        $rawHeaders = explode("\r\n", trim(substr($bareResponse, 0, $headerSize)));
        foreach ($rawHeaders as $i => $header) {
            if ($i === 0) {
                $statusCode = (int) substr($header, 9, 3);
                continue;
            }
            $header = explode(': ', $header);
            $headers[$header[0]] = $header[1];
        }

        if (is_array($body) && array_key_exists('error', $body)) {
            throw new \Exception('API error: ' . $body['error']);
        } elseif ($statusCode !== 200) {
            throw new \Exception('API error: Status code: ' . $statusCode);
        }

        return compact('statusCode', 'headers', 'body');
    }
}
