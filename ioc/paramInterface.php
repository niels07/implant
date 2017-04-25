<?php

namespace Implant\Ioc;

/**
 * Interface for parameter classes.
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
interface ParamInterface {

    /**
     * Get the parameter value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Sets the value of the parameter.
     *
     * @param mixed $value
     */
    public function setValue($value);



}
