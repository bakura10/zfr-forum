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

namespace ZfrForum\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ZfcRbac\Identity\IdentityInterface;
use ZfrForum\Entity\UserInterface;

/**
 * A group represents different users that share the same permissions. For instance, you could create a group
 * that have access to a category. A group only have one role, whose name follows the following pattern :
 * GROUP_ROLE_id (eg. GROUP_ROLE_7)
 *
 * @ORM\Entity
 * @ORM\Table(name="Groups")
 */
class Group implements IdentityInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="ZfrForum\Entity\User")
     * @ORM\JoinTable(name="GroupsUsers")
     */
    protected $users;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get the identifier of the group
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the group
     *
     * @param  string $name
     * @return string
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this->name;
    }

    /**
     * Get the name of the group
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a new user to the group
     *
     * @param  UserInterface $user
     * @return Group
     */
    public function addUser(UserInterface $user)
    {
        $this->users->add($user);
        return $this;
    }

    /**
     * Add a collection of users to the group
     *
     * @param  Collection $users
     * @return Group
     */
    public function addUsers(Collection $users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    /**
     * Remove a user from the group
     *
     * @param UserInterface $user
     * @return Group
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * Remove a collection of users from the group
     *
     * @param Collection $users
     * @return Group
     */
    public function removeUsers(Collection $users)
    {
        foreach ($users as $user) {
            $this->removeUser($user);
        }

        return $this;
    }

    /**
     * Set new users to the group
     *
     * @param  Collection $users
     * @return Group
     */
    public function setUsers(Collection $users)
    {
        $this->users->clear();
        $this->addUsers($users);

        return $this;
    }

    /**
     * Get the users of the group
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get the roles for a given group (in the case of a group, there is only one role, that is the
     * name of the group)
     *
     * @return array
     */
    public function getRoles()
    {
        return array($this->canonicalizeRole($this->id));
    }

    /**
     * Transform the group name to a group role name (this is just convention used to avoid name clashes)
     *
     * @param  string $role
     * @return string
     */
    protected function canonicalizeRole($role)
    {
        return 'GROUP_ROLE_' . strtoupper(str_replace(' ', '_', $role));
    }
}