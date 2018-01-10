<?php
namespace MySample\MyWallet\Client;

/**
 * API クライアントアダプター用インターフェース
 */
interface ApiClientAdapterInterface
{
    public function provider(string $provider);
}
