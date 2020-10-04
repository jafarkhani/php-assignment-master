<?php
//----------------------------
//developer   : Sh.Jafarkhani
//date        : 2020/09/27
//----------------------------

namespace App\Core;

use Sirius\Validation\Validator;
use Sirius\Validation\RuleFactory;
use Psr\Container\ContainerInterface;
use MongoDB\BSON\ObjectId;

class Model
{
    protected $connection;
    protected $validator;
    protected $exeptions = [];
    protected $fillable = [];
    public $data;

    public function __construct(){

        $container = $GLOBALS["app"]->getContainer();
        $this->connection = $container['database'];
        $this->validator = new Validator();
    }

    public function PushException($errorCode, $errordesc){
        $this->exeptions[] = ["errorCode" => $errorCode, "errorDesc" => $errordesc];
    }
    public function PopException(){
        return array_pop($this->exeptions);
    }

    public function IsValid(){
        
        if(!$this->validator->validate($this->data)){
            $this->PushException(StatusCodes::HTTP_UNPROCESSABLE_ENTITY, $this->getValidationMessages());
            return false;
        }
        return true;
    }

    public function getValidationMessages()
    {
        $messages = [];

        foreach ($this->validator->getMessages() as $rule => $message) {
            /** @var \Sirius\Validation\ErrorMessage $item */
            foreach ($message as $item) {
                $messages[$rule] = (string)$item;
            }
        }

        return $messages;
    }

    function find($condition = array()){
        return $this->connection->{ $this->collection}->find($condition);
    }

    function findById($_id){

        if(is_string($_id))
            $_id = new ObjectId($_id);
        return $this->connection->{ $this->collection}->findOne(array("_id" => $_id));
    }

    public function FillData($input){
        
        foreach($input as $key => $value){
            if(array_search($key, $this->fillable) !== false)
                $this->data[ $key ] = $value;
        }    
    }

    function save(){

        if(!empty($this->data["_id"]))
        {
            $id = new ObjectId($this->data["_id"]);
            $result = $this->connection->{ $this->collection}->updateOne(["_id" => $id], ['$set' => $this->data]);
            
        }
        else{
            $result = $this->connection->{ $this->collection}->insertOne($this->data);
            if($result->getInsertedCount() == 0)
                return null;
            $id = $result->getInsertedId();
        }

        return $this->findById($id);
    }

    function remove(){

        $result = $this->connection->{ $this->collection}->deleteOne(["_id" => $this->data["_id"]]);
        if($result->getDeletedCount() == 0){
            $this->PushException(HTTP_CONFLICT, "");
            return false;
        }
        return true;
    }
}