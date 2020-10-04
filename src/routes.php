<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\AddressController;

$app->group('/api/v1', function () {
    
    $this->get('/address', 'App\Controllers\AddressController:list');
    $this->get('/address/{id}', 'App\Controllers\AddressController:list');
    $this->post('/address', 'App\Controllers\AddressController:save');
    $this->patch('/address/{id}', 'App\Controllers\AddressController:save');
    $this->delete('/address/{id}', 'App\Controllers\AddressController:remove');

});

