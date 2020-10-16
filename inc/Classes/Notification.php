<?php

class Notification implements JsonSerializable {

    private $dateSent;
    private $message;
    private $read;
    private $title;

    /**
     * Get the value of date
     */ 
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * Set the value of date
     */ 
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of read
     */ 
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Set the value of read
     */ 
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    //Serialize the object to JSON.
    public function jsonSerialize(){   

        $notification = new stdClass;
        //Add all the attributes
        $notification->dateSent = $this->dateSent;
        $notification->message = $this->message;
        $notification->read = $this->read;
        $notification->title = $this->title;
        
        return $notification;
    }
}

?>