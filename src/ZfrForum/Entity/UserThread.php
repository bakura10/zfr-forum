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

namespace ZfrForum\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="UsersThreads")
 */
class UserThread
{
    /**
     * @var UserInterface
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $user;

    /**
     * @var Post
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Post")
     */
    protected $post;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Post")
     */
    protected $firstUnreadPost;


    /**
     * Constructor
     *
     * @param UserInterface $user
     * @param Post          $post
     */
    public function __construct(UserInterface $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Get the user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the first unread post by the user in this thread
     *
     * @param  Post $firstUnreadPost
     * @return UserThread
     */
    public function setFirstUnreadPost(Post $firstUnreadPost)
    {
        $this->firstUnreadPost = $firstUnreadPost;
        return $this;
    }

    /**
     * Get the first unread post by the user in this thread
     *
     * @return Post
     */
    public function getFirstUnreadPost()
    {
        return $this->firstUnreadPost;
    }

    /**
     * Mark the thread as read by this user
     *
     * @return Post
     */
    public function markAsRead()
    {
        $this->firstUnreadPost = null;
        return $this;
    }
}
