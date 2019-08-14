<?php
class Tritac_CapayableApiClient_Enums_Enum {

    private static $reflector;

    public static function toArray()
    {
        self::$reflector = new ReflectionClass(get_called_class());
        return self::$reflector->getConstants();
    }

    public static function toString($value){
        $fields = self::toArray();
        return array_search($value, $fields);
    }

}