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
use ZfcUser\Entity\UserInterface;

/**
 * This entity is used to save user-wide parameters for the forum. Each forum can define its own default parameters,
 * some of them can be overridden by the user
 *
 * @ORM\Entity
 * @ORM\Table(name="UserSettings")
 */
class UserSettings extends AbstractSettings
{
    /**
     * @var UserInterface
     *
     * @ORM\OneToOne(targetEntity="ZfcUser\Entity\UserInterface")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $primaryStyleName = 'Default';


    /**
     * Set the name of the primary style to use
     *
     * @param  string $primaryStyleName
     * @return UserSettings
     */
    public function setPrimaryStyleName($primaryStyleName)
    {
        $this->primaryStyleName = (string) $primaryStyleName;
        return $this;
    }

    /**
     * Get the name of the primary style to use
     *
     * @return string
     */
    public function getPrimaryStyleName()
    {
        return $this->primaryStyleName;
    }
}
