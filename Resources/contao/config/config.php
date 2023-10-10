<?php

/**
 * Created by e-spin Berlin.
 *
 * (c) 2022
 *
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2022 Ingolf Steinhardt <info@e-spin.de>
 * @license    Commercial
 * @filesource
 */

/**
 * Back end modules
 */
if (!is_array($GLOBALS['BE_MOD'])) {
    $GLOBALS['BE_MOD'] = [];
}

$i                 = array_search('design', array_keys($GLOBALS['BE_MOD']));
$GLOBALS['BE_MOD'] = array_merge(
    array_slice(
        $GLOBALS['BE_MOD'],
        0,
        $i
    ),
    [
        'mm-workshop' => []
    ],
    array_slice($GLOBALS['BE_MOD'], $i)
);

if (defined('TL_MODE') && 'BE' === TL_MODE) {
    $GLOBALS['TL_CSS']['mm-workshop'] = 'bundles/app/css/style.css';
}
