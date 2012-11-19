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

namespace ZfrForum\Mapper;

use Doctrine\Common\Persistence\ObjectRepository;
use ZfrForum\Entity\CategoryTracking;
use ZfrForum\Entity\Category;
use ZfcUser\Entity\UserInterface;

interface CategoryTrackingMapperInterface extends ObjectRepository
{
    /**
     * @param  CategoryTracking $categoryTracking
     * @return CategoryTracking
     */
    public function add(CategoryTracking $categoryTracking);

    /**
     * @param  CategoryTracking $categoryTracking
     * @return CategoryTracking
     */
    public function update(CategoryTracking $categoryTracking);

    /**
     * @param  CategoryTracking $categoryTracking
     * @return void
     */
    public function remove(CategoryTracking $categoryTracking);

    /**
     * @param  Category      $category
     * @param  UserInterface $user
     * @return CategoryTracking
     */
    public function findByCategoryAndUser(Category $category, UserInterface $user);
}
