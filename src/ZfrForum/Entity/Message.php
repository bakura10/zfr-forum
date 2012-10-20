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
use ZfcUser\Entity\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Messages")
 * @ORM\HasLifecycleCallbacks
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $authorDisplayName;

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
    protected $createdAt;

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
     * Get the unique identifier of the message
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the author of the message
     *
     * @param UserInterface $author
     * @return Message
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get the author of the message
     *
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the author display name. This is useful if the author's account is deleted or banned, so that
     * we can still see who wrote the message
     *
     * @param  string $authorDisplayName
     * @return Message
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
     * Set the content of the message
     *
     * @param  string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }

    /**
     * Get the content of the message
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the date when this message was created
     *
     * @return Message
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime('now');
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return clone $this->createdAt;
    }

    /**
     * Set the last time that the message was modified
     *
     * @return Message
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
     * Get the last time the message was modified
     *
     * @return DateTime
     */
    public function getLastModifiedAt()
    {
        return clone $this->lastModifiedAt;
    }

    /**
     * Get the number of times the message has been modified
     *
     * @return int
     */
    public function getCountModified()
    {
        return $this->countModified;
    }
}
