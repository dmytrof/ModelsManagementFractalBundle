<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity;

use Dmytrof\ModelsManagementBundle\Model\{ActiveModelInterface, SimpleModelInterface};
use Dmytrof\ModelsManagementBundle\Model\Traits\{ActiveModelTrait, SimpleModelTrait, TimestampableEntityTrait};
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article implements SimpleModelInterface, ActiveModelInterface
{
    use SimpleModelTrait, ActiveModelTrait, TimestampableEntityTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string')]
    protected string $title = '';

    #[ORM\Column(type: 'integer')]
    protected int $position = 0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

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
