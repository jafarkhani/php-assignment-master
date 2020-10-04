<?php
//----------------------------
//developer   : Sh.Jafarkhani
//date        : 2020/09/27
//----------------------------

namespace App\Core;

use App\Core\Validation;
use Psr\Container\ContainerInterface;

class Controller
{
    protected $container;
    protected $settings;
    protected $router;
    protected $database;

    /**
     * @var Monolog\Logger
     */
    protected $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->settings  = $this->container['settings'];
        $this->router    = $this->container['router'];
        $this->database  = $this->container['database'];
        $this->logger    = $this->container['logger'];

    }

    public function ObjectSerializer($object){
        $result = [];
        $arr = get_object_vars($object); 
        foreach($arr as $key => $value)
        {
            if(is_array($value))
            {
                $result[$key] = $this->ObjectSerializer($value);
                continue;
            }
            if($key == "_id")
            {
                $result["id"] = $value->__toString();
                continue;
            }

            $result[ $key ] = $value;
        }
        return $result;
    }
    
    public function ArraySerializer(Array $data){

        $result = array();
        foreach($data as $row)
        {
            $result[] = $this->ObjectSerializer($row);
        }        
        
        return $result;
    }
}