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
use Dmytrof\ModelsManagementBundle\Model\SimpleModelInterface;
use League\Fractal\Resource\{Primitive, ResourceInterface};

class SimpleModelExtension extends AbstractExtension
{
    public const INCLUDE_ID = 'id';

    /**
     * {@inheritdoc}
     */
    public function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return $reflectionClass->implementsInterface(SimpleModelInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function decorateTransformer(AbstractTransformer $transformer): void
    {
        parent::decorateTransformer($transformer);

        $transformer
            ->addDefaultInclude(static::INCLUDE_ID)
            ->setIncludeCall(static::INCLUDE_ID, [$this, 'includeId'])
        ;
    }

    /**
     * Includes id
     * @param SimpleModelInterface $subject
     * @param AbstractTransformer $transformer
     * @return ResourceInterface
     */
    public function includeId($subject, AbstractTransformer $transformer): ResourceInterface
    {
        return new Primitive($subject->getId());
    }
}