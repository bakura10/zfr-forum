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
use ZfrForum\Entity\Report;
use ZfrForum\Mapper\PostMapperInterface;
use ZfrForum\Mapper\ReportMapperInterface;
use ZfrForumTest\Fixture;
use ZfrForumTest\ServiceManagerTestCase;

class PostRepositoryTest extends ServiceManagerTestCase
{
    /**
     * @var PostMapperInterface
     */
    protected $postMapper;

    /**
     * @var ReportMapperInterface
     */
    protected $reportMapper;

    /**
     * @var ORMExecutor
     */
    protected $executor;

    public function setUp()
    {
        $this->createDb();
        $this->postMapper = self::getServiceManager()->get('ZfrForum\Mapper\PostMapperInterface');
        $this->reportMapper = self::getServiceManager()->get('ZfrForum\Mapper\ReportMapperInterface');

        // Init the fixture
        $loader = new FixtureLoader();
        $loader->addFixture(new Fixture\CategoryFixture());
        $loader->addFixture(new Fixture\UserFixture());
        $loader->addFixture(new Fixture\ThreadFixture());
        $purger = new ORMPurger();
        $em = self::getServiceManager()->get('Doctrine\ORM\EntityManager');
        $this->executor = new ORMExecutor($em, $purger);
        $this->executor->execute($loader->getFixtures());
    }

    public function tearDown()
    {
        $this->dropDb();
    }

    public function testCanReportAPost()
    {
        $repository = $this->executor->getReferenceRepository();
        $post = $repository->getReference('thread-0')->getLastPost();
        $reportedBy = $repository->getReference('user-1');

        $report = new Report();
        $report->setPost($post)
               ->setDescription('This post is spam !')
               ->setReportedBy($reportedBy)
               ->setReportedAt(new DateTime('now'));

        $this->reportMapper->create($report);

        $this->assertInternalType('integer', $report->getId());

        /** @var \Zend\Paginator\Paginator $reports */
        $reports = $this->reportMapper->findByPost($post);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $reports);
        $this->assertEquals(1, $reports->getTotalItemCount());


        // Let's add another report
        $report = new Report();
        $report->setPost($post)
               ->setDescription('This post is REALLY a spam !')
               ->setReportedBy($repository->getReference('user-2'))
               ->setReportedAt(new DateTime('now'));

        $this->reportMapper->create($report);

        $reports = $this->reportMapper->findByPost($post);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $reports);
        $this->assertEquals(2, $reports->getTotalItemCount());
    }

    public function testThrowExceptionWhenTheSameUserReportTheSamePostTwice()
    {
        $repository = $this->executor->getReferenceRepository();
        $post = $repository->getReference('thread-0')->getLastPost();
        $reportedBy = $repository->getReference('user-1');

        $report = new Report();
        $report->setPost($post)
            ->setDescription('This post is spam !')
            ->setReportedBy($reportedBy)
            ->setReportedAt(new DateTime('now'));

        $this->reportMapper->create($report);

        $this->assertInternalType('integer', $report->getId());

        /** @var \Zend\Paginator\Paginator $reports */
        $reports = $this->reportMapper->findByPost($post);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $reports);
        $this->assertEquals(1, $reports->getTotalItemCount());


        // Let's try to add another report (of course, this is handled at a higher level in
        // the service)
        $report = new Report();
        $report->setPost($post)
            ->setDescription('This post is REALLY a spam !')
            ->setReportedBy($reportedBy)
            ->setReportedAt(new DateTime('now'));

        $this->setExpectedException('Doctrine\DBAL\DBALException');
        $this->reportMapper->create($report);
    }
}
