<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\Transformer;

use Dmytrof\FractalBundle\Exception\TransformerException;
use Dmytrof\ModelsManagementBundle\Model\Target;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\FractalManagerFactory;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\FullModel;
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TargetTransformerTest extends TestCase
{
    private TargetTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new TargetTransformer(FractalManagerFactory::extensions());
    }

    public function testTransformsTarget(): void
    {
        $target = new Target(new EventDispatcher(), (new FullModel())->setId(5));

        $this->assertSame(['id' => 5, 'className' => FullModel::class], $this->transformer->transform($target));
    }

    public function testTargetWithoutModel(): void
    {
        $this->assertSame(['id' => null, 'className' => null], $this->transformer->transform(new Target(new EventDispatcher())));
    }

    public function testNoExtensionsApply(): void
    {
        $this->assertCount(0, $this->transformer->getExtensions());
    }

    public function testSupportsTargetsOnly(): void
    {
        $this->assertSame(Target::class, $this->transformer->getSubjectClass());
        $this->assertFalse($this->transformer->supports(new FullModel()));

        $this->expectException(TransformerException::class);
        $this->transformer->transform(new FullModel());
    }
}
