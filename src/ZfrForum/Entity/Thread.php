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
use Doctrine\ORM\Mapping;
use ZfcUser\Entity\UserInterface;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\ThreadRepository")
 * @ORM\Table(name="Threads")
 * @ORM\HasLifecycleCallbacks
 */
class Thread
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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Category")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $title;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $createdBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ZfrForum\Entity\Post", mappedBy="thread", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"sentAt"="ASC"})
     */
    protected $posts;

    /**
     * @var Post
     *
     * @ORM\OneToOne
     */
    protected $lastPost;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="ZfcUser\Entity\UserInterface", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="ThreadsFollowers")
     */
    protected $followers;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $closed = false;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts     = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }

    /**
     * Get the identifier of the thread
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the category this thread belongs to
     *
     * @param  Category $category
     * @return Thread
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get the category this thread belongs to
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the title of the thread
     * 
     * @param string $title
     * @return Thread 
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * Get the title of the thread
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the user that created the thread
     *
     * @param  UserInterface $createdBy
     * @return Thread
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
        $this->addFollower($createdBy);

        return $this;
    }

    /**
     * Get the user that created the thread
     *
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set when this thread was created
     *
     * @return Thread
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime('now');
        return $this;
    }

    /**
     * Get when this thread was created
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return clone $this->createdAt;
    }

    /**
     * Add a new post to a thread
     *
     * @param  Post $post
     * @return Thread
     */
    public function addPost(Post $post)
    {
        $post->setThread($this);

        $this->posts->add($post);
        $this->setLastPost($post);

        return $this;
    }

    /**
     * Add a collection of posts to the thread
     *
     * @param  Collection $posts
     * @return Thread
     */
    public function addPosts(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }

        return $this;
    }

    /**
     * Remove a post
     *
     * @param  Post $post
     * @return Thread
     */
    public function removePost(Post $post)
    {
        $this->posts->remove($post);
        return $this;
    }

    /**
     * Remove a collection of posts from the thread
     *
     * @param  Collection $posts
     * @return Thread
     */
    public function removePosts(Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removePost($post);
        }

        return $this;
    }

    /**
     * Set posts to the thread
     *
     * @param  Collection $posts
     * @return Thread
     */
    public function setPosts(Collection $posts)
    {
        $this->posts->clear();
        $this->addPosts($posts);

        return $this;
    }

    /**
     * Get the posts from the thread
     *
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set the last post of the thread
     *
     * @param  Post $lastPost
     * @return Thread
     */
    public function setLastPost(Post $lastPost)
    {
        $this->lastPost = $lastPost;
        return $this;
    }

    /**
     * Get the last post of the thread
     *
     * @return Post
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Add a new follower to a thread
     *
     * @param  UserInterface $user
     * @return Thread
     */
    public function addFollower(UserInterface $user)
    {
        $this->followers->add($user);
        return $this;
    }

    /**
     * Remove a follower
     *
     * @param  UserInterface $user
     * @return Thread
     */
    public function removeFollower(UserInterface $user)
    {
        $this->followers->remove($user);
        return $this;
    }

    /**
     * Get the followers of the thread
     * 
     * @return Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set if the thread is closed
     *
     * @param  boolean $closed
     * @return Thread
     */
    public function setClosed($closed)
    {
        $this->closed = (boolean) $closed;
        return $this;
    }

    /**
     * Returns true if the thread is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->closed;
    }

}
