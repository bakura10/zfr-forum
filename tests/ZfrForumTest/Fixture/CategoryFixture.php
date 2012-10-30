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

namespace ZfrForumTest\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use ZfrForum\Entity\Category;

class CategoryFixture extends AbstractFixture
{
    /**
     * Number of instances to build when the fixture is loaded
     */
    const INSTANCES_COUNT = 5;

    /**
     * {@inheritDoc}
     */
    function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository('ZfrForum\Entity\Category');

        for ($i = 0; $i < self::INSTANCES_COUNT; $i += 1) {
            $category = new Category();
            $category->setName("Category $i");

            $category = $repository->create($category);
            $this->setReference("category-$i", $category);

            // Add a child category
            $child = new Category();
            $child->setName("Category $i / 1");
            $child->setParent($category);

            $child = $repository->create($child);
            $this->setReference("category-$i-1", $child);
        }

        $manager->flush();
    }
}
