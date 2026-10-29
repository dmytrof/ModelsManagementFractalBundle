<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model;

use Dmytrof\ModelsManagementBundle\Model\{SimpleModelInterface, TargetedModelInterface};
use Dmytrof\ModelsManagementBundle\Model\Traits\{SimpleModelTrait, TargetedModelTrait};

class CommentModel implements SimpleModelInterface, TargetedModelInterface
{
    use SimpleModelTrait, TargetedModelTrait;

    protected mixed $id = null;
}
