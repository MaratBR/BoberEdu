<?php


namespace App\Http\DTO;


use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class DtoBase implements Arrayable
{

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $reflect = new ReflectionClass(static::class);
        $methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $data = [];

        foreach ($props as $prop)
        {
            $data[$prop->name] = $prop->getValue($this);
            if ($data[$prop->name] instanceof Arrayable)
                $data[$prop->name] = $data[$prop->name]->toArray();
        }

        foreach ($methods as $method)
        {
            if ($method->getNumberOfParameters() == 0)
            {
                if (substr($method->name, 0, 3) === 'get')
                    $prop = substr($method->name, 3);
                elseif (substr($method->name, 0, 2) === 'is')
                    $prop = substr($method->name, 2);
                else
                    continue;
                if (ctype_upper($prop))
                    $prop = strtolower($prop);
                else
                    $prop = lcfirst($prop);


                $data[$prop] = $method->invoke($this);
                if ($data[$prop] instanceof Arrayable)
                    $data[$prop] = $data[$prop]->toArray();
            }
        }

        return $data;
    }
}
