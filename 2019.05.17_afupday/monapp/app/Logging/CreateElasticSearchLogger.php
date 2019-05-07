<?php
namespace App\Logging;

use Monolog\Handler\ElasticSearchHandler;
use Elastica\Client;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class CreateElasticSearchLogger {

    public function __invoke(array $config) {

        $client = new Client($config['config']);

        $options = array(
            'index' => $config['config']['index'],
            'type'  => 'log',
        );
        $handler = new ElasticsearchHandler($client, $options);

        $logger = new Logger("elasticsearch", [$handler]);

        return $logger;
    }
}