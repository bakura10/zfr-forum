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

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ZfrForum\Entity\Post;
use ZfrForum\Entity\Thread;

class ThreadFixture extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * Number of instances to build when the fixture is loaded
     */
    const INSTANCES_COUNT = 5;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return array(
            'ZfrForumTest\Fixture\UserFixture',
            'ZfrForumTest\Fixture\CategoryFixture'
        );
    }

    /**
     * {@inheritDoc}
     */
    function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::INSTANCES_COUNT; $i += 1) {
            $user = $this->getReference("user-$i");
            $category = $this->getReference("category-$i");

            $thread = new Thread();
            $thread->setTitle('Foo thread')
                   ->setCategory($category)
                   ->setCreatedBy($user)
                   ->setCreatedAt(new DateTime('now'));

            $post = new Post();
            $post->setContent('France ftw')
                 ->setAuthor($user)
                 ->setSentAt(new DateTime('now'));

            $thread->addPost($post);

            $manager->persist($thread);
            $this->setReference("thread-$i", $thread);
        }

        $manager->flush();
    }
}
