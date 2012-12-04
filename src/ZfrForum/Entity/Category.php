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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Internally, categories are stored into a hierarchical tree in order to easily fetch threads
 * from specific categories, or from all the sub-categories of a given category...
 *
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\CategoryRepository")
 * @ORM\Table(name="Categories")
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
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ZfrForum\Entity\Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $name = '';

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
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

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
     * Set the parent category (or null to remove)
     *
     * @param  Category|null $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
        $this->depth  = $parent->getDepth() + 1;

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
        return $this->parent !== null;
    }

    /**
     * Add a child category
     *
     * @param  Category $category
     * @return Category
     */
    public function addChild(Category $category)
    {
        $category->setParent($this);
        $this->children->add($category);

        return $this;
    }

    /**
     * Add children categories
     *
     * @param  Collection $children
     * @return Category
     */
    public function addChildren(Collection $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Remove a child category
     *
     * @param  Category $category
     * @return Category
     */
    public function removeChild(Category $category)
    {
        $category->setParent(null);
        $this->children->removeElement($category);

        return $this;
    }

    /**
     * Remove children categories
     *
     * @param  Collection $children
     * @return Category
     */
    public function removeChildren(Collection $children)
    {
        foreach ($children as $child) {
            $this->removeChild($child);
        }

        return $this;
    }

    /**
     * Set children categories
     *
     * @param  Collection $children
     * @return Category
     */
    public function setChildren(Collection $children)
    {
        $this->children->clear();
        $this->addChildren($children);

        return $this;
    }

    /**
     * Get the children categories
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set the position of the category according to the sibling categories
     *
     * @param  int $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
        return $this;
    }

    /**
     * Get the position of the category according to the sibling categories
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
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
     * Get the depth of the category (it begins by 1)
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }
}
