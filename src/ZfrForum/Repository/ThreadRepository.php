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
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator;
use ZfrForum\Entity\Category;
use ZfrForum\Entity\Thread;
use ZfrForum\Mapper\ThreadMapperInterface;

class ThreadRepository extends EntityRepository implements ThreadMapperInterface
{
    /**
     * @param  Thread $thread
     * @return Thread
     */
    public function create(Thread $thread)
    {
        $em = $this->getEntityManager();
        $em->persist($thread);
        $em->flush();
    }

    /**
     * @param  Thread $thread
     * @return Thread
     */
    public function update(Thread $thread)
    {
        $this->getEntityManager()->flush($thread);
        return $thread;
    }

    /**
     * @param  Category $category
     * @return Paginator
     */
    public function findByCategory(Category $category = null)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        if ($category !== null) {
            $queryBuilder->join('t.category', 'c')
                         ->where('c.leftBound >= :leftBound')
                         ->andWhere('c.rightBound <= :rightBound')
                         ->setParameter('leftBound', $category->getLeftBound())
                         ->setParameter('rightBound', $category->getRightBound());
        }

        $paginatorAdapter = new PaginatorAdapter(new DoctrinePaginator($queryBuilder, false));

        return new Paginator($paginatorAdapter);
    }
}
