<?php
/**
 * add autoload function starting with _get to Eloquent
 */
class basemodel extends Eloquent
{

    /**
     * return object to_array with _get method
     * @return array
     */
    public function presenter()
    {
        $rval = $this->to_array();
        $class = new ReflectionClass($this);
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            if ($this->starts_with($method->name, "get_") && !$this->starts_with($method->class, "Laravel")) {
                $method = new ReflectionMethod($this, $method->name);
                $rval[substr($method->name, 4)] = $method->invoke($this);
                Log::info('toto');
            }
        }

        return $rval;
    }

    /**
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    private function starts_with($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }
}
