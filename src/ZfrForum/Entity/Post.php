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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use ZfrForum\Entity\UserInterface;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\PostRepository")
 * @ORM\Table(name="Posts")
 */
class Post implements JsonSerializable
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
     * @ORM\JoinColumn(referencedColumnName="user_id")
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastModifiedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $countModified = 0;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ZfrForum\Entity\Post", mappedBy="answerOf")
     */
    protected $answers;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Post", inversedBy="answers")
     */
    protected $answerOf;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

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
     * @param  DateTime $sentAt
     * @return Post
     */
    public function setSentAt(DateTime $sentAt)
    {
        $this->sentAt = clone $sentAt;
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
     * @param DateTime $lastModifiedAt
     * @return Post
     */
    public function setLastModifiedAt(DateTime $lastModifiedAt)
    {
        $this->lastModifiedAt = clone $lastModifiedAt;
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
        if ($this->lastModifiedAt) {
            return clone $this->lastModifiedAt;
        }

        return null;
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

    /**
     * Add an answer to this post
     *
     * @param Post $post
     * @return Post
     */
    public function addAnswer(Post $post)
    {
        $post->setAnswerOf($this);
        $this->answers->add($post);

        return $this;
    }

    /**
     * Add a collection of answers
     *
     * @param  Collection $posts
     * @return Post
     */
    public function addAnswers(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addAnswer($post);
        }

        return $this;
    }

    /**
     * Remove an answer
     *
     * @param  Post $post
     * @return Post
     */
    public function removeAnswer(Post $post)
    {
        $this->answers->remove($post);
        return $this;
    }

    /**
     * Remove a collection of answers
     *
     * @param  Collection $posts
     * @return Post
     */
    public function removeAnswers(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removeAnswer($post);
        }

        return $this;
    }

    /**
     * Add a collection of answers to the post, removing the old ones
     *
     * @param Collection $posts
     * @return Post
     */
    public function setAnswers(Collection $posts)
    {
        $this->answers->clear();
        $this->addAnswers($posts);

        return $this;
    }

    /**
     * Get all the answers to this post
     *
     * @return Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set the post this post is answering (this is similar as quoting)
     *
     * @param Post $post
     * @return Post
     */
    public function setAnswerOf(Post $post)
    {
        $this->answerOf = $post;
        return $this;
    }

    /**
     * Get the post this post is answering (null if none)
     *
     * @return Post
     */
    public function getAnswerOf()
    {
        return $this->answerOf;
    }

    /**
     * (PHP 5 >= 5.4.0)
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @link http://docs.php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed Returns data which can be serialized by json_encode(), which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return array(
            'id'     => $this->getId(),
            'author' => array(
                'id'          => $this->getAuthor()->getId(),
                'displayName' => $this->getAuthorDisplayName()
            ),
            'thread' => array(
                'id' => $this->getThread()->getId()
            ),
            'content' => $this->getContent()
        );
    }
}
