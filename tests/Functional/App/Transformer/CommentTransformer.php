<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Transformer;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity\Comment;

class CommentTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = Comment::class;

    public function transformSubject($subject): array
    {
        return ['text' => $subject->getText()];
    }
}
