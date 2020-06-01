<?php

/*
 * This file is part of the DmytrofModelsManagementFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\ModelsManagementFractalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\{Builder\TreeBuilder, ConfigurationInterface};

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('dmytrof_models_management_fractal');

        return $treeBuilder;
    }
}
