<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 07.10.2019
 * Time: 08:00
 */

namespace Igoooor\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class IgoooorUserExtension
 */
class IgoooorUserExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configPath = __DIR__.'/../Resources/config';

        $fileLocator = new FileLocator($configPath);

        $loader = new XmlFileLoader($container, $fileLocator);
        $loader->load('services.xml');

        $processor = new Processor();
        $config = $processor->processConfiguration($this->getConfiguration($configs, $container), $configs);

        $container->getDefinition('Igoooor\UserBundle\Factory\SuperAdminUserFactory')
            ->replaceArgument(1, $config['user_class']);
        $container->getDefinition('Igoooor\UserBundle\Utils\UserManipulator')
            ->replaceArgument(1, $config['user_class']);
    }
}
