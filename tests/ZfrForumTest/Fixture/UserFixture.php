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
use Doctrine\Common\Persistence\ObjectManager;
use ZfrForum\Entity\User;

class UserFixture extends AbstractFixture
{
    /**
     * Number of instances to build when the fixture is loaded
     */
    const INSTANCES_COUNT = 5;

    protected $userDisplayNames = array(
        'Nicolas Sarkozy',
        'Jacques Chirac',
        'Tintin',
        'Maïté',
        'Bernard de la Villardière',
    );

    /**
     * {@inheritDoc}
     */
    function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::INSTANCES_COUNT; $i += 1) {
            $user = new User();
            $user->setLastActivityDate(new DateTime('now'))
                 ->setEmail("user-$i@gmail.com")
                 ->setDisplayName($this->userDisplayNames[$i])
                 ->setPassword('azerty');

            $manager->persist($user);
            $this->addReference("user-$i", $user);
        }

        $manager->flush();
    }
}
