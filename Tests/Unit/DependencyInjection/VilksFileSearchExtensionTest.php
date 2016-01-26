<?php
namespace Vilks\FileSearchBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Vilks\FileSearchBundle\DependencyInjection\VilksFileSearchExtension;

class VilksFileSearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testEngineDefinition()
    {
        $container = $this->getContainer('engine.yml');

        $this->assertEquals('dummy', $container->getParameter('vilks.file_search.default_engine'));
    }

    public function testDefaultDefinition()
    {
        $container = $this->getContainer('no_engine.yml');

        $this->assertEquals('php_read', $container->getParameter('vilks.file_search.default_engine'));
    }

    private function getContainer($file, $debug = false)
    {
        $container = new ContainerBuilder(new ParameterBag(array('kernel.debug' => $debug)));
        $container->registerExtension(new VilksFileSearchExtension());

        $locator = new FileLocator(__DIR__ . '/Fixtures');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
