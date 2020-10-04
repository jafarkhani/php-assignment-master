<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Psr\Container\ContainerInterface;

$container = $app->getContainer();

$container['logger'] = function(ContainerInterface $container) {

    $config = $container->get('settings')['logger'];

    $logger = new Monolog\Logger($config['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($config['path'], $config['level']));

    return $logger;
};

$container['database'] = function(ContainerInterface $container) {

    $config = $container->get('settings')['mongo'];

    $uri = $server = sprintf('mongodb+srv://%s:%s@%s/%s',
        $config['user'],
        $config['password'],
        $config['host'],
        $config['database']
    );
    $client = new MongoDB\Client($uri);

    return $client->selectDatabase($config['database']);

};

$container['errorHandler'] = function (ContainerInterface $container) {
    return function ($request, $response, $exception) use ($container) {
       
        $container['logger']->critical($exception->getMessage() . " file:" . 
            $exception->getFile() . " line " . $exception->getLine());

        $response = $container['response'];
        return $response->withJson(['errors' => 'Something went wrong!'], 500);
    };
};