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

namespace ZfrForum;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerPluginProviderInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface
{

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getControllerPluginConfig()
    {
        return array(
            /**
             * Invokables
             */
            'factories' => array(
                'ZfrForum\Controller\Plugin\ForumSettings' => function($serviceManager) {
                    $settingsService = $serviceManager->get('ZfrForum\Service\SettingsService');
                    return new Controller\Plugin\ForumSettings($settingsService);
                }
            ),
            /**
             * Aliases
             */
            'aliases' => array(
                'forumSettings' => 'ZfrForum\Controller\Plugin\ForumSettings'
            )
        );
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            /**
             * Invokables
             */
            'factories' => array(
                'ZfrForum\View\Helper\ForumSettings' => function($serviceManager) {
                    $settingsService = $serviceManager->get('ZfrForum\Service\SettingsService');
                    return new View\Helper\ForumSettings($settingsService);
                }
            ),
            /**
             * Aliases
             */
            'aliases' => array(
                'forumSettings' => 'ZfrForum\View\Helper\ForumSettings'
            )
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            /**
             * Invokables
             */
            'invokables' => array(
                'ZfrForum\DoctrineExtensions\TablePrefix' => 'ZfrForum\DoctrineExtensions\TablePrefix',
                'ZfrForum\Service\UserBanService' => 'ZfrForum\Service\UserBanService',
            ),
            /**
             * Factories
             */
            'factories' => array(
                'ZfrForum\Options\ModuleOptions' => function ($serviceManager) {
                    $config = $serviceManager->get('Config');
                    $options = isset($config['zfr_forum']) ? $config['zfr_forum'] : array();

                    return new Options\ModuleOptions($options);
                },
                'ZfrForum\Service\CategoryService' => function($serviceManager) {
                    $categoryMapper = $serviceManager->get('ZfrForum\Mapper\CategoryMapperInterface');
                    return new Service\CategoryService($categoryMapper);
                },
                'ZfrForum\Service\MessageService' => function($serviceManager) {
                    $messageMapper = $serviceManager->get('ZfrForum\Mapper\MessageMapperInterface');
                    return new Service\MessageService($messageMapper);
                },
                'ZfrForum\Service\SettingsService' => function($serviceManager) {
                    $settingsMapper = $serviceManager->get('ZfrForum\Mapper\SettingsMapperInterface');
                    return new Service\SettingsService($settingsMapper);
                },
                'ZfrForum\Service\ThreadService' => function($serviceManager) {
                    $threadMapper = $serviceManager->get('ZfrForum\Mapper\ThreadMapperInterface');
                    return new Service\ThreadService($threadMapper);
                },
            ),
            /**
             * Abstract factories
             */
            'abstract_factories' => array(
                'ZfrForum\ServiceFactory\MapperAbstractFactory'
            )
        );
    }

}
