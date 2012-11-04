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

use Doctrine\ORM\Mapping as ORM;

/**
 * In RBAC model, a role gives access to zero, one or many permissions. In the context of the forum, two entities
 * can have roles : users and groups. A user may have multiples roles, while a group only have one role, named after
 * the name of the group.
 *
 * A role name for a user follows the following pattern : ROLE_TYPE (eg. ROLE_MODERATOR). On the other hand, a role
 * name for a group follows the following pattern : GROUP_ROLE_GROUPNAME (eg. GROUP_ROLE_MY_SUPER_GROUP)
 *
 * This model allows to finely tuned the access of your forum. By default, four roles are created out-of-the-box
 * for you, and have a pre-defined set of permissions :
 *
 *      - PERMISSION_CAN_READ_THREADS
 *      - PERMISSION_CAN_CREATE_THREADS
 *      - PERMISSION_CAN_ADD_REPLIES
 *      - PERMISSION_CAN_EDIT_POSTS
 *      - PERMISSION_CAN_DELETE_THREADS
 *      - PERMISSION_CAN_DELETE_POSTS
 *
 *      - ROLE_ADMINISTRATOR : PERMISSION_CAN_READ_THREADS, PERMISSION_CAN_CREATE_THREADS, PERMISSION_CAN_ADD_REPLIES,
 *                             PERMISSION_CAN_EDIT_POSTS, PERMISSION_CAN_DELETE_THREADS, PERMISSION_CAN_DELETE_POSTS
 *
 *      - ROLE_MODERATOR : PERMISSION_CAN_READ_THREADS, PERMISSION_CAN_CREATE_THREADS, PERMISSION_CAN_ADD_REPLIES,
 *                         PERMISSION_CAN_EDIT_POSTS, PERMISSION_CAN_DELETE_THREADS, PERMISSION_CAN_DELETE_POSTS
 *
 *      - ROLE_MEMBER : PERMISSION_CAN_READ_THREADS, PERMISSION_CAN_CREATE_THREADS, PERMISSION_CAN_ADD_REPLIES
 *
 *      - ROLE_GUEST : PERMISSION_CAN_READ_THREADS
 *
 * You may want to create a new role for a user. An identity (user or group) can have multiple roles. For instance,
 * let's say that you want to give a moderator access to a user, but ONLY in a single category (because you don't
 * trust him enough to change his/her role to moderator). Every user has to roles : a "default" role (ROLE_GUEST,
 * ROLE_MEMBER, ROLE_MODERATOR, ROLE_ADMINISTRATOR) and also a role that is specific to the user, and that has
 * the following pattern : ROLE_USER_id (eg. ROLE_USER_4)
 *
 * @ORM\Entity
 * @ORM\Table(name="Roles")
 */
class Role
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
     * @ORM\Column(type="string", length=64)
     */
    protected $name;


    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = (string) $name;
    }

    /**
     * Get the identifier of the role
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the role
     *
     * @param  string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get the name of the role
     *
     * @return string
     */
    public function getName()
    {
        return $this->getName();
    }
}

