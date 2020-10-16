<?php

class Order implements JsonSerializable {

    private $customer;
    private $customer_message;
    private $date_created;
    private $date_shipped;
    private $id;
    private $payment_method;
    private $productsOrdered;
    private $shipping_cost;
    private $status;
    private $total;
    private $total_tax;

    /**
     * Get the value of customer
     */ 
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @return  self
     */ 
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of customer_message
     */ 
    public function getCustomer_message()
    {
        return $this->customer_message;
    }

    /**
     * Set the value of customer_message
     *
     * @return  self
     */ 
    public function setCustomer_message($customer_message)
    {
        $this->customer_message = $customer_message;

        return $this;
    }

    /**
     * Get the value of date_created
     */ 
    public function getDate_created()
    {
        return $this->date_created;
    }

    /**
     * Set the value of date_created
     *
     * @return  self
     */ 
    public function setDate_created($date_created)
    {
        $this->date_created = $date_created;

        return $this;
    }

    /**
     * Get the value of date_shipped
     */ 
    public function getDate_shipped()
    {
        return $this->date_shipped;
    }

    /**
     * Set the value of date_shipped
     *
     * @return  self
     */ 
    public function setDate_shipped($date_shipped)
    {
        $this->date_shipped = $date_shipped;

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
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of payment_method
     */ 
    public function getPayment_method()
    {
        return $this->payment_method;
    }

    /**
     * Set the value of payment_method
     *
     * @return  self
     */ 
    public function setPayment_method($payment_method)
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    /**
     * Get the value of productsOrdered
     */ 
    public function getProductsOrdered()
    {
        return $this->productsOrdered;
    }

    /**
     * Set the value of productsOrdered
     *
     * @return  self
     */ 
    public function setProductsOrdered($productsOrdered)
    {
        $this->productsOrdered = $productsOrdered;

        return $this;
    }

    /**
     * Get the value of shipping_cost
     */ 
    public function getShipping_cost()
    {
        return $this->shipping_cost;
    }

    /**
     * Set the value of shipping_cost
     *
     * @return  self
     */ 
    public function setShipping_cost($shipping_cost)
    {
        $this->shipping_cost = $shipping_cost;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of total
     */ 
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */ 
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of total_tax
     */ 
    public function getTotal_tax()
    {
        return $this->total_tax;
    }

    /**
     * Set the value of total_tax
     *
     * @return  self
     */ 
    public function setTotal_tax($total_tax)
    {
        $this->total_tax = $total_tax;

        return $this;
    }

    //Serialize the object to JSON.
    public function jsonSerialize(){   
        $newOrder = new stdClass;
        //Add all the attributes
        $newOrder->customer = $this->customer;
        $newOrder->customer_message = $this->customer_message;
        $newOrder->date_created = $this->date_created;
        $newOrder->date_shipped = $this->date_shipped;
        $newOrder->id = $this->id;
        $newOrder->payment_method = $this->payment_method;
        $newOrder->productsOrdered = $this->productsOrdered;
        $newOrder->shipping_cost = $this->shipping_cost;
        $newOrder->status = $this->status;
        $newOrder->total = $this->total;
        $newOrder->total_tax = $this->total_tax;
        return $newOrder;
    }
}

?>