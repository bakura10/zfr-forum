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
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Report;
use ZfrForum\Mapper\ReportMapperInterface;

class ReportRepository extends EntityRepository implements ReportMapperInterface
{
    /**
     * Add a new report
     *
     * @param  Report $report
     * @return Report
     */
    public function create(Report $report)
    {
        $em = $this->getEntityManager();
        $em->persist($report);
        $em->flush();

        return $report;
    }

    /**
     * Find all the reports for a given post, and return as a paginator
     *
     * @param  Post $post
     * @return Paginator
     */
    public function findByPost(Post $post)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->where('r.post = :post')
                     ->setParameter('post', $post);

        $paginatorAdapter = new PaginatorAdapter(new DoctrinePaginator($queryBuilder, false));

        return new Paginator($paginatorAdapter);
    }
}
