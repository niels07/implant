<?php

namespace Implant\Ioc;

/**
 * Interface for services.
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
interface ServiceInterface {
    /**
     * Get the parameter class.
     *
     * @return string Name of the class.
     */
    public function getClass(): string;

    /**
     * Bind a constructor parameter.
     *
     * @param string $name Name of the parameter.
     * @param ParamInterface $param The parameter to bind.
     * @return Ioc\Service $this for function chaining.
     */
    public function bindParam(string $name, ParamInterface $param);

    /**
     * Get a paramter that is bound to $name. Returns null if a parameter does
     * not exist.
     *
     * @var string $name Name of the parameter.
     * @return ParamInterface
     */
    public function getParam(string $name): ?ParamInterface;
}
