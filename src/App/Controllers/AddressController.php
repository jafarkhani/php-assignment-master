<?php
//----------------------------
//developer   : Sh.Jafarkhani
//date        : 2020/09/27
//----------------------------

namespace App\Controllers;

use App\Model\Address;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Controller;
use App\Core\StatusCodes;
use Monolog\Logger;

class AddressController extends Controller{

    public function list(Request $request, Response $response, array $args){
        
        $obj = new Address();

        if(isset($args["id"]))
        {
            $obj = new Address($args["id"]);
            if(!$obj->data){
                $error = $obj->PopException();
                $this->logger->warning("Get all address by invalid id");
                return $response->withStatus($error["errorCode"])->withJson($error["errorDesc"]);
            }

            $this->logger->info("Get address by id = " . $args["id"]);
            return $response->withJson($this->ObjectSerializer($obj->data));
        }

        $result = $obj->find()->toArray();
        $this->logger->info("Get all addresses");
        return $response->withJson($this->ArraySerializer($result));

    }

    public function save(Request $request, Response $response, array $args){

        $input = $request->getParsedBody();
        if(!$input){
            $this->logger->notice("request to save address with invalid data");
            return $response->withStatus(StatusCodes::HTTP_UNPROCESSABLE_ENTITY
                )->write(StatusCodes::getMessageForCode(StatusCodes::HTTP_UNPROCESSABLE_ENTITY));
        }
        $id = isset($args["id"]) ? $args["id"] : "";
        if($request->isPatch() && empty($id)){
            $this->logger->warning("request to save address with invalid data");
            return $response->withStatus(StatusCodes::HTTP_UNPROCESSABLE_ENTITY)->write("id needed");
        }
        
        $obj = new Address($id);
        if(!empty($id) && !$obj->data)
        {
            $error = $obj->PopException();
            $this->logger->warning("request to save address with invalid id");
            return $response->withStatus($error["errorCode"])->withJson($error["errorDesc"]);
        }
        if(!empty($id) && $obj->data["status"] != null && $obj->data["status"] != Address::STATUS_NOTATHOME)
        {
            $this->logger->warning("request to save address with invalid status");
            return $response->withStatus(StatusCodes::HTTP_FORBIDDEN
            )->write("Only addresses with status equal to 'null' or 'not at home' can be edited.");
        }
        
        $obj->FillData($input);

        if (!$obj->IsValid()) {
            $this->logger->warning("request to save address with invalid data");
            $error = $obj->PopException();
            return $response->withStatus($error["errorCode"])->withJson($error["errorDesc"]);
        }

        $address = $obj->save();
        if(empty($address))
        {
            $this->logger->notice("error in save address");
            return $response->withStatus(StatusCodes::HTTP_INTERNAL_SERVER_ERROR
                )->write(StatusCodes::getMessageForCode(StatusCodes::HTTP_INTERNAL_SERVER_ERROR));
        }

        if($request->isPost()){
            $this->logger->info("request to add a new address");            
            return $response->withStatus(StatusCodes::HTTP_CREATED
                )->withHeader('Location',  $request->getHeader('Location')
                )->withJson($this->ObjectSerializer($address));;
        }
        else{
            $this->logger->info("request to edit an address with id = " . $args["id"]);
            return $response->withStatus(StatusCodes::HTTP_OK
                )->withJson($this->ObjectSerializer($address));;
        }
        
    }

    public function remove(Request $request, Response $response, array $args){

        $id = isset($args["id"]) ? $args["id"] : "";
        if(empty($id)){
            $this->logger->warning("request to delete address by invalid id");
            return $response->withStatus(StatusCodes::HTTP_UNPROCESSABLE_ENTITY
                )->write(StatusCodes::getMessageForCode(StatusCodes::HTTP_UNPROCESSABLE_ENTITY));
        }

        $obj = new Address($id);
        if(!$obj->data)
        {
            $this->logger->warning("request to delete address by invalid id");
            $error = $obj->PopException();
            return $response->withStatus($error["errorCode"])->withJson($error["errorDesc"]);
        }

        $result = $obj->remove();
        if(!$result){
            $this->logger->notice("error in deleting address with id = " . $id);
            $error = $obj->PopException();
            return $response->withStatus($error["errorCode"])->withJson($error["errorDesc"]);
        }

        $this->logger->info("delete address with id = " . $id);
        return $response->withStatus(StatusCodes::HTTP_NO_CONTENT)->withJson("");
    }
}

