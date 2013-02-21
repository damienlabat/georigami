<?php

class BaseModel extends Eloquent{

    public function presenter(){
        $rval = $this->to_array();
        $class = new ReflectionClass($this);
        $methods = $class->getMethods();
        foreach($methods as $method){
            if($this->starts_with($method->name, "get_") && !$this->starts_with($method->class, "Laravel")){
                $method = new ReflectionMethod($this, $method->name);
                $rval[substr($method->name, 4)] = $method->invoke($this);
                Log::info('toto');
            }
        }
        return $rval;
    }

    private function starts_with($haystack, $needle){
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}