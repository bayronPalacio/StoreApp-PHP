<?php

class Customer implements JsonSerializable {

    private $address;
    private $card;
    private $email;
    private $first_name;
    private $id;
    private $last_name;
    private $password;
    private $phone;

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of card
     */ 
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set the value of card
     */ 
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of first_name
     */ 
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * Set the value of first_name
     */ 
    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of last_name
     */ 
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     */ 
    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }


    //Serialize the object to JSON.
    public function jsonSerialize(){   

        $customer = new stdClass;
        //Add all the attributes
        $customer->address = $this->address;
        $customer->email = $this->email;
        $customer->first_name = $this->first_name;
        $customer->id = $this->id;
        $customer->last_name = $this->last_name;
        $customer->password= $this->password;
        $customer->phone = $this->phone;
        $customer->card = $this->card;
        
        return $customer;
    }
}

?>