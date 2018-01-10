<?php
namespace MySample\MyWallet\Client;

/**
 * API クライアントアダプタークラス用抽象クラス
 *
 * API のクライアントアダプタークラスのアプリケーションロジックが実装されます。
 */
abstract class AbstractApiClientAdapter implements ApiClientAdapterInterface
{
    /**
     * @var array 選択可能な API プロバイダー
     */
    protected $selectableProviders = [];

    /**
     * @var null|string 選択されている API プロバイダー
     */
    private $provider = null;

    /**
     * @var array API クライアントクラスのインスタンスのリスト
     */
    private $clients = [];

    /**
     * 選択している API クライアントの任意のメソッドを呼び出すためのマジックメソッド
     *
     * @param string $name
     * @param array  $arguments
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (! is_callable([$this->clients[$this->provider], $name], true, $callableName)) {
            throw new \Exception(
                sprintf(
                    '%s is unimplemented method in API client for %s.',
                    $callableName,
                    $this->provider
                )
            );
        }

        return call_user_func_array([&$this->clients[$this->provider], $name], $arguments);
    }

    /**
     * 対象となる API プロバイダーを指定するメソッド
     *
     * @param string $provider
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function provider(string $provider)
    {
        if (! in_array($provider, $this->selectableProviders, true)) {
            throw new \Exception(sprintf('%s is undefined provider.', $provider));
        }
        $this->provider = $provider;

        return $this;
    }

    /**
     * API クライアントクラスを登録するメソッド
     *
     * @param string             $provider
     * @param ApiClientInterface $instance
     */
    protected function registerClient(string $provider, ApiClientInterface $instance)
    {
        $this->clients[$provider] = $instance;
    }
}
