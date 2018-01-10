<?php
namespace MySample\MyWallet\Injector;

use MySample\MyWallet\Client\ApiClientAdapterInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

/**
 * 通貨 API クライアントアダプターのインジェクター
 */
trait CurrencyApiInjector
{
    /**
     * @var ApiClientAdapterInterface
     */
    public $currencyApi;

    /**
     * リソースに通貨 API クライアントアダプターを注入するメソッド
     *
     * @param ApiClientAdapterInterface $adapter
     *
     * @Inject
     * @Named("currencyApi")
     */
    public function setCurrencyApiClientAdapter(ApiClientAdapterInterface $adapter)
    {
        $this->currencyApi = $adapter;
    }
}
