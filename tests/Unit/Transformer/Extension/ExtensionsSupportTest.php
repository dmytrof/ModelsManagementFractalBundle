<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\Transformer\Extension;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\FractalBundle\Transformer\Extension\ExtensionInterface;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\FractalManagerFactory;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\{ChildModel, CommentModel, FullModel, PlainModel};
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer\FullModelTransformer;
use Dmytrof\ModelsManagementFractalBundle\Transformer\Extension\{ActiveModelExtension, SimpleModelExtension, SortableModelExtension, TargetedModelExtension, TimestampableModelExtension};
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Which model classes each extension applies to.
 */
class ExtensionsSupportTest extends TestCase
{
    public static function cases(): iterable
    {
        $all = [FullModel::class, ChildModel::class, CommentModel::class, PlainModel::class];
        $matrix = [
            SimpleModelExtension::class => [FullModel::class, ChildModel::class, CommentModel::class],
            ActiveModelExtension::class => [FullModel::class, ChildModel::class],
            SortableModelExtension::class => [FullModel::class, ChildModel::class],
            TimestampableModelExtension::class => [FullModel::class, ChildModel::class],
            TargetedModelExtension::class => [CommentModel::class],
        ];
        foreach ($matrix as $extension => $supported) {
            foreach ($all as $model) {
                $short = fn (string $c) => substr(strrchr($c, '\\'), 1);
                yield $short($extension).' / '.$short($model) => [$extension, $model, in_array($model, $supported, true)];
            }
        }
    }

    #[DataProvider('cases')]
    public function testSupports(string $extensionClass, string $modelClass, bool $expected): void
    {
        /** @var ExtensionInterface $extension */
        $extension = new $extensionClass();
        $transformer = new FullModelTransformer(FractalManagerFactory::extensions());

        $this->assertSame($expected, $extension->supports(new \ReflectionClass($modelClass), $transformer));
    }

    public function testTransformerPicksSupportedExtensions(): void
    {
        $transformer = new FullModelTransformer(FractalManagerFactory::extensions());

        $this->assertEqualsCanonicalizing([
            SimpleModelExtension::class,
            ActiveModelExtension::class,
            SortableModelExtension::class,
            TimestampableModelExtension::class,
        ], $transformer->getExtensions()->getKeys());
    }

    public function testDecoratedTransformerIncludes(): void
    {
        /** @var AbstractTransformer $transformer */
        $transformer = (new FullModelTransformer(FractalManagerFactory::extensions()))->setup();

        $this->assertEqualsCanonicalizing(['id', 'createdAt', 'updatedAt'], $transformer->getDefaultIncludes());
        $this->assertSame([], $transformer->getAvailableIncludes());
        foreach (['id', 'createdAt', 'updatedAt'] as $include) {
            $this->assertTrue($transformer->hasIncludeCall($include), "include call for $include");
        }
    }
}
