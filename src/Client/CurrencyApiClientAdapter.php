<?php
namespace MySample\MyWallet\Client;

/**
 * 通貨 API クライアントアダプタークラス
 */
class CurrencyApiClientAdapter extends AbstractApiClientAdapter
{
    /**
     * 規定の API プロバイダー
     */
    const DEFAULT_PROVIDER = 'fixer';

    /**
     * @var array 選択可能な API プロバイダーのリスト
     */
    protected $selectableProviders = ['fixer'];

    /**
     * コンストラクタ
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->provider(self::DEFAULT_PROVIDER);
        $this->registerClient('fixer', new Fixer);
    }
}
