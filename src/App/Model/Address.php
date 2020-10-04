<?php
//----------------------------
//developer   : Sh.Jafarkhani
//date        : 2020/09/27
//----------------------------

namespace App\Model;

use App\Core\Model;
use App\Core\StatusCodes;
use League\ISO3166\ISO3166;
use Sirius\Validation\Validator;
use MongoDB\BSON\ObjectId;

class Address extends Model{

    protected $collection = 'addresses';

    protected $fillable = [
        'country',
        'city',
        'street',
        'postalcode',
        'number',
        'numberAddition',
        'createdAt',
        'updatedAt',
        'status',
        'name',
        'email'
    ];

    public $data = [];

    const STATUS_NOTATHOME = "not at home";
    const STATUS_NOTINTERESTED = "not interested";
    const STATUS_INTERESTED = "interested";

    public function __construct($id = ""){

        parent::__construct();
        
        $this->validator->add(
            [
                'country:Country'       => 'required | Alpha | length(2,2)(Country code has to be 2 charecters)',
                'city:City'             => 'required',
                'street:Street'         => 'required',
                'postalcode:postal code' => 'required | regex(pattern=/^[0-9]{5}$/)(Postal code has to be 5 digits) ',
                'number:Number'         => 'required | Number | GreaterThan(0)',
                'name'                  => 'Alpha',
                'email'                 => 'email'
            ]
        );
        
        $this->validator->add('status', function($value){
            if($value != Address::STATUS_NOTATHOME && $value != Address::STATUS_INTERESTED && $value != Address::STATUS_NOTINTERESTED)
                return false;
            return true;
            
        }, "", "status could be 'not at home' or 'not interested' or 'interested'");

        $this->validator->add('country', function($value){
            $countries = (new ISO3166)->all();
            foreach($countries as $country)
                if($country["alpha2"] == $value)
                    return true;
            return false;
            
        }, "", "Country code has to be a valid Alpha-2 code from ISO 3166-1.");

        if($id != "")
        {
            $this->data = $this->findById($id);
            if(empty($this->data))
            {
                $this->PushException(StatusCodes::HTTP_NOT_FOUND, "No address found by id=" . $id);
                return false;
            }
        }
        
    }

    function IsValid(){
        if(empty($this->data["_id"])){
            $newAllowed = array("country","city","street","postalcode","number","numberAddition");
            foreach($this->data as $key => $value){
                if(array_search($key, $newAllowed) === false){
                    $this->PushException(StatusCodes::HTTP_UNPROCESSABLE_ENTITY, 
                        StatusCodes::getMessageForCode(StatusCodes::HTTP_UNPROCESSABLE_ENTITY));
                    return false;
                }
            }                    
        }
        return parent::IsValid();
    }

    function save(){

        if(empty($this->data["_id"]))
        {
            $this->data["createdAt"] = date("Y-m-d\TH:i:s\Z");
            $this->data["updatedAt"] = date("Y-m-d\TH:i:s\Z");
            $this->data["status"] = null;
            $this->data["name"] = null;
            $this->data["email"] = null;
        }
        else{
            $this->data["updatedAt"] = date("Y-m-d\TH:i:s\Z");
        }
        
        return parent::save();
    }

    
}