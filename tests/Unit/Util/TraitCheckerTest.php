<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Unit\Util;

use Dmytrof\ModelsManagementBundle\Model\Traits\{ActiveModelTrait, DefinedModelTrait, SimpleModelTrait, TargetedModelTrait, TimestampableEntityTrait, TimestampableModelTrait};
use Dmytrof\ModelsManagementFractalBundle\Tests\Fixtures\Model\{ChildModel, CommentModel, FullModel, PlainModel};
use Dmytrof\ModelsManagementFractalBundle\Util\TraitChecker;
use Gedmo\Timestampable\Traits\Timestampable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TraitCheckerTest extends TestCase
{
    public static function cases(): iterable
    {
        yield 'direct trait' => [FullModel::class, ActiveModelTrait::class, true];
        yield 'trait used by a trait' => [FullModel::class, DefinedModelTrait::class, true];
        yield 'trait used by a trait of a third-party package' => [FullModel::class, Timestampable::class, true];
        yield 'trait of the parent class' => [ChildModel::class, TimestampableModelTrait::class, true];
        yield 'nested trait of the parent class' => [ChildModel::class, DefinedModelTrait::class, true];
        yield 'missing trait' => [FullModel::class, TargetedModelTrait::class, false];
        yield 'other timestampable trait' => [FullModel::class, TimestampableEntityTrait::class, false];
        yield 'class without traits' => [PlainModel::class, SimpleModelTrait::class, false];
        yield 'targeted model' => [CommentModel::class, TargetedModelTrait::class, true];
    }

    #[DataProvider('cases')]
    public function testCheck(string $class, string $trait, bool $expected): void
    {
        $this->assertSame($expected, TraitChecker::check($class, $trait));
    }

    public function testAcceptsObjects(): void
    {
        $this->assertTrue(TraitChecker::check(new ChildModel(), TimestampableModelTrait::class));
        $this->assertFalse(TraitChecker::check(new PlainModel(), TimestampableModelTrait::class));
    }
}
