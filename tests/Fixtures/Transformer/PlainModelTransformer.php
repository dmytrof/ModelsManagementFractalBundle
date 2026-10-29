<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\PlainModel;

class PlainModelTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = PlainModel::class;

    public function transformSubject($subject): array
    {
        return ['name' => $subject->name];
    }
}
