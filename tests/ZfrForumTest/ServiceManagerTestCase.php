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

namespace ZfrForumTest;

use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit_Framework_TestCase as BaseTestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Application;

/**
 * Base test case to be used when a service manager instance is required
 */
class ServiceManagerTestCase extends BaseTestCase
{
    /**
     * @var array
     */
    private static $configuration = array();

    /**
     * @var ServiceManager
     */
    private static $serviceManager = null;

    /**
     * @var boolean
     */
    private static $hasDb = false;

    /**
     * Creates a database if not done already.
     */
    public function createDb()
    {
        if (self::$hasDb) {
            return;
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = self::$serviceManager->get('Doctrine\ORM\EntityManager');
        $tool = new SchemaTool($em);
        $tool->updateSchema($em->getMetadataFactory()->getAllMetadata());
        self::$hasDb = true;
    }

    public function dropDb()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = self::$serviceManager->get('Doctrine\ORM\EntityManager');
        $tool = new SchemaTool($em);
        $tool->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $em->clear();

        self::$hasDb = false;
    }

    /**
     * @static
     * @param array $configuration
     */
    public static function setConfiguration(array $configuration)
    {
        self::$configuration = $configuration;
    }

    /**
     * @static
     * @return array
     */
    public static function getConfiguration()
    {
        return self::$configuration;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }

    /**
     * @static
     * @return null|ServiceManager
     */
    public static function getServiceManager()
    {
        return self::$serviceManager;
    }
}

