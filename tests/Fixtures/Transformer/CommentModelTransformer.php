<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Transformer;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\CommentModel;

class CommentModelTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = CommentModel::class;

    public function transformSubject($subject): array
    {
        return [];
    }
}
