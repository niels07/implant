<?php

namespace Implant\Ioc;

/**
 * Abstract parameter class.
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
abstract class AbstractParam implements ParamInterface {

    /**
     * Constructor.
     *
     * @param string mixed The value of the parameter.
     */
    public function __construct($value) {
        $this->value = $value;
    }

    /**
     * Get the value of the paramter.
     *
     * @return mixed The parameter value.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the value of the parameter.
     *
     * @param string $value
     */
    public function setValue($value): void {
        $this->value = $value;
    }
}
