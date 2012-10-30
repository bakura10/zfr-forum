<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrForumTest\Repository;

use DateTime;
use Doctrine\Common\DataFixtures\Loader as FixtureLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Thread;
use ZfrForum\Mapper\ThreadMapperInterface;
use ZfrForumTest\Fixture;
use ZfrForumTest\ServiceManagerTestCase;

class ThreadRepositoryTest extends ServiceManagerTestCase
{
    /**
     * @var ThreadMapperInterface
     */
    protected $threadMapper;

    /**
     * @var ORMExecutor
     */
    protected $executor;

    public function setUp()
    {
        $this->createDb();
        $this->threadMapper = self::getServiceManager()->get('ZfrForum\Mapper\ThreadMapperInterface');

        // Init the fixture
        $loader = new FixtureLoader();
        $loader->addFixture(new Fixture\CategoryFixture());
        $loader->addFixture(new Fixture\UserFixture());
        $loader->addFixture(new Fixture\ThreadFixture());
        $purger = new ORMPurger();
        $em = $this->getEntityManager();
        $this->executor = new ORMExecutor($em, $purger);
        $this->executor->execute($loader->getFixtures());
    }

    public function tearDown()
    {
        $this->dropDb();
    }

    public function testCanGetThreadsByCategoryAsPaginator()
    {
        $category = $this->executor->getReferenceRepository()->getReference('category-0');
        $threads = $this->threadMapper->findByCategory($category);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $threads);
        $this->assertEquals(1, $threads->getTotalItemCount());

        $thread = $threads->getItem(1);
        $this->assertInstanceOf('ZfrForum\Entity\Thread', $thread);
        $this->assertEquals($category->getName(), $thread->getCategory()->getName());
    }

    /**
     * Finding threads by category is either :
     *  - not strict (default): given a category, find all the threads in the category BUT also all the threads of
     *    the children categories
     *  - strict: only find threads in the given categories.
     */
    public function testGetThreadsByCategoryUsingStrictSearch()
    {
        $subCategory = $this->executor->getReferenceRepository()->getReference('category-0-1');

        // Let's add a thread in this sub-category
        $user = $this->executor->getReferenceRepository()->getReference('user-0');
        $thread = new Thread();
        $thread->setTitle('Foo thread')
               ->setCreatedBy($user)
               ->setCategory($subCategory)
               ->setCreatedAt(new DateTime('now'));

        $post = new Post();
        $post->setContent('France ftw')
             ->setAuthor($user)
             ->setSentAt(new DateTime('now'));

        $thread->addPost($post);

        $this->threadMapper->create($thread);


        // The parent category should now have 2 threads
        $category = $this->executor->getReferenceRepository()->getReference('category-0');
        $this->getEntityManager()->refresh($category);

        $threads = $this->threadMapper->findByCategory($category);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $threads);
        $this->assertEquals(2, $threads->getTotalItemCount());

        // When using strict, only the one in the parent should appear
        $threads = $this->threadMapper->findByCategory($category, true);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $threads);
        $this->assertEquals(1, $threads->getTotalItemCount());
    }
}
