<?php
/**
 * EJsonBehavior is used to encode a model with its related models.
 * This is because the CJSON::encode() does not do that, it encodes
 * only model's attributes.
 **/

class EJsonBehavior extends CBehavior {
	
	private $owner;
	private $relations;
	
	public function toJSON() {
		$this->owner = $this->getOwner();
	 
		if (is_subclass_of($this->owner,'CActiveRecord')){
	 
			$attributes     = $this->owner->getAttributes();
			$this->relations= $this->getRelated();
	 
			foreach($this->relations as $key => $value)
			{
				$attributes[$key] = $value; 
			}
	 
			return CJSON::encode($attributes);
		}
	 
		return false;
	}

	private function getRelated() {	
		$related = array();
		
		$obj = null;
		
		$md=$this->owner->getMetaData();
		
		foreach($md->relations as $name=>$relation){
			
			$obj = $this->owner->getRelated($name);
			
			$related[$name] = $obj instanceof CActiveRecord ? $obj->getAttributes() : $obj;
		}
	    
	    return $related;
	}
}
