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

namespace ZfrForum\Service;

use ZfcUser\Entity\UserInterface;
use ZfrForum\Entity\AbstractSettings;
use ZfrForum\Entity\GlobalSettings;
use ZfrForum\Entity\UserSettings;
use ZfrForum\Mapper\SettingsMapperInterface;

class SettingsService
{
    /**
     * @var SettingsMapperInterface
     */
    protected $settingsMapper;


    /**
     * @param SettingsMapperInterface $settingsMapper
     */
    public function __construct(SettingsMapperInterface $settingsMapper)
    {
        $this->settingsMapper = $settingsMapper;
    }

    /**
     * Get forum settings (first get global settings, then it tries to get user settings if global settings allow
     * this). You can force to get global settings by setting first parameter to true (this is the same as
     * calling getGlobalSettings directly)
     *
     * @param  bool $forceGlobals
     * @return AbstractSettings
     */
    public function getSettings($forceGlobals = false)
    {
        if ($forceGlobals) {
            return $this->getGlobalSettings();
        }

        $settings = $this->getGlobalSettings();

        if ($settings->canSettingsBeOverriddenByUser() === false) {
            return $settings;
        }

        $loggedUser = null; // get the logged user

        if ($userSettings = $this->getUserSettings($loggedUser)) {
            $settings = $userSettings;
        }

        return $settings;
    }

    /**
     * @return GlobalSettings
     */
    public function getGlobalSettings()
    {
        return $this->settingsMapper->findGlobalSettings();
    }

    /**
     * @param  UserInterface $user
     * @return UserSettings|null
     */
    public function getUserSettings(UserInterface $user)
    {
        return $this->settingsMapper->findByUser($user);
    }
}
