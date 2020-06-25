<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use MatheusFS\Laravel\Checkout\Entities\Person;
use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Traits\Requestable;

class Customer {

    use Requestable;

    public $external_id;
    public $name;
    public $type = 'individual';
    public $country = 'br';
    public $documents;
    public $phone_numbers;
    public $email;

    public function __construct(Person $person){

        $this->setName("$person->firstname $person->lastname");
        $this->setDocument('cpf', $person->document);
        $this->setPhone('55', $person->phone);
        $this->setEmail($person->email);
        $this->setExternalId();
    }

    public function setName(string $name){return $this->name = $name;}
    public function getName(){return $this->name;}

    public function setEmail(string $email){return $this->email = $email;}
    public function getEmail(){return $this->email;}

    public function setDocument($type, $number){
        
        return $this->documents = [
            [
                'type' => $type,
                'number' => "$number"
            ]
        ];
    }

    public function setPhone($country_code, $number){
        
        return $this->phone_numbers = [ "+$country_code$number" ];
    }

    public function setExternalId(){

        $first_document_number = $this->documents[0]['number'];

        if(!empty($this->email) && !empty($first_document_number)){

            $this->external_id = "{$this->email}_{$first_document_number}";
        }else{

            throw new \Exception('Cannot set external_id w/o e-mail and document number.');
        }
    }

    public function save(){

        try{

            return !$this->contains('external_id', $this->external_id)
            ? Api::client()->customers()->create($this->payload())
            : 'Customer with external_id already exists!';
        }catch(\PagarMe\Exceptions\PagarMeException $th){

            report($th);
            $message = preg_filter('/^.*MESSAGE: (.*)/', '$1', $th->getMessage());
            throw new FormExeption($message);
        }
    }

    public function collect(){return collect(Api::client()->customers()->getList());}
    public function where($key, $value){return $this->collect()->where($key, $value);}
    public function contains($key, $value){return $this->collect()->contains($key, $value);}
}