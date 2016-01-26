<?php
namespace Vilks\FileSearchBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vilks\FileSearchBundle\DependencyInjection\Compiler;

class VilksFileSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new Compiler\EnginePass());
    }
}
