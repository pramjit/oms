<?php

class ControllerApitest extends Controller {
	public function index() {
         $string= array();
         $string = "    Hello   World!   ";
    
   
    //$length = count($string);
	echo $string.length;
    for($x = 0; $x < $string.length; $x++){
		echo $string[$x];
    } 
    }
    
    //
    
}