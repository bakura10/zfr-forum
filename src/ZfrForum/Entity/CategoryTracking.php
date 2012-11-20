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
use ZfrForum\Entity\Category;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\CategoryTrackingRepository")
 * @ORM\Entity
 * @ORM\Table(name="CategoriesTracking")
 */
class CategoryTracking
{
    /**
     * @var UserInterface
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\UserInterface")
     */
    protected $user;

    /**
     * @var Category
     *
     * @ORM\Id
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
     * @param Category      $category
     */
    function __construct(UserInterface $user, Category $category)
    {
        $this->category = $category;
        $this->user = $user;
        $this->markTime = new DateTime('now');
    }


    /**
     * Set when all thread of the category was read
     *
     * @param  DateTime $markTime
     * @return CategoryTracking
     */
    public function setMarkTime(DateTime $markTime)
    {
        $this->markTime = clone $markTime;
        return $this;
    }

    /**
     * Return the markTime
     *
     * @return DateTime
     */
    public function getMarkTime()
    {
        return clone $this->markTime;
    }

    /**
     * Return the category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
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
}
