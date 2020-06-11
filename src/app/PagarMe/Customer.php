<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

class Customer {

    protected $external_id;
    protected $name;
    protected $type = 'individual';
    protected $country = 'br';
    protected $documents;
    protected $phone_numbers;
    protected $email;

    public function __construct(string $name, string $cpf, string $phone, string $email){

        $this->setName($name);
        $this->setDocument('cpf', $cpf);
        $this->setPhone('55', $phone);
        $this->setEmail($email);
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
                'number' => $number
            ]
        ];
    }

    public function setPhone($country_code, $number){
        
        $number = preg_replace('/\D/', '', $number);
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

        return !$this->contains('external_id', $this->external_id)
        ? Api::client()->customers()->create($this->toArray())
        : 'Customer with external_id already exists!';
    }

    public function toArray(){return get_object_vars($this);}

    public function collect(){return collect(Api::client()->customers()->getList());}
    public function where($key, $value){return $this->collect()->where($key, $value);}
    public function contains($key, $value){return $this->collect()->contains($key, $value);}
}