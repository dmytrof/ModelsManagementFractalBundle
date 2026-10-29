<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Transformer;

use Dmytrof\FractalBundle\Transformer\AbstractTransformer;
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity\Article;

class ArticleTransformer extends AbstractTransformer
{
    protected const SUBJECT_CLASS = Article::class;

    public function transformSubject($subject): array
    {
        return ['title' => $subject->getTitle()];
    }
}
