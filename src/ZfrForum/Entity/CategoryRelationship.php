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
 * This entity provides a way to efficiently fetch all the threads that belongs to a given category, or all
 * the sub-categories of a given category. It differs from the nested set model as it does not use left and right
 * bounds (there is therefore not any RANGE query by SQL). This model also allows to have multiple root nodes without
 * too many hacks.
 *
 * It works like this : given category 1, which has a category 2 as child, which itself has a category 3 as child,
 * the following rows will be created : (1, 1) ; (2, 1) ; (3, 1) ; (2, 2) ; (3, 2) ; (3, 3), where the two PK are
 * (category_id, parent_category_id). Please note that each category also has a reference to itself.
 *
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\CategoryRelationshipRepository")
 * @ORM\Table(name="CategoriesRelationships")
 */
class CategoryRelationship
{
    /**
     * @var Category
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Category")
     */
    protected $category;

    /**
     * @var Category
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ZfrForum\Entity\Category")
     */
    protected $parentCategory;


    /**
     * @param Category $category
     * @param Category $parentCategory
     */
    public function __construct(Category $category, Category $parentCategory)
    {
        $this->category       = $category;
        $this->parentCategory = $parentCategory;
    }

    /**
     * Get the category in the relationship
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get the parent category in the relationship
     *
     * @return Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }
}
