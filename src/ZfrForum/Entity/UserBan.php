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
use ZfcUser\Entity\UserInterface;

/**
 * @ORM\Entity(repositoryClass="ZfrForum\Repository\UserBanRepository")
 * @ORM\Table(name="UsersBan")
 */
class UserBan
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
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=1000)
     */
    protected $message;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $expireAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $bannedBy;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $actived = true;

    /**
     * Get the identifier of the ban
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the banned user
     *
     * @param  UserInterface $user
     * @return UserBan
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the banned user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the ban message
     *
     * @param  string $message
     * @return UserBan
     */
    public function setMessage($message)
    {
        $this->message = (string) $message;
        return $this;
    }

    /**
     * Get the ban message
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the end date of the ban
     *
     * @param DateTime $expireAt
     * @return UserBan
     */
    public function setExpireAt(DateTime $expireAt)
    {
        $this->expireAt = clone $expireAt;
        return $this;
    }

    /**
     * Get the end date of the ban
     *
     * @return DateTime
     */
    public function getExpireAt()
    {
        return clone $this->expireAt;
    }

    /**
     * Set the admin user who banned
     * 
     * @param UserInterface $bannedBy
     * @return UserBan
     */
    public function setBannedBy(UserInterface $bannedBy)
    {
        $this->bannedBy = $bannedBy;
        return $this;
    }

    /**
     * Get the admin user who banned
     * 
     * @return UserInterface 
     */
    public function getBannedBy()
    {
        return $this->bannedBy;
    }
    
    /**
     * Set the ban is actived
     * 
     * @param  boolean $actived
     * @return UserBan
     */
    public function setActived($actived)
    {
        $this->actived = (boolean) $actived;
        return $this;
    }
    
    /**
     * Return true if the ban is actived
     * 
     * @return boolean 
     */
    public function isActived()
    {
        return $this->actived;
    }
}
