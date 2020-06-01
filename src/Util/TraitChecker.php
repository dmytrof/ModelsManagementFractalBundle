<?php

/*
 * This file is part of the DmytrofModelsManagementFractalBundle package.
 *
 * (c) Dmytro Feshchenko <dmytro.feshchenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dmytrof\ModelsManagementFractalBundle\Util;

class TraitChecker
{
    /**
     * Checks trait
     * @param mixed $object
     * @param string $traitName
     * @param bool $autoload
     * @return bool
     */
    public static function check($object, string $traitName, bool $autoload = true): bool
    {
        $traits = [];
        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($object, $autoload), $traits);
        } while ($object = get_parent_class($object));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        };

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return in_array($traitName, $traits);
    }
}