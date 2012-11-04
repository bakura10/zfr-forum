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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=39)
     */
    protected $ip = '';

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastActivityDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $globalRole = 'MEMBER';


    /**
     * Set the IP address of the user (needed for ban functionnality)
     *
     * @param  string $ip
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get the IP address of the user (needed for ban functionnality)
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the last activity date (this is updated at each request)
     *
     * @param  DateTime $lastActivityDate
     * @return User
     */
    public function setLastActivityDate(DateTime $lastActivityDate)
    {
        $this->lastActivityDate = clone $lastActivityDate;
        return $this;
    }

    /**
     * Get the last activity date
     *
     * @return DateTime
     */
    public function getLastActivityDate()
    {
        return clone $this->lastActivityDate;
    }

    /**
     * Set the global role
     *
     * @param  string $role
     * @return UserInterface
     */
    public function setGlobalRole($role)
    {
        $this->globalRole = (string) $role;
        return $this;
    }

    /**
     * Get the global role
     *
     * @return string
     */
    public function getGlobalRole()
    {
        return $this->canonicalizeRole($this->globalRole);
    }

    /**
     * Get the local role
     *
     * @return string
     */
    public function getLocalRole()
    {
        return $this->canonicalizeRole($this->id);
    }

    /**
     * Get the roles for a given user (a user have two roles : one "global" and one "local")
     *
     * @return array
     */
    public function getRoles()
    {
        return array($this->getGlobalRole(), $this->getLocalRole());
    }

    /**
     * Transform the role name (this is just convention used to avoid name clashes)
     *
     * @param  string $role
     * @return string
     */
    protected function canonicalizeRole($role)
    {
        return 'USER_ROLE_' . strtoupper(str_replace(' ', '_', $role));
    }
}
