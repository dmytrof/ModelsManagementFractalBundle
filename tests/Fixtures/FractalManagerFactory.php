<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures;

use Dmytrof\FractalBundle\Service\{ExtensionsContainer, FractalManager, ScopeFactory, TransformersContainer};
use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Transformer\Extension\{ActiveModelExtension, SimpleModelExtension, SortableModelExtension, TargetedModelExtension, TimestampableModelExtension};
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer\{CommentModelTransformer, FullModelTransformer, PlainModelTransformer};

/**
 * Wires a FractalManager with all bundle extensions and test transformers, without a kernel.
 */
final class FractalManagerFactory
{
    public static function extensions(): ExtensionsContainer
    {
        return new ExtensionsContainer([
            new SimpleModelExtension(),
            new ActiveModelExtension(),
            new SortableModelExtension(),
            new TimestampableModelExtension(),
            new TargetedModelExtension(),
        ]);
    }

    /**
     * @param class-string<AbstractTransformer>[] $transformerClasses
     */
    public static function create(array $transformerClasses = [FullModelTransformer::class, PlainModelTransformer::class, CommentModelTransformer::class, TargetTransformer::class]): FractalManager
    {
        $extensions = self::extensions();
        $transformers = array_map(fn (string $class) => new $class($extensions), $transformerClasses);

        return new FractalManager(new TransformersContainer($transformers), new ScopeFactory());
    }
}
