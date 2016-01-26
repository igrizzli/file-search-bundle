<?php
namespace Vilks\FileSearchBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Vilks\FileSearchBundle\DependencyInjection\Compiler\EnginePass;
use Vilks\FileSearchBundle\DependencyInjection\VilksFileSearchExtension;

class EnginePassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $compiler = new EnginePass();
        $container = $this->getContainer('no_engine.yml');

        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_2']);
        $container->setDefinition('vilks.file_search.engine.php_read_2', $definition);
        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_3']);
        $container->setDefinition('vilks.file_search.engine.php_read_3', $definition);

        $container->addCompilerPass($compiler);
        $container->compile();

        $calls = $container->getDefinition('vilks.file_search.registry')->getMethodCalls();
        $this->assertEquals('setDefault', $calls[0][0]);
        $this->assertEquals('php_read', $calls[0][1][0]);

        $checked = false;
        $add = [
            'php_read' => new Reference('vilks.file_search.engine.php_read'),
            'php_read_2' => new Reference('vilks.file_search.engine.php_read_2'),
            'php_read_3' => new Reference('vilks.file_search.engine.php_read_3')
        ];
        foreach ($calls as $call) {
            if ($call[0] == 'setDefault') {
                $this->assertEquals('php_read', $call[1][0]);
                $this->assertFalse($checked);
                $checked = true;
            } elseif ($call[0] == 'add') {
                $this->assertArrayHasKey($call[1][0], $add);
                $this->assertEquals($call[1][1], $add[$call[1][0]]);
            }
        }


        $engines = $container->getParameter('vilks.file_search.engines');
        $this->assertCount(3, $engines);
        $ids = [
            'vilks.file_search.engine.php_read',
            'vilks.file_search.engine.php_read_2',
            'vilks.file_search.engine.php_read_3'
        ];
        $this->assertCount(0, array_diff($ids, array_values($engines)));
        $this->assertCount(0, array_diff(['php_read', 'php_read_2', 'php_read_3'], array_keys($engines)));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTagWithoutEngine()
    {
        $compiler = new EnginePass();
        $container = $this->getContainer('no_engine.yml');

        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_2']);
        $container->setDefinition('vilks.file_search.engine.php_read_2', $definition);
        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['fail' => 'php_read_3']);
        $container->setDefinition('vilks.file_search.engine.php_read_3', $definition);

        $container->addCompilerPass($compiler);
        $container->compile();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDuplicateEngine()
    {
        $compiler = new EnginePass();
        $container = $this->getContainer('no_engine.yml');

        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_2']);
        $container->setDefinition('vilks.file_search.engine.php_read_2', $definition);
        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_2']);
        $container->setDefinition('vilks.file_search.engine.php_read_3', $definition);

        $container->addCompilerPass($compiler);
        $container->compile();
    }

    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\EngineNotExistsException
     */
    public function testNotExistsEngine()
    {
        $compiler = new EnginePass();
        $container = $this->getContainer('engine.yml');

        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_2']);
        $container->setDefinition('vilks.file_search.engine.php_read_2', $definition);
        $definition = new Definition('Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine');
        $definition->addTag('vilks.file_search.engine', ['engine' => 'php_read_3']);
        $container->setDefinition('vilks.file_search.engine.php_read_3', $definition);

        $container->addCompilerPass($compiler);
        $container->compile();
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

        return $container;
    }
}
