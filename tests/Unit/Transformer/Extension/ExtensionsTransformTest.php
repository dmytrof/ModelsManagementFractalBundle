<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\Transformer\Extension;

use Dmytrof\ModelsManagementBundle\Model\Target;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\FractalManagerFactory;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\{CommentModel, FullModel};
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer\{CommentModelTransformer, FullModelTransformer};
use Dmytrof\ModelsManagementFractalBundle\Transformer\Extension\{ActiveModelExtension, SimpleModelExtension, SortableModelExtension, TargetedModelExtension, TimestampableModelExtension};
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use League\Fractal\Resource\{Item, Primitive};
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Extension methods called directly.
 */
class ExtensionsTransformTest extends TestCase
{
    private FullModelTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new FullModelTransformer(FractalManagerFactory::extensions());
    }

    private function model(): FullModel
    {
        $model = (new FullModel())->setPosition(7);
        $model->setId(42);
        $model->setActive(false);
        $model->setCreatedAt(new \DateTime('2026-10-28 10:00:00+00:00'));
        $model->setUpdatedAt(new \DateTime('2026-10-29 11:30:00+02:00'));

        return $model;
    }

    public function testActiveModelExtension(): void
    {
        $this->assertSame(['active' => false], (new ActiveModelExtension())->transform($this->model(), $this->transformer));
        $this->assertSame(['active' => true], (new ActiveModelExtension())->transform($this->model()->activate(), $this->transformer));
    }

    public function testSortableModelExtension(): void
    {
        $this->assertSame(['position' => 7], (new SortableModelExtension())->transform($this->model(), $this->transformer));
    }

    public function testSimpleModelExtensionIncludesId(): void
    {
        $resource = (new SimpleModelExtension())->includeId($this->model(), $this->transformer);

        $this->assertInstanceOf(Primitive::class, $resource);
        $this->assertSame(42, $resource->getData());
        $this->assertSame([], (new SimpleModelExtension())->transform($this->model(), $this->transformer), 'id is an include, not a transformed field');
    }

    public function testTimestampableModelExtensionIncludesDates(): void
    {
        $extension = new TimestampableModelExtension();

        $this->assertSame('2026-10-28T10:00:00+00:00', $extension->includeCreatedAt($this->model(), $this->transformer)->getData());
        $this->assertSame('2026-10-29T11:30:00+02:00', $extension->includeUpdatedAt($this->model(), $this->transformer)->getData());
        $this->assertNull($extension->includeCreatedAt(new FullModel(), $this->transformer)->getData());
    }

    public function testTargetedModelExtensionIncludesTarget(): void
    {
        $comment = new CommentModel();
        $comment->setEventDispatcher(new EventDispatcher());
        $comment->setTarget($this->model());

        $resource = (new TargetedModelExtension())->includeTarget($comment, new CommentModelTransformer(FractalManagerFactory::extensions()));

        $this->assertInstanceOf(Item::class, $resource);
        $this->assertInstanceOf(Target::class, $resource->getData());
        $this->assertSame(TargetTransformer::class, $resource->getTransformer());
    }

    public function testTargetedModelExtensionMakesTargetAvailableInclude(): void
    {
        $transformer = (new CommentModelTransformer(FractalManagerFactory::extensions()))->setup();

        $this->assertSame(['target'], array_values($transformer->getAvailableIncludes()));
        $this->assertTrue($transformer->hasIncludeCall('target'));
        $this->assertSame(['id'], array_values($transformer->getDefaultIncludes()));
    }
}
