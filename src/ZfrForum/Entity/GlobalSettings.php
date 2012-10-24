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
 * @ORM\Entity
 * @ORM\Table(name="GlobalSettings")
 */
class GlobalSettings extends AbstractSettings
{
    /**
     * @var array
     */
    protected $styles = array('Default' => 'default.css');

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $defaultStyleName;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $maxSignaturesLength;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $canSettingsBeOverriddenByUser;


    /**
     * Add a new style
     *
     * @param  string $styleName
     * @param  string $styleSheet
     * @return void
     */
    public function addStyle($styleName, $styleSheet)
    {
        $this->styles[$styleName] = strtolower($styleSheet);
    }

    /**
     * Remove a style by its name
     *
     * @param string $styleName
     */
    public function removeStyle($styleName)
    {
        // We can't remove the default stylesheet
        if ($styleName === 'Default') {
            return;
        }

        unset($this->styles[$styleName]);
    }

    /**
     * Is this style defined ?
     *
     * @param  string $styleName
     * @return bool
     */
    public function hasStyle($styleName)
    {
        return isset($this->styles[$styleName]);
    }

    /**
     * Set the name of the default style
     *
     * @param  $defaultStyleName
     * @return GlobalSettings
     */
    public function setDefaultStyleName($defaultStyleName)
    {
        $this->defaultStyleName = (string) $defaultStyleName;
        return $this;
    }

    /**
     * Get the name of the default style
     *
     * @return string
     */
    public function getDefaultStyleName()
    {
        return $this->defaultStyleName;
    }

    /**
     * Set the maximum length for signatures
     *
     * @param  int $maxSignaturesLength
     * @return GlobalSettings
     */
    public function setMaxSignaturesLength($maxSignaturesLength)
    {
        $this->maxSignaturesLength = (int) $maxSignaturesLength;
        return $this;
    }

    /**
     * Get the maximum length for signatures
     *
     * @return int
     */
    public function getMaxSignaturesLength()
    {
        return $this->maxSignaturesLength;
    }

    /**
     * If set to true (which is the default), users have the possibility to change the default settings of
     * the forum
     *
     * @param  boolean $canSettingsBeOverriddenByUser
     * @return GlobalSettings
     */
    public function setCanSettingsBeOverriddenByUser($canSettingsBeOverriddenByUser)
    {
        $this->canSettingsBeOverriddenByUser = (bool) $canSettingsBeOverriddenByUser;
        return $this;
    }

    /**
     * Return if users can override the default settings of the forum
     *
     * @return boolean
     */
    public function canSettingsBeOverriddenByUser()
    {
        return $this->canSettingsBeOverriddenByUser;
    }
}
