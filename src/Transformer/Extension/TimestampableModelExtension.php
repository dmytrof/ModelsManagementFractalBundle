<?php

/*
 * This file is part of the DmytrofModelsManagementFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\ModelsManagementFractalBundle\Transformer\Extension;

use Dmytrof\FractalBundle\Transformer\{AbstractTransformer, Extension\AbstractExtension};
use Dmytrof\ModelsManagementFractalBundle\Util\TraitChecker;
use Dmytrof\ModelsManagementBundle\Model\Traits\{TimestampableModelTrait, TimestampableEntityTrait};
use League\Fractal\Resource\{Primitive, ResourceInterface};

class TimestampableModelExtension extends AbstractExtension
{
    public const INCLUDE_CREATED_AT = 'createdAt';
    public const INCLUDE_UPDATED_AT = 'updatedAt';

    /**
     * {@inheritdoc}
     */
    protected function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return TraitChecker::check($reflectionClass->getName(), TimestampableModelTrait::class)
            || TraitChecker::check($reflectionClass->getName(), TimestampableEntityTrait::class);
    }

    /**
     * {@inheritdoc}
     */
    public function decorateTransformer(AbstractTransformer $transformer): void
    {
        parent::decorateTransformer($transformer);
        $transformer
            ->addDefaultInclude(static::INCLUDE_CREATED_AT, static::INCLUDE_UPDATED_AT)
            ->setIncludeCall(static::INCLUDE_CREATED_AT, [$this, 'includeCreatedAt'])
            ->setIncludeCall(static::INCLUDE_UPDATED_AT, [$this, 'includeUpdatedAt'])
        ;
    }

    /**
     * Includes created at
     * @param $subject
     * @param AbstractTransformer $transformer
     * @return ResourceInterface
     */
    public function includeCreatedAt($subject, AbstractTransformer $transformer): ResourceInterface
    {
        return new Primitive($transformer->transformDateTime($subject->getCreatedAt()));
    }

    /**
     * Includes updated at
     * @param $subject
     * @param AbstractTransformer $transformer
     * @return ResourceInterface
     */
    public function includeUpdatedAt($subject, AbstractTransformer $transformer): ResourceInterface
    {
        return new Primitive($transformer->transformDateTime($subject->getUpdatedAt()));
    }
}