<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Repository;

use Dmytrof\ModelsManagementBundle\Repository\{EntityRepositoryInterface, Traits\EntityRepositoryTrait};
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository implements EntityRepositoryInterface
{
    use EntityRepositoryTrait;
}
