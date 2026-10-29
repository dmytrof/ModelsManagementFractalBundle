<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Manager;

use Dmytrof\ModelsManagementBundle\Manager\AbstractDoctrineManager;
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity\Article;

class ArticleManager extends AbstractDoctrineManager
{
    public const MODEL_CLASS = Article::class;
}
