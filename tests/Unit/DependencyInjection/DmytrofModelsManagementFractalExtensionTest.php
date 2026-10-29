<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\DependencyInjection;

use Dmytrof\ModelsManagementFractalBundle\DependencyInjection\{Configuration, DmytrofModelsManagementFractalExtension};
use Dmytrof\ModelsManagementFractalBundle\Transformer\Extension\{ActiveModelExtension, SimpleModelExtension, SortableModelExtension, TargetedModelExtension, TimestampableModelExtension};
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DmytrofModelsManagementFractalExtensionTest extends TestCase
{
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        (new DmytrofModelsManagementFractalExtension())->load([], $this->container);
    }

    public function testConfigurationIsEmpty(): void
    {
        $this->assertSame([], (new Processor())->processConfiguration(new Configuration(), []));
    }

    public function testExtensionsAndTransformerAreAutoconfiguredServices(): void
    {
        foreach ([
            SimpleModelExtension::class, ActiveModelExtension::class, SortableModelExtension::class,
            TimestampableModelExtension::class, TargetedModelExtension::class, TargetTransformer::class,
        ] as $id) {
            $this->assertTrue($this->container->hasDefinition($id), "$id is registered");
            $this->assertTrue($this->container->getDefinition($id)->isAutoconfigured(), "$id is autoconfigured");
        }
    }
}
