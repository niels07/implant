<?php

namespace Implant\Ioc;

/**
 * Inversion of control
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
class FactoryService extends Service {
    /**
     * Associative array to determine what the parameters
     * are for the delegate method.
     *
     * @var array
     */
    private $template;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class, array $template = null) {
        $this->template = $template;
        parent::__construct($class);
    }

    /**
     * Get the array containing the parameters for the
     * delegate method.
     *
     * @return array
     */
    public function getTemplate(): ?array {
        return $this->template;
    }
}
