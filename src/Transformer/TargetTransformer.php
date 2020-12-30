<?php
/*
 * This file is part of the DmytrofModelsManagementFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\ModelsManagementFractalBundle\Transformer;

use Dmytrof\ModelsManagementBundle\Model\Target as Model;
use Dmytrof\FractalBundle\Transformer\AbstractTransformer;

class TargetTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = Model::class;

    /**
     * Transforms target to array
     * @param Model $subject
     * @return array
     */
    public function transformSubject($subject): array
    {
        return [
            'id'        => $subject->getId(),
            'className' => $subject->getClassName(),
        ];
    }
}