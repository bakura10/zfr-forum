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

namespace ZfrForum\Service;

use DateTime;
use Zend\Authentication\AuthenticationService;
use Zend\Paginator\Paginator;
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Report;
use ZfrForum\Entity\UserInterface;
use ZfrForum\Mapper\PostMapperInterface;
use ZfrForum\Mapper\ReportMapperInterface;

class PostService
{
    /**
     * @var PostMapperInterface
     */
    protected $postMapper;

    /***
     * @var ReportMapperInterface
     */
    protected $reportMapper;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;


    /**
     * @param PostMapperInterface   $postMapper
     * @param ReportMapperInterface $reportMapper
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        PostMapperInterface $postMapper,
        ReportMapperInterface $reportMapper,
        AuthenticationService $authenticationService
    )
    {
        $this->postMapper            = $postMapper;
        $this->reportMapper          = $reportMapper;
        $this->authenticationService = $authenticationService;
    }

    /**
     * Report a post
     *
     * @param  Post   $post
     * @param  string $description
     * @throws Exception\LogicException
     * @return void
     */
    public function report(Post $post, $description)
    {
        if (!$this->authenticationService->hasIdentity()) {
            throw new Exception\LogicException('A user has to be logged to report a post');
        }

        $user = $this->authenticationService->getIdentity();

        // TODO: make this an option like allow_multiple_reports
        if (!$this->isReportedByUser($post, $user)) {
            $report = new Report();
            $report->setPost($post)
                   ->setReportedBy($user)
                   ->setReportedAt(new DateTime('now'))
                   ->setDescription($description);

            $this->reportMapper->create($report);
        }
    }

    /**
     * Return true if the given post is already reported by the user
     *
     * @param Post          $post
     * @param UserInterface $user
     * @return boolean
     */
    public function isReportedByUser(Post $post, UserInterface $user)
    {
        $report = $this->reportMapper->findOneBy(array('post' => $post, 'reportedBy' => $user));
        return $report !== null;
    }

    /**
     * Get all the reports for a given post
     *
     * @param  Post $post
     * @throws Exception\UnexpectedValueException
     * @return Paginator
     */
    public function getReports(Post $post)
    {
        $reports = $this->reportMapper->findByPost($post);

        if (!$reports instanceof Paginator) {
            throw new Exception\UnexpectedValueException(sprintf(
                '%s method expects a Zend\Paginator\Paginator instance, %s received',
                __FUNCTION__,
                get_class($reports)
            ));
        }

        return $reports;
    }

    /**
     * @param  int $id
     * @return Post
     */
    public function getById($id)
    {
        return $this->postMapper->find($id);
    }
}
