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

namespace ZfrForum\Service;

use ZfrForum\Entity\Category;
use ZfrForum\Mapper\CategoryMapperInterface;

class CategoryService
{
    /**
     * @var CategoryMapperInterface
     */
    protected $categoryMapper;


    /**
     * @param CategoryMapperInterface $categoryMapper
     */
    public function __construct(CategoryMapperInterface $categoryMapper)
    {
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * Create a new category
     *
     * @param  Category $category
     * @throws Exception\DomainException
     * @return void
     */
    public function create(Category $category)
    {
        if ($category->getName() === '' || $category->getName() === null) {
            throw new Exception\DomainException('A category must have a name, but none was given');
        }

        $category = $this->categoryMapper->create($category);
    }

    /**
     * Update an existing category
     *
     * @param Category $category
     * @return void
     */
    public function update(Category $category)
    {
        $this->categoryMapper->update($category);
    }

    /**
     * Remove an existing category
     *
     * @param Category $category
     * @return void
     */
    public function remove(Category $category)
    {
        $this->categoryMapper->remove($category);
    }

    /**
     * @param  int $id
     * @return Category
     */
    public function getById($id)
    {
        return $this->categoryMapper->find($id);
    }
}
