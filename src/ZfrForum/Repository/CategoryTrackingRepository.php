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

use ZfrForum\Entity\CategoryTracking;
use ZfrForum\Mapper\CategoryTrackingMapperInterface;

class CategoryTrackingRepository extends EntityRepository implements CategoryTrackingMapperInterface
{
    /**
     * @param  CategoryTracking $categoryTracking
     * @return CategoryTracking
     */
    public function create(CategoryTracking $categoryTracking)
    {
        // TODO: Implement add() method.
    }

    /**
     * @param  CategoryTracking $categoryTracking
     * @return CategoryTracking
     */
    public function update(CategoryTracking $categoryTracking)
    {
        $this->getEntityManager()->flush($categoryTracking);
        return $categoryTracking;
    }

    /**
     * @param  CategoryTracking $categoryTracking
     * @return void
     */
    public function remove(CategoryTracking $categoryTracking)
    {
        // TODO: Implement remove() method.
    }
}
