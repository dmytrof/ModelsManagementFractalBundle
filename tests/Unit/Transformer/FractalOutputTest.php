<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\Transformer;

use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\FractalManagerFactory;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\{ChildModel, CommentModel, FullModel, PlainModel};
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer\{CommentModelTransformer, FullModelTransformer, PlainModelTransformer};
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Complete Fractal output through FractalManager (includes resolved by Fractal scopes).
 */
class FractalOutputTest extends TestCase
{
    private function model(string $class = FullModel::class): FullModel
    {
        $model = new $class();
        $model->name = 'Apple';
        $model->setId(1)->setActive(true);
        $model->setPosition(3);
        $model->setCreatedAt(new \DateTime('2026-10-28T10:00:00+00:00'));
        $model->setUpdatedAt(new \DateTime('2026-10-29T10:00:00+00:00'));

        return $model;
    }

    public function testModelData(): void
    {
        $data = FractalManagerFactory::create()->getModelData($this->model(), FullModelTransformer::class);

        $this->assertSame([
            'active' => true,
            'createdAt' => '2026-10-28T10:00:00+00:00',
            'id' => 1,
            'name' => 'Apple',
            'position' => 3,
            'updatedAt' => '2026-10-29T10:00:00+00:00',
        ], $data);
    }

    public function testSubclassModelIsDecoratedToo(): void
    {
        $data = FractalManagerFactory::create()->getModelData($this->model(ChildModel::class), FullModelTransformer::class);

        $this->assertSame(['active', 'createdAt', 'id', 'name', 'position', 'updatedAt'], array_keys($data));
    }

    public function testPlainModelHasNoExtraFields(): void
    {
        $model = new PlainModel();
        $model->name = 'Plain';

        $this->assertSame(['name' => 'Plain'], FractalManagerFactory::create()->getModelData($model, PlainModelTransformer::class));
    }

    public function testCollectionData(): void
    {
        $second = $this->model()->setPosition(4);
        $second->setId(2);

        $data = FractalManagerFactory::create()->getCollectionData([$this->model(), $second], FullModelTransformer::class);

        $this->assertSame([1, 2], array_column($data, 'id'));
        $this->assertSame([3, 4], array_column($data, 'position'));
    }

    public function testTargetIsIncludedOnRequest(): void
    {
        $comment = new CommentModel();
        $comment->setId(10);
        $comment->setEventDispatcher(new EventDispatcher());
        $comment->setTarget($this->model());

        $manager = FractalManagerFactory::create();
        $this->assertSame(['id' => 10], $manager->getModelData($comment, CommentModelTransformer::class), 'target is not a default include');

        $manager->parseIncludes('target');
        $this->assertSame([
            'id' => 10,
            'target' => ['className' => FullModel::class, 'id' => 1],
        ], $manager->getModelData($comment, CommentModelTransformer::class));
    }
}
