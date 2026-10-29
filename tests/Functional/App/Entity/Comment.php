<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity;

use Dmytrof\ModelsManagementBundle\Model\{SimpleModelInterface, TargetedModelInterface};
use Dmytrof\ModelsManagementBundle\Model\Traits\{SimpleModelTrait, TargetedModelTrait};
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment implements SimpleModelInterface, TargetedModelInterface
{
    use SimpleModelTrait, TargetedModelTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string')]
    protected string $text = '';

    #[ORM\Column(type: 'json', nullable: true)]
    protected ?array $target = null;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
