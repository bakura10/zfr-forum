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
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="Reports", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQ_C38372B2B6BD307F", columns={"message", "reported_by"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Report
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
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Message")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $message;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $reportedBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $reportedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $description = '';


    /**
     * Get the identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the reported message
     *
     * @param  Message $message
     * @return Report
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the reported message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the user that reported the message
     *
     * @param  UserInterface $reportedBy
     * @return Report
     */
    public function setReportedBy(UserInterface $reportedBy)
    {
        $this->reportedBy = $reportedBy;
        return $this;
    }

    /**
     * Get the user that reported the message
     *
     * @return UserInterface
     */
    public function getReportedBy()
    {
        return $this->reportedBy;
    }

    /**
     * Set when the message has been reported
     *
     * @return Report
     *
     * @ORM\PrePerist
     */
    public function setReportedAt()
    {
        $this->reportedAt = new DateTime('now');
        return $this;
    }

    /**
     * Get when the message has been reported
     *
     * @return DateTime
     */
    public function getReportedAt()
    {
        return clone $this->reportedAt;
    }
}
