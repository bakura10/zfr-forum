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
use ZfrForum\Entity\Category;
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Thread;
use ZfrForum\Mapper\ThreadMapperInterface;

class ThreadService
{
    /**
     * @var ThreadMapperInterface
     */
    protected $threadMapper;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;


    /**
     * @param ThreadMapperInterface $threadMapper
     * @param AuthenticationService $authenticationService
     */
    public function __construct(ThreadMapperInterface $threadMapper, AuthenticationService $authenticationService)
    {
        $this->threadMapper          = $threadMapper;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param  Thread $thread
     * @param  Post   $post
     * @throws Exception\LogicException
     */
    public function addPost(Thread $thread, Post $post)
    {
        if (!$this->authenticationService->hasIdentity()) {
            throw new Exception\LogicException('A user has to be logged to add a new post');
        }

        $post->setSentAt(new DateTime('now'))
             ->setAuthor($this->authenticationService->getIdentity());

        $thread->addPost($post);

        $this->threadMapper->update($thread);
    }

    /**
     * Pin a thread
     *
     * @param Thread  $thread
     * @return Thread
     */
    public function pin(Thread $thread)
    {
        $thread->setPinned(true);
        return $this->threadMapper->update($thread);
    }

    /**
     * Unpin a thread
     *
     * @param  Thread $thread
     * @return Thread
     */
    public function unpin(Thread $thread)
    {
        $thread->setPinned(false);
        return $this->threadMapper->update($thread);
    }

    /**
     * Get a paginator for the latest threads, optionally filtered by a category
     *
     * @param  Category $category
     * @throws Exception\UnexpectedValueException
     * @return Paginator
     */
    public function getLatestThreadsByCategory(Category $category = null)
    {
        $latestThreads = $this->threadMapper->findByCategory($category);

        if (!$latestThreads instanceof Paginator) {
            throw new Exception\UnexpectedValueException(sprintf(
                'This service expects a Zend\Paginator\Paginator instance, %s received',
                get_class($latestThreads)
            ));
        }

        return $latestThreads;
    }

    /**
     * @param  int $id
     * @return Thread
     */
    public function getById($id)
    {
        return $this->threadMapper->find($id);
    }
}
