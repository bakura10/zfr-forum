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

namespace ZfrForum\Repository;

use Doctrine\ORM\EntityRepository;
use ZfrForum\Entity\Category;
use ZfrForum\Mapper\CategoryMapperInterface;

class CategoryRepository extends EntityRepository implements CategoryMapperInterface
{
    /**
     * Note : to efficiently create a category, we perform an UPDATE at SQL level. To work correctly with
     * Doctrine 2, we need to clear the entity manager at the end of the operation. As a consequence, you need
     * to re-load all the entities affected by this operation.
     *
     * @param  Category $category
     * @return Category
     */
    public function create(Category $category)
    {
        $em = $this->getEntityManager();

        if ($category->hasParent()) {
            $queryBuilder = $em->createQueryBuilder();

            // First update right bounds
            $queryBuilder->update('ZfrForum\Entity\Category', 'c')
                         ->set('c.rightBound', 'c.rightBound + 2')
                         ->where('c.rightBound >= :rightBound')
                         ->setParameter('rightBound', $category->getParent()->getRightBound())
                         ->getQuery()->execute();

            // Then left bounds
            $queryBuilder->resetDQLParts(array('set', 'where'))
                         ->set('c.leftBound', 'c.leftBound + 2')
                         ->where('c.leftBound >= :rightBound')
                         ->getQuery()->execute();
        }

        // Finally, add the category
        $em->persist($category);
        $em->flush();

        // Clear all the categories (see the note above)
        $em->clear('ZfrForum\Entity\Category');

        return $em->merge($category);
    }

    /**
     * @param  Category $category
     * @return Category
     */
    public function update(Category $category)
    {
        // TODO: Implement update() method.
    }

    /**
     * Note : to efficiently remove a category, we perform an UPDATE at SQL level. To work correctly with
     * Doctrine 2, we need to clear the entity manager at the end of the operation. As a consequence, you need
     * to re-load all the entities affected by this operation.
     *
     * @param  Category $category
     * @return void
     */
    public function remove(Category $category)
    {
        $em = $this->getEntityManager();

        if ($category->hasParent()) {
            $queryBuilder = $em->createQueryBuilder();

            // First update left bounds
            $queryBuilder->update('ZfrForum\Entity\Category', 'c')
                         ->set('c.leftBound', 'c.leftBound - 2')
                         ->where('c.leftBound >= :leftBound')
                         ->setParameter('leftBound', $category->getLeftBound())
                         ->getQuery()->execute();

            // Then right bounds
            $queryBuilder->resetDQLParts(array('set', 'where'))
                         ->set('c.rightBound', 'c.rightBound - 2')
                         ->setParameter('t', 't')
                         ->where('c.rightBound >= :leftBound')
                         ->getQuery()->execute();
        }

        // Finally, remove the entity
        $em->remove($category);
        $em->flush();

        // Clear all the categories (see the note above)
        $em->clear('ZfrForum\Entity\Category');
    }
}
