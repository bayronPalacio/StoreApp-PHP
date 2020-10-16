<?php

class Product implements JsonSerializable {

    private $barcode;
    private $brand_id;
    private $categories;
    private $cost_price;
    private $description;
    private $id; 
    private $inventory_level; 
    private $inventory_warning_level;
    private $name; 
    private $review_message; 
    private $price; 
    private $reviews_count; 
    private $reviews_rating_sum; 
    private $url_image;
    private $availability; 

    /**
     * Get the value of barcode
     */ 
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set the value of barcode
     */ 
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * Get the value of brand_id
     */ 
    public function getBrand_id()
    {
        return $this->brand_id;
    }

    /**
     * Set the value of brand_id
     */ 
    public function setBrand_id($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Get the value of categories
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     */ 
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Get the value of cost_price
     */ 
    public function getCost_price()
    {
        return $this->cost_price;
    }

    /**
     * Set the value of cost_price
     */ 
    public function setCost_price($cost_price)
    {
        $this->cost_price = $cost_price;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */ 
    public function setDescription($description)
    {
        $this->description = $description;
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
    }

    /**
     * Get the value of inventory_level
     */ 
    public function getInventory_level()
    {
        return $this->inventory_level;
    }

    /**
     * Set the value of inventory_level
     */ 
    public function setInventory_level($inventory_level)
    {
        $this->inventory_level = $inventory_level;
    }

    /**
     * Get the value of inventory_warning_level
     */ 
    public function getInventory_warning_level()
    {
        return $this->inventory_warning_level;
    }

    /**
     * Set the value of inventory_warning_level
     */ 
    public function setInventory_warning_level($inventory_warning_level)
    {
        $this->inventory_warning_level = $inventory_warning_level;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */ 
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of preorder_message
     */ 
    public function getReview_message()
    {
        return $this->review_message;
    }

    /**
     * Set the value of preorder_message
     */ 
    public function setReview_message($review_message)
    {
        $this->review_message = $review_message;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */ 
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get the value of reviews_count
     */ 
    public function getReviews_count()
    {
        return $this->reviews_count;
    }

    /**
     * Set the value of reviews_count
     */ 
    public function setReviews_count($reviews_count)
    {
        $this->reviews_count = $reviews_count;
    }

    /**
     * Get the value of reviews_rating_sum
     */ 
    public function getReviews_rating_sum()
    {
        return $this->reviews_rating_sum;
    }

    /**
     * Set the value of reviews_rating_sum
     */ 
    public function setReviews_rating_sum($reviews_rating_sum)
    {
        $this->reviews_rating_sum = $reviews_rating_sum;
    }

    /**
     * Get the value of url_image
     */ 
    public function getUrl_image()
    {
        return $this->url_image;
    }

    /**
     * Set the value of url_image
     */ 
    public function setUrl_image($url_image)
    {
        $this->url_image = $url_image;
    }
    
    /**
     * Get the value of availability
     */ 
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set the value of availability
     *
     * @return  self
     */ 
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    //Serialize the object to JSON.
    public function jsonSerialize(){   
        $product = new stdClass;
        //Add all the attributes
        $product->barcode = $this->barcode;
        $product->brand_id = $this->brand_id;
        $product->categories = $this->categories;
        $product->cost_price = $this->cost_price;
        $product->description = $this->description;
        $product->id = $this->id;
        $product->inventory_level = $this->inventory_level;
        $product->inventory_warning_level = $this->inventory_warning_level;
        $product->name = $this->name;
        $product->review_message = $this->review_message;
        $product->price = $this->price;
        $product->reviews_count = $this->reviews_count;
        $product->reviews_rating_sum = $this->reviews_rating_sum;
        $product->url_image = $this->url_image;
        $product->availability = $this->availability;
        return $product;
    }
}    
?>