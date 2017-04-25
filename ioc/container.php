<?php

namespace Implant\Ioc;

/**
 * IOC container.
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
class Container {

    /**
     * Services registered into this container.
     *
     * @var array
     */
    private $services;

    /**
     * Services that have been resolved.
     *
     * @var array
     */
    private $resolved;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->services = [];
        $this->resolved = [];
    }

    /**
     * Register a dependency.
     *
     * @param string $name Name of the service.
     * @param Service $service The service to register.
     * @return Service The service created by the container.
     */
    public function register($name, ServiceInterface $service) {
        $this->services[$name] = $service;
        return $service;
    }

    /**
     * Creates and returns the delegate for a factory service.
     *
     * @param FactoryService $service
     */
    private function getDelegate(FactoryService $service, array $params): callable {
        return function() use ($params, $service) {
            $args = func_get_args();
            $className = $service->getClass();

            $template = $service->getTemplate();

            if ($template) {
                $name = array_shift($args);
                $template = $template[$name];
                $vars = preg_filter('/^/', '@', array_keys($template));
                $className = str_replace($vars, array_values($template), $className);
            }
            $class = new \ReflectionClass($className);
            $classParams = $class->getConstructor()->getParameters();

            foreach ($classParams as $param) {
                if (!array_key_exists($param->name, $params)) {
                    continue;
                }
                $arg = $params[$param->name];
                $value = $arg->getValue();
                if ($arg instanceof TemplateParam) {
                    $value = str_replace($vars, array_values($template), $value);
                }
                $args[] = $value;
            }
            return $class->newInstanceArgs($args);
        };
    }

    /**
     * Resolve a factory service.
     *
     * @param FactoryService $service The factory to resolve.
     * @return callable The delegate method that fabricates the instance.
     */
    private function resolveFactory(FactoryService $service): callable {
        $params = $service->getParams();
        $args = [];
        foreach ($params as $name => $param) {
            if ($param instanceof ResolveParam) {
                $value = $this->resolve($param->getValue());
                $param->setValue($value);
            }
            $args[$name] = $param;
        }
        return $this->getDelegate($service, $args);
    }

    /**
     * Resolve a service.
     *
     * @param Service $service The service to be resolved.
     * @return mixed Instance of the class.
     */
    private function resolveService(Service $service) {
        $class = new \ReflectionClass($service->getClass());
        $params = $class->getConstructor()->getParameters();

        $args = [];
        foreach ($params as $param) {
            $arg = $service->getParam($param->name);
            if (!$arg) {
                continue;
            }
            $value = $arg->getValue();
            if ($arg instanceof ResolveParam) {
                $value = $this->resolve($value);
            }
            $args[] = $value;
        }
        return $class->newInstanceArgs($args);
    }

    /**
     * Create a new instance and resolve its dependencies.
     *
     * @param string $class name of the class
     * @return The new instance.
     */
    public function resolve(string $name) {
        if (array_key_exists($name, $this->resolved)) {
            return $this->resolved[$name];
        }

        $service = $this->services[$name];
        $instance = ($service instanceof FactoryService)
            ? $this->resolveFactory($service)
            : $this->resolveService($service);

        $this->resolved[$name] = $instance;
        return $instance;
    }

}

