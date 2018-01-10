<?php
namespace MySample\MyWallet\Injector;

use MySample\MyWallet\Client\ApiClientAdapterInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

/**
 * 取引所 API クライアントアダプターのインジェクター
 */
trait ExchangeApiInjector
{
    /**
     * @var ApiClientAdapterInterface
     */
    public $exchangeApi;

    /**
     * リソースに取引所 API クライアントアダプターを注入するメソッド
     *
     * @param ApiClientAdapterInterface $adapter
     *
     * @Inject
     * @Named("exchangeApi")
     */
    public function setExchangeApiClientAdapter(ApiClientAdapterInterface $adapter)
    {
        $this->exchangeApi = $adapter;
    }
}
