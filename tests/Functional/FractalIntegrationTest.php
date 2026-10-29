<?php

namespace Dmytrof\ModelsManagementFractalBundle\Tests\Functional;

use Dmytrof\FractalBundle\Service\{ExtensionsContainer, FractalManager, TransformersContainer};
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Entity\{Article, Comment};
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Manager\{ArticleManager, CommentManager};
use Dmytrof\ModelsManagementFractalBundle\Tests\Functional\App\Transformer\{ArticleTransformer, CommentTransformer};
use Dmytrof\ModelsManagementFractalBundle\Transformer\Extension\{ActiveModelExtension, SimpleModelExtension, SortableModelExtension, TargetedModelExtension, TimestampableModelExtension};
use Dmytrof\ModelsManagementFractalBundle\Transformer\TargetTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * The bundle in a real kernel with FractalBundle, ModelsManagementBundle and Doctrine (SQLite).
 */
class FractalIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get('doctrine.orm.entity_manager');
        (new SchemaTool($this->em))->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

    private function fractal(): FractalManager
    {
        return static::getContainer()->get(FractalManager::class);
    }

    private function createArticle(string $title, int $position = 0, bool $active = true): Article
    {
        $manager = static::getContainer()->get(ArticleManager::class);
        $article = $manager->new()->setTitle($title)->setPosition($position);
        $article->setActive($active);
        $manager->save($article);

        return $article;
    }

    public function testExtensionsAreRegisteredInFractalBundle(): void
    {
        $extensions = array_map(get_class(...), iterator_to_array(static::getContainer()->get(ExtensionsContainer::class), false));

        $this->assertEqualsCanonicalizing([
            SimpleModelExtension::class,
            ActiveModelExtension::class,
            SortableModelExtension::class,
            TimestampableModelExtension::class,
            TargetedModelExtension::class,
        ], $extensions);
    }

    public function testTargetTransformerIsRegistered(): void
    {
        $transformers = static::getContainer()->get(TransformersContainer::class);

        $this->assertTrue($transformers->has(TargetTransformer::class));
        $this->assertTrue($transformers->has(ArticleTransformer::class));
        $this->assertTrue($transformers->has(CommentTransformer::class));
    }

    public function testArticleData(): void
    {
        $article = $this->createArticle('Hello', 5, false);

        $data = $this->fractal()->getModelData($article, ArticleTransformer::class);

        $this->assertSame(['active', 'createdAt', 'id', 'position', 'title', 'updatedAt'], array_keys($data));
        $this->assertSame($article->getId(), $data['id']);
        $this->assertFalse($data['active']);
        $this->assertSame(5, $data['position']);
        $this->assertSame('Hello', $data['title']);
        // Set by the Gedmo listener on save, rendered as ATOM
        $this->assertSame($article->getCreatedAt()->format(\DateTimeInterface::ATOM), $data['createdAt']);
        $this->assertNotFalse(\DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $data['updatedAt']));
    }

    public function testArticleLoadedFromDatabase(): void
    {
        $id = $this->createArticle('Stored', 2)->getId();
        $this->em->clear();

        $data = $this->fractal()->getModelData(static::getContainer()->get(ArticleManager::class)->getItem($id), ArticleTransformer::class);

        $this->assertSame(['id' => $id, 'position' => 2, 'title' => 'Stored'], array_intersect_key($data, array_flip(['id', 'position', 'title'])));
    }

    public function testPaginatorData(): void
    {
        foreach (['First', 'Second', 'Third'] as $i => $title) {
            $this->createArticle($title, $i);
        }

        $paginator = static::getContainer()->get(ArticleManager::class)->getDoctrinePaginator(['page' => 1, 'limit' => 2]);
        $data = $this->fractal()->getPaginatorData($paginator, ArticleTransformer::class);

        $this->assertSame(['First', 'Second'], array_column($data, 'title'));
        $this->assertSame([0, 1], array_column($data, 'position'));
    }

    public function testCommentWithTargetInclude(): void
    {
        $article = $this->createArticle('Commented');
        $comments = static::getContainer()->get(CommentManager::class);
        $comment = $comments->new()->setText('Nice!')->setTarget($article);
        $comments->save($comment);
        $commentId = $comment->getId();
        $this->em->clear();

        $loaded = $comments->getItem($commentId);
        $fractal = $this->fractal();

        $this->assertSame(['id' => $commentId, 'text' => 'Nice!'], $fractal->getModelData($loaded, CommentTransformer::class));

        $fractal->parseIncludes('target');
        $this->assertSame([
            'id' => $commentId,
            'target' => ['className' => Article::class, 'id' => $article->getId()],
            'text' => 'Nice!',
        ], $fractal->getModelData($loaded, CommentTransformer::class));
    }
}
