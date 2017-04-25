<?php

namespace Implant\Ioc;

/**
 * Inversion of control
 *
 * @package Implant
 * @author Niels Vanden Eynde
 */
class Service implements ServiceInterface {

    /**
     * Service class name
     *
     * @var string
     */
    private $class;

    /**
     * Service parameters.
     *
     * @var array
     */
    private $params;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class) {
        $this->class = $class;
        $this->params = [];
    }

    /**
     * Get the parameter class.
     *
     * @return string Name of the class.
     */
    public function getClass(): string {
        return $this->class;
    }

    /**
     * Bind a constructor parameter.
     *
     * @param string $name Name of the parameter.
     * @param ParamInterface $param The parameter to bind.
     * @return Ioc\Service $this for function chaining.
     */
    public function bindParam(string $name, ParamInterface $param) {
        $this->params[$name] = $param;
        return $this;
    }

    /**
     * Get a paramter that is bound to $name. Returns null if a parameter does
     * not exist.
     *
     * @var string $name Name of the parameter.
     * @return ParamInterface
     */
    public function getParam(string $name): ?ParamInterface {
        return array_key_exists($name, $this->params)
            ? $this->params[$name] : null;
    }

    /**
     * Get the parameters bound to this service.
     *
     * @return array
     */
    public function getParams(): array {
        return $this->params;
    }
}
