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
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Report;
use ZfrForum\Mapper\PostMapperInterface;

class PostService
{
    /**
     * @var PostMapperInterface
     */
    protected $postMapper;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;


    /**
     * @param PostMapperInterface   $postMapper
     * @param AuthenticationService $authenticationService
     */
    public function __construct(PostMapperInterface $postMapper, AuthenticationService $authenticationService)
    {
        $this->postMapper            = $postMapper;
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

        $report = new Report();
        $report->setPost($post)
               ->setReportedBy($this->authenticationService->getIdentity())
               ->setReportedAt(new DateTime('now'))
               ->setDescription($description);

        $this->postMapper->addReport($report);
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
