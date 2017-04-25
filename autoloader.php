<?php

namespace Implant;

/**
 * Autoloader class.
 *
 * @package Implant
 * @author  Niels Vanden Eynde
 */
class Autoloader {
    /**
     * Root namespace.
     *
     * @var string
     */
    private $namespace;

    /**
     * Path to the root directory.
     *
     * @var string
     */
    private $path;

    /**
     * Constructor.
     *
     * @param string $namespace Root namespace.
     * @param string $path Path to the root directory.
     */
    public function __construct(string $namespace, string $path) {
        $this->namespace = $namespace;
        $this->path = $path;
    }

    /**
     * Register autoloader function.
     */
    public function invoke(): void {
        spl_autoload_register([$this, 'autoLoader']);
    }

    /**
     * Get the path to a file based on the namespace and class.
     *
     * @param string $class Name of the class.
     * @return string Path to the file containing the class.
     */
    private function getFilePath(string $class): string {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        if (strpos($class, $this->namespace) === 0) {
            $path = str_replace($this->namespace . '/', '', $path);
        }
        $path = implode('/', array_map(function($e) { return lcfirst($e); }, explode('/', $path)));
        return $this->path . DIRECTORY_SEPARATOR . $path . ".php";
    }

    /**
     * Autoloader function.
     *
     * @param string $name
     */
    private function autoLoader(string $class): void {
        $filePath = $this->getFilePath($class);
        if (is_readable($filePath)) {
            require $filePath;
        }
    }
}
