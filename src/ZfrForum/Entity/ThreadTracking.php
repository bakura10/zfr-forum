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
use ZfrForum\Entity\Thread;
use ZfrForum\Entity\Category;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\ThreadTrackingRepository")
 * @ORM\Entity
 * @ORM\Table(name="ThreadsTracking")
 */
class ThreadTracking
{
    /**
     * @var UserInterface
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\UserInterface")
     */
    protected $user;

    /**
     * @var Thread
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Thread")
     */
    protected $thread;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Category")
     */
    protected $category;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $markTime;

    
    /**
     * Constructor
     *
     * @param UserInterface $user
     * @param Thread        $thread
     */
    function __construct(UserInterface $user, Thread $thread)
    {
        $this->user     = $user;
        $this->thread   = $thread;
        $this->category = $thread->getCategory();
        $this->markTime = $thread->getLastPost()->getSentAt();
    }

    /**
     * Return the user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return the thread
     *
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set the category of the thread
     *
     * @param  Category $category
     * @return ThreadTracking
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Return the category of the thread
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the created date of the lasted post of the thread
     *
     * @param  DateTime $markTime
     * @return ThreadTracking
     */
    public function setMarkTime(DateTime $markTime)
    {
        $this->markTime = clone $markTime;
        return $this;
    }

    /**
     * Return the date of the lasted post of the thread
     * @return DateTime
     */
    public function getMarkTime()
    {
        return clone $this->markTime;
    }
}
