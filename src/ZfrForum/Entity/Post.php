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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ZfrForum\Entity\UserInterface;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\PostRepository")
 * @ORM\Table(name="Posts")
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\UserInterface")
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $authorDisplayName;

    /**
     * @var Thread
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Thread", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $thread;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $sentAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastModifiedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $countModified = 0;


    /**
     * Get the identifier of the post
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the author of the post
     *
     * @param  UserInterface $author
     * @return Post
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        $this->setAuthorDisplayName($author->getDisplayName());

        return $this;
    }

    /**
     * Get the author of the post
     *
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the author display name. This is useful if the author's account is deleted or banned, so that
     * we can still see who wrote the post
     *
     * @param  string $authorDisplayName
     * @return Post
     */
    public function setAuthorDisplayName($authorDisplayName)
    {
        $this->authorDisplayName = (string) $authorDisplayName;
        return $this;
    }

    /**
     * Get the author display name
     *
     * @return string
     */
    public function getAuthorDisplayName()
    {
        return $this->authorDisplayName;
    }

    /**
     * Set the thread this post belongs to
     *
     * @param  Thread $thread
     * @return Post
     */
    public function setThread(Thread $thread)
    {
        $this->thread = $thread;
        return $this;
    }

    /**
     * Get the thread this post belongs to
     *
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set the content of the post
     *
     * @param  string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }

    /**
     * Get the content of the post
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set when this post was sent for the first time
     *
     * @return Post
     *
     * @ORM\PrePersist
     */
    public function setSentAt()
    {
        $this->sentAt = new DateTime('now');
        return $this;
    }

    /**
     * Get when this post was sent for the first time
     *
     * @return DateTime
     */
    public function getSentAt()
    {
        return clone $this->sentAt;
    }

    /**
     * Set the last time that the post was modified
     *
     * @return Post
     *
     * @ORM\PreUpdate
     */
    public function setLastModifiedAt()
    {
        $this->lastModifiedAt = new DateTime('now');
        $this->countModified  += 1;

        return $this;
    }

    /**
     * Get the last time the post was modified
     *
     * @return DateTime
     */
    public function getLastModifiedAt()
    {
        return clone $this->lastModifiedAt;
    }

    /**
     * Get the number of times the post has been modified
     *
     * @return int
     */
    public function getCountModified()
    {
        return $this->countModified;
    }
}
