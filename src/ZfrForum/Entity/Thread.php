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
    protected $name;

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
     * @ORM\OneToMany(targetEntity="ZfrForum\Entity\Message", mappedBy="thread", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"sentAt"="ASC"})
     */
    protected $messages;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isClosed = false;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * Set the user that created the thread
     *
     * @param  UserInterface $createdBy
     * @return Thread
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
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
     * Add a new message to a thread
     *
     * @param  Message $message
     * @return Thread
     */
    public function addMessage(Message $message)
    {
        $message->setThread($this);
        $this->messages->add($message);

        return $this;
    }

    /**
     * Add a collection of messages to the thread
     *
     * @param  Collection $messages
     * @return Thread
     */
    public function addMessages(Collection $messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Remove a message (by reference or by a key)
     *
     * @param  Message|int $messageOrKey
     * @return Thread
     */
    public function removeMessage($messageOrKey)
    {
        if ($messageOrKey instanceof Message) {
            $this->messages->removeElement($messageOrKey);
        } else {
            $this->messages->remove($messageOrKey);
        }

        return $this;
    }

    /**
     * Remove a collection of messages from the thread
     *
     * @param  Collection $messages
     * @return Thread
     */
    public function removeMessages(Collection $messages)
    {
        foreach ($messages as $message) {
            $this->removeMessage($message);
        }

        return $this;
    }

    /**
     * Set messages to the thread
     *
     * @param  Collection $messages
     * @return Thread
     */
    public function setMessages(Collection $messages)
    {
        $this->messages->clear();
        $this->addMessages($messages);

        return $this;
    }

    /**
     * Get the messages from the thread
     *
     * @return Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set if the thread is closed
     *
     * @param  boolean $isClosed
     * @return Thread
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = (bool) $isClosed;
        return $this;
    }

    /**
     * Returns true if the thread is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->isClosed;
    }
}
