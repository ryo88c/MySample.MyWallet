# MySample.MyWallet

　SoftwareDesign2018年3月号特集1に記載された内容は、情報の提供のみを目的
としています。したがって、本書を用いた運用は、必ずお客様自身の責任と判断
によって行ってください。これらの情報の運用の結果について、技術評論社およ
び著者はいかなる責任も負いません。

　本書記載の情報は、2018年1月現在のものを掲載していますので、ご利用時に
は、変更されている場合もあります。
　また、ソフトウェアに関する記述は、特に断わりのないかぎり、2018年1月現
在での最新バージョンをもとにしています。ソフトウェアはバージョンアップさ
れる場合があり、本書での説明とは機能内容や画面図などが異なってしまうこと
もあり得ます。本書ご購入の前に、必ずバージョン番号をご確認ください。

　このソースコードは必ずSoftwareDesign2018年3月号特集1をお読みになった上
で、ご利用ください。
　そしてこの利用は、必ずお客様自身の責任と判断によって行ってください。使
用した結果生じたいかなる直接的・間接的損害も、技術評論社、著者、すべての
個人と企業は、一切その責任を負いません。

　以上の注意事項をご承諾いただいた上でご利用願います。これらの注意事項を
お読みいただかずに、お問い合わせいただいても、技術評論社および著者は対処
しかねます。あらかじめ、ご承知おきください。
　本GitHubのリポジトリに存在するコードの著作権はすべて作者のものです。

## インストール方法

本リポジトリをクローンしたら、以下のコマンドを実行してください。

    composer install

## 使い方

本アプリケーションの使い方を説明します。

### サーバーの起動

以下のコマンドを実行すると PHP のビルトインサーバ上でアプリケーションが起動します。

    COMPOSER_PROCESS_TIMEOUT=0 composer serve

### HTTP で API を呼び出す

    curl 127.0.0.1:8080/balances

### コンソールから API を呼び出す

    composer web get /balances
    composer api get /currencies/rates
    composer api get /exchanges/balances
    composer api get /exchanges/tickers

### ソースコードの検証

    composer test       // phpunit
    composer coverage   // test coverate
    composer cs         // lint
    composer cs-fix     // lint fix
    vendor/bin/phptest  // test + cs
    vendor/bin/phpbuild // phptest + doc + qa
