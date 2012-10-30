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

namespace ZfrForumTest\Repository;

use ZfrForum\Entity\Category;
use ZfrForum\Mapper\CategoryMapperInterface;
use ZfrForumTest\ServiceManagerTestCase;

class CategoryRepositoryTest extends ServiceManagerTestCase
{
    /**
     * @var CategoryMapperInterface
     */
    protected $categoryMapper;

    public function setUp()
    {
        $this->createDb();
        $this->categoryMapper = self::getServiceManager()->get('ZfrForum\Mapper\CategoryMapperInterface');
    }

    public function tearDown()
    {
        $this->dropDb();
    }

    /**
     * @param  string $name
     * @param  Category $parent
     * @return Category
     */
    protected function createCategory($name, Category $parent = null)
    {
        if ($parent === null) {
            $parent = $this->categoryMapper->findRoot();
        }

        $category = new Category();
        $category->setName($name)
                 ->setParent($parent);

        return $category;
    }

    public function testCanPersistRootCategory()
    {
        $category = $this->createCategory('Foo');
        $this->categoryMapper->create($category);

        $this->assertInternalType('integer', $category->getId());
        $this->assertEquals(2, $category->getLeftBound());
        $this->assertEquals(3, $category->getRightBound());
        $this->assertTrue($category->isLeaf());
        $this->assertEquals(0, $category->getChildrenCount());
    }

    public function testCanPersistMultipleRootCategories()
    {
        $firstRoot = $this->createCategory('Foo');
        $firstRoot = $this->categoryMapper->create($firstRoot);

        $this->assertInternalType('integer', $firstRoot->getId());
        $this->assertEquals(2, $firstRoot->getLeftBound());
        $this->assertEquals(3, $firstRoot->getRightBound());

        $secondRoot = $this->createCategory('Bar');
        $secondRoot = $this->categoryMapper->create($secondRoot);

        $this->assertInternalType('integer', $secondRoot->getId());
        $this->assertEquals(4, $secondRoot->getLeftBound());
        $this->assertEquals(5, $secondRoot->getRightBound());
    }

    public function testCanAddNewCategory()
    {
        // Root category (left bound = 2, right bound = 3)
        $category = $this->createCategory('Foo');
        $category = $this->categoryMapper->create($category);

        // Node category whose parent is previous category (left bound = 3, right bound = 4)
        $childCategory = $this->createCategory("Foo's child", $category);
        $childCategory = $this->categoryMapper->create($childCategory);

        $this->assertInternalType('integer', $childCategory->getId());
        $this->assertEquals(3, $childCategory->getLeftBound());
        $this->assertEquals(4, $childCategory->getRightBound());
        $this->assertTrue($childCategory->isLeaf());

        // Root category should now have left bound = 2, right bound = 5
        $category = $this->categoryMapper->find($category->getId());
        $this->assertInternalType('integer', $category->getId());
        $this->assertEquals(2, $category->getLeftBound());
        $this->assertEquals(5, $category->getRightBound());
        $this->assertFalse($category->isLeaf());
        $this->assertEquals(1, $category->getChildrenCount());
    }

    public function testCanAddMultipleCategories()
    {
        // Root category (left bound = 2, right bound = 3)
        $category = $this->createCategory('Foo');
        $category = $this->categoryMapper->create($category);

        // Node category whose parent is previous category (left bound = 3, right bound = 4)
        $firstChild = $this->createCategory("Foo's First Child", $category);
        $this->categoryMapper->create($firstChild);

        $category = $this->categoryMapper->find($category->getId());

        // Node category whose parent is previous category (left bound = 5, right bound = 6)
        $secondChild = $this->createCategory("Foo's Second Child", $category);
        $this->categoryMapper->create($secondChild);

        // Root category should now have left bound = 1, right bound = 6
        $category = $this->categoryMapper->find($category->getId());
        $this->assertInternalType('integer', $category->getId());
        $this->assertEquals(2, $category->getLeftBound());
        $this->assertEquals(7, $category->getRightBound());
        $this->assertFalse($category->isLeaf());
        $this->assertEquals(2, $category->getChildrenCount());
    }

    public function testCanRemoveCategory()
    {
        // Root category (left bound = 2, right bound = 3)
        $category = $this->createCategory('Foo');
        $category = $this->categoryMapper->create($category);

        // Node category whose parent is previous category (left bound = 3, right bound = 4)
        $childCategory = $this->createCategory("Foo's child", $category);
        $childCategory = $this->categoryMapper->create($childCategory);

        $this->categoryMapper->remove($childCategory);

        // Root category now have again left bound = 2, right bound = 3
        $category = $this->categoryMapper->find($category->getId());

        $this->assertEquals(2, $category->getLeftBound());
        $this->assertEquals(3, $category->getRightBound());
        $this->assertTrue($category->isLeaf());
        $this->assertEquals(0, $category->getChildrenCount());
    }

    public function testCanRemoveChildCategoryWhenParentNodeHaveMultipleCategories()
    {
        // Root category (left bound = 2, right bound = 3)
        $category = $this->createCategory('Foo');
        $category = $this->categoryMapper->create($category);

        // Node category whose parent is previous category (left bound = 3, right bound = 4)
        $firstChild = $this->createCategory("Foo's First Child", $category);
        $firstChild = $this->categoryMapper->create($firstChild);

        $category = $this->categoryMapper->find($category->getId());

        // Node category whose parent is previous category (left bound = 5, right bound = 6)
        $secondChild = $this->createCategory("Foo's Second Child", $category);
        $secondChild = $this->categoryMapper->create($secondChild);

        $firstChild = $this->categoryMapper->find($firstChild->getId());
        $this->categoryMapper->remove($firstChild);

        $secondChild = $this->categoryMapper->find($secondChild->getId());
        $this->assertEquals(3, $secondChild->getLeftBound());
        $this->assertEquals(4, $secondChild->getRightBound());
        $this->assertTrue($secondChild->isLeaf());
        $this->assertEquals(0, $secondChild->getChildrenCount());

        $category = $this->categoryMapper->find($category->getId());
        $this->assertEquals(2, $category->getLeftBound());
        $this->assertEquals(5, $category->getRightBound());
        $this->assertFalse($category->isLeaf());
        $this->assertEquals(1, $category->getChildrenCount());
    }
}
