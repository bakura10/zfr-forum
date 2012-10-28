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
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="Reports", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQ_C38372B2B6BD307F", columns={"post_id", "reportedBy_id"})
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Post")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $post;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\UserInterface")
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
     * Set the reported post
     *
     * @param  Post $post
     * @return Report
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get the reported post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the user that reported the post
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
     * Get the user that reported the post
     *
     * @return UserInterface
     */
    public function getReportedBy()
    {
        return $this->reportedBy;
    }

    /**
     * Set when the post has been reported
     *
     * @return Report
     *
     * @ORM\PrePersist
     */
    public function setReportedAt()
    {
        $this->reportedAt = new DateTime('now');
        return $this;
    }

    /**
     * Get when the post has been reported
     *
     * @return DateTime
     */
    public function getReportedAt()
    {
        return clone $this->reportedAt;
    }
}
