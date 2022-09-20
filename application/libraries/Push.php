<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push {
    
    private $title;
    private $message;
    private $image;
    private $data;
    private $is_background;
 
    function __construct() {
         
    }
 
    public function setTitle($title) {
        $this->title = $title;
    }
 
    public function setMessage($message) {
        $this->message = $message;
    }
 
    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    }
 
    public function setPayload($data) {
        $this->data = $data;
    }
 
    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }
 
    public function getPush() {
        $res = array();
        $res['title'] = $this->title;
        $res['body'] = $this->message;
        $res['icon'] = $this->image;
        return $res;
    }
 
}