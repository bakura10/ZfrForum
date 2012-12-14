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

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app           = $e->getTarget();
        $eventManager  = $app->getEventManager();

        // Attach to helper set event and load the entity manager helper.
        $eventManager->attach('addPost.post', function(EventInterface $e) {
            /* @var $threadService \ZfrForum\Service\ThreadService */
            $threadService = $e->getTarget();

            $params = $e->getParams();
            $user   = $params['user'];
            $thread = $params['thread'];

            $threadService->track($user, $thread);
        });
    }

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
    public function getServiceConfig()
    {
        return array(
            /**
             * Invokables
             */
            'invokables' => array(
                'ZfrForum\DoctrineExtensions\TablePrefix' => 'ZfrForum\DoctrineExtensions\TablePrefix',
            ),
            /**
             * Factories
             */
            'factories' => array(
                'ZfrForum\Options\ModuleOptions' => function ($serviceManager) {
                    $config  = $serviceManager->get('Config');
                    $options = isset($config['zfr_forum']) ? $config['zfr_forum'] : array();

                    return new Options\ModuleOptions($options);
                },
                'ZfrForum\Service\CategoryService' => function($serviceManager) {
                    $categoryMapper = $serviceManager->get('ZfrForum\Mapper\CategoryMapperInterface');
                    return new Service\CategoryService($categoryMapper);
                },
                'ZfrForum\Service\PostService' => function($serviceManager) {
                    $postMapper     = $serviceManager->get('ZfrForum\Mapper\PostMapperInterface');
                    $reportMapper   = $serviceManager->get('ZfrForum\Mapper\ReportMapperInterface');
                    $authentication = $serviceManager->get('zfcuser_auth_service');
                    return new Service\PostService($postMapper, $reportMapper, $authentication);
                },
                'ZfrForum\Service\RankService' => function($serviceManager) {
                    $rankMapper = $serviceManager->get('ZfrForum\Mapper\RankMapperInterface');
                    return new Service\RankService($rankMapper);
                },
                'ZfrForum\Service\SettingsService' => function($serviceManager) {
                    $settingsMapper = $serviceManager->get('ZfrForum\Mapper\SettingsMapperInterface');
                    return new Service\SettingsService($settingsMapper);
                },
                'ZfrForum\Service\ThreadService' => function($serviceManager) {
                    $threadMapper         = $serviceManager->get('ZfrForum\Mapper\ThreadMapperInterface');
                    $threadTrackingMapper = $serviceManager->get('ZfrForum\Mapper\ThreadTrackingMapperInterface');
                    $authentication       = $serviceManager->get('zfcuser_auth_service');
                    return new Service\ThreadService($threadMapper, $threadTrackingMapper, $authentication);
                },
                'ZfrForum\Service\UserBanService' => function($serviceManager) {
                    $userBanMapper = $serviceManager->get('ZfrForum\Mapper\UserBanMapperInterface');
                    return new Service\UserBanService($userBanMapper);
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
