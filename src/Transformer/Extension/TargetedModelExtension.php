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
use Dmytrof\ModelsManagementBundle\Model\TargetedModelInterface;
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use League\Fractal\Resource\{Item, ResourceInterface};

class TargetedModelExtension extends AbstractExtension
{
    public const INCLUDE_TARGET = 'target';

    /**
     * {@inheritdoc}
     */
    public function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return $reflectionClass->implementsInterface(TargetedModelInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function decorateTransformer(AbstractTransformer $transformer): void
    {
        parent::decorateTransformer($transformer);

        $transformer
            ->addAvailableInclude(static::INCLUDE_TARGET)
            ->setIncludeCall(static::INCLUDE_TARGET, [$this, 'includeTarget'])
        ;
    }

    /**
     * Includes target
     * @param TargetedModelInterface $subject
     * @param AbstractTransformer $transformer
     * @return ResourceInterface
     */
    public function includeTarget($subject, AbstractTransformer $transformer): ResourceInterface
    {
        return new Item($subject->getTarget(), TargetTransformer::class);
    }
}