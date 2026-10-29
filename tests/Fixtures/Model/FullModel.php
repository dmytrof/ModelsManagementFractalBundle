<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model;

use Dmytrof\ModelsManagementBundle\Model\{ActiveModelInterface, SimpleModelInterface};
use Dmytrof\ModelsManagementBundle\Model\Traits\{ActiveModelTrait, SimpleModelTrait, TimestampableModelTrait};

/**
 * Supported by every extension except TargetedModelExtension.
 */
class FullModel implements SimpleModelInterface, ActiveModelInterface
{
    use SimpleModelTrait, ActiveModelTrait, TimestampableModelTrait;

    protected mixed $id = null;
    protected int $position = 0;
    public string $name = '';

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
