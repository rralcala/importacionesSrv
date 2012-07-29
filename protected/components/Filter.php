
<?php
/**
 * Helper Class that processes filters sent by the client.
 **/

class Filter {
	
    /**
     * @param $filter: a JSON object holding filter values. The filter
     * 				   object looks like if it is a grid's filter plugin
     * 	[{
	 *		"type":"customlist",
	 *		"value":["Cocaco","Fumaca","Goldex"],
	 *		"field":"Brand"
	 *	},{
	 *	  "type":"string",
	 *	  "value":"AAA",
	 *	  "field":"Code"
	 *	}]
     * 
     * 	Or it looks like this if it is Ext.util.Filter
     * 	[{"property":"request_id","value":27}]
     * 
     * @return Array of ColumnName => array(values).
     **/
    public static function process_filter($filter){
        $ret = array();
        if(!$filter){
            return $ret;
        }        
        $filter = CJSON::decode($filter);
        
        foreach($filter as $f){
            $val = $f["value"];
            $field = isset($f["field"]) ? $f["field"] : $f["property"];
            $ret[$field] = is_array($val) ? $val : array($val);
        }
        return $ret;
    }
	
}
