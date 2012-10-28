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

/**
 * Internally, categories are stored into a hierarchical tree in order to easily fetch threads
 * from specific categories, or from all the sub-categories of a given category...
 *
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\CategoryRepository")
 * @ORM\Table(name="Categories", indexes={
 *      @ORM\Index(name="IDX_FF3A7B97AEF225EE", columns={"leftBound"}),
 *      @ORM\Index(name="IDX_FF3A7B975DE4E6B6", columns={"rightBound"})
 * })
 */
class Category
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
    protected $parent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=1000)
     */
    protected $description = '';

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $depth = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $leftBound = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $rightBound = 2;


    /**
     * Get the identifier of the category
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the parent category (null if none)
     *
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        if ($parent !== null) {
            $this->setDepth($parent->getDepth() + 1)
                 ->setLeftBound($parent->getRightBound())
                 ->setRightBound($parent->getRightBound() + 1);
        }

        return $this;
    }

    /**
     * Get the parent category (null if none)
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return if this category has a parent category
     *
     * @return bool
     */
    public function hasParent()
    {
        return !($this->parent === null);
    }

    /**
     * Set the name of category
     *
     * @param  string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get the name of the category
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the optional description for the category
     *
     * @param  string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * Get the optional description for the category
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the depth of the category
     *
     * @param $depth
     * @return Category
     */
    public function setDepth($depth)
    {
        $this->depth = (int) $depth;
        return $this;
    }

    /**
     * Get the depth of the category
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set the left bound for the category
     *
     * @param  int $leftBound
     * @return Category
     */
    public function setLeftBound($leftBound)
    {
        $this->leftBound = (int) $leftBound;
        return $this;
    }

    /**
     * Get the left bound for the category
     *
     * @return int
     */
    public function getLeftBound()
    {
        return $this->leftBound;
    }

    /**
     * Set the right bound for the category
     *
     * @param  int $rightBound
     * @return Category
     */
    public function setRightBound($rightBound)
    {
        $this->rightBound = (int) $rightBound;
        return $this;
    }

    /**
     * Get the right bound for the category
     *
     * @return int
     */
    public function getRightBound()
    {
        return $this->rightBound;
    }

    /**
     * Returns true if the category does not have any children categories
     *
     * @return bool
     */
    public function isLeaf()
    {
        return (($this->rightBound - $this->leftBound) === 1);
    }

    /**
     * Returns the number of children categories for the category (or null if it is a leaf category)
     *
     * @return int|null
     */
    public function getChildrenCount()
    {
        if ($this->isLeaf()) {
            return null;
        }

        return ceil(($this->rightBound - $this->leftBound) / 2) - 1;
    }
}
