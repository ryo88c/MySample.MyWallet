<?php
namespace MySample\MyWallet\Client;

use Ray\Di\Di\Named;

/**
 * 取引所 API クライアントアダプタークラス
 */
class ExchangeApiClientAdapter extends AbstractApiClientAdapter
{
    /**
     * 規定の API プロバイダー
     */
    const DEFAULT_PROVIDER = 'poloniex';

    /**
     * @var array 選択可能な API プロバイダーのリスト
     */
    protected $selectableProviders = ['poloniex'];

    /**
     * コンストラクタ
     *
     * @param array $config
     *
     * @throws \Exception
     *
     * @Named("exchange_config")
     */
    public function __construct(array $config)
    {
        $this->provider(self::DEFAULT_PROVIDER);
        $poloniex = new Poloniex($config['poloniex']['api_key'], $config['poloniex']['api_secret']);
        $this->registerClient('poloniex', $poloniex);
    }
}
