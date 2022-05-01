<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 07.10.2019
 * Time: 08:00
 */

namespace Igoooor\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('igoooor_user');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('user_class')->defaultValue('App\Entity\User')->end()
            ->end();

        return $treeBuilder;
    }
}
