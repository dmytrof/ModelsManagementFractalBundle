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

class SortableModelExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function _supports(\ReflectionClass $reflectionClass, AbstractTransformer $transformer): bool
    {
        return $reflectionClass->hasMethod('getPosition');
    }

    /**
     * {@inheritdoc}
     */
    public function transform($subject, AbstractTransformer $transformer): array
    {
        return [
            'position' => $subject->getPosition(),
        ];
    }
}