<?php
namespace Vilks\FileSearchBundle\Engine;

use Vilks\FileSearchBundle\Exception\EngineNotExistsException;

class EngineRegistry
{
    private $engines = [];
    private $default;

    /**
     * Set default search engine
     *
     * @param string $name
     *
     * @return self
     */
    public function setDefault($name)
    {
        $this->default = $name;

        return $this;
    }

    /**
     * Add new search engine
     *
     * @param string $name
     * @param FileSearchEngineInterface $engine
     *
     * @return self
     */
    public function add($name, FileSearchEngineInterface $engine)
    {
        $this->engines[$name] = $engine;

        return $this;
    }

    /**
     * Return names of all engines
     *
     * @return string[]
     */
    public function names()
    {
        return array_keys($this->engines);
    }

    /**
     * Return engine
     *
     * @param string $name
     *
     * @return FileSearchEngineInterface
     * @throws EngineNotExistsException
     */
    public function get($name = null)
    {
        $name = $name ?: $this->default;

        if (!$name || !array_key_exists($name, $this->engines)) {
            throw new EngineNotExistsException($name);
        }

        return $this->engines[$name];
    }
}
