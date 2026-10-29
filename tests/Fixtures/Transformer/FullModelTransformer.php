<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\FullModel;

class FullModelTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = FullModel::class;

    public function transformSubject($subject): array
    {
        return ['name' => $subject->name];
    }
}
