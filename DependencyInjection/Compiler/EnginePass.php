<?php
namespace Vilks\FileSearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vilks\FileSearchBundle\Exception\EngineNotExistsException;

/**
 * Compiler pass for engine loading
 */
class EnginePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $engines = [];
        $registry = $container->getDefinition('vilks.file_search.registry');
        $defaultEngine = $container->getParameter('vilks.file_search.default_engine');
        $registry->addMethodCall('setDefault', [$defaultEngine]);
        foreach ($container->findTaggedServiceIds('vilks.file_search.engine') as $id => $attr) {
            $attr = $attr[0];
            if (!array_key_exists('engine', $attr)) {
                throw new \InvalidArgumentException('File search engine tag must have "engine" attribute');
            }
            if (array_key_exists($attr['engine'], $engines)) {
                throw new \InvalidArgumentException(
                    sprintf('Duplicate file search engine with name "%s"', $attr['engine'])
                );
            }
            $engines[$attr['engine']] = $id;
            $registry->addMethodCall('add', [$attr['engine'], new Reference($id)]);
        }

        $container->setParameter('vilks.file_search.engines', $engines);
        if (!array_key_exists($defaultEngine, $engines)) {
            throw new EngineNotExistsException($defaultEngine);
        }
    }
}
