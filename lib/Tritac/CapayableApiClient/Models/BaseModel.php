<?php
abstract class Tritac_CapayableApiClient_Models_BaseModel {

    public static $typeMap = array(

    );

    public function getTypeName()
    {
        return get_called_class();
    }

    public function toArray()
    {
        $vars = get_object_vars($this);
        $arr = array();
        foreach($vars as $key => $value) {
            $newKey = ucfirst($key);
            $getter = 'get' . $newKey;
            $value =  $this->{$getter}();

            if(is_bool($value)) {
                $arr[$newKey] = ($value) ? 'True' : 'False';
            } elseif(!empty($value)) {
                $arr[$newKey] = $value;
            }

        }

        return $arr;
    }
}