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
 * A permission tells if a given role can do a specific action. In this forum, we have a lot of permissions,
 * and new ones can be dynamically added. There are "global" permissions assigned to roles, that have a name
 * following this pattern : PERMISSION_TYPE (eg. PERMISSION_CAN_READ_THREADS).
 *
 * You can also add "local" permissions, either for a specific user, or for a whole group. Those permissions follow
 * this pattern : PERMISSION_CAT_id_TYPE (eg. PERMISSION_CAT_4_CAN_READ_THREADS)
 *
 * Here are the basic permission you can find out-of-the-box:
 *
 *      - PERMISSION_CAN_READ_THREADS
 *      - PERMISSION_CAN_CREATE_THREADS
 *      - PERMISSION_CAN_ADD_REPLIES
 *      - PERMISSION_CAN_EDIT_POSTS
 *      - PERMISSION_CAN_DELETE_THREADS
 *      - PERMISSION_CAN_DELETE_POSTS
 *
 * @ORM\Entity
 * @ORM\Table(name="Permissions")
 */
class Permission
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
     * Get the identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the permission
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
     * Get the name of the permission
     *
     * @return string
     */
    public function getName()
    {
        return $this->getName();
    }
}
