<?php
namespace Vilks\FileSearchBundle\Exception;

class EngineNotExistsException extends \InvalidArgumentException
{
    private $name;

    /**
     * @inheritDoc
     */
    public function __construct($name)
    {
        $this->name = $name;
        parent::__construct(sprintf('File search engine with name "%s" not exists', $name));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
