<?php

/**
 * Created by e-spin Berlin.
 *
 * (c) 2023
 *
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2023 Ingolf Steinhardt <info@e-spin.de>
 * @license    LGPL-3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace App\ContaoManager;

use App\AppBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use MetaModels\CoreBundle\MetaModelsCoreBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @param ParserInterface $parser
     *
     * @return ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(AppBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        ContaoManagerBundle::class,
                        MetaModelsCoreBundle::class,
                    ]
                )
        ];
    }

    /**
     * "Anti-Contao-Magic-Service":
     * Apparently, in Contao 4.13, Contao's own service.yml settings are
     * overwritten by Contao's "magic" and auto-wire is enabled - to bypass
     * it, a fake service is included with "App\".
     * https://github.com/contao/manager-bundle/blob/0ea2d38b8236c4119cc3ad04896c5620dce56ef5/src/Resources/skeleton/config/services.php#L30
     * https://github.com/contao/manager-plugin/blob/7771110f7d6f6a7ea3fec80dfe311dbf445f852b/src/Composer/AppAutoloadPlugin.php#L31
     *
     * @param LoaderInterface $loader
     * @param array           $managerConfig
     *
     * @return void
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__ . '/../Resources/config/foo.yml');
    }
}
