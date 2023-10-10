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

namespace App\Helper;

use MetaModels\Filter\Setting\IFilterSettingFactory;
use MetaModels\IFactory;
use MetaModels\IMetaModel;
use MetaModels\Render\Setting\IRenderSettingFactory;

class MetaModelsTemplateHelper
{
    /**
     * The factory.
     *
     * @var IFactory
     */
    private IFactory $factory;

    /**
     * Filter setting factory.
     *
     * @var IFilterSettingFactory
     */
    private IFilterSettingFactory $filterFactory;

    /**
     * Render setting factory.
     *
     * @var IRenderSettingFactory
     */
    private IRenderSettingFactory $renderFactory;

    /**
     * MetaModelsTemplateHelper constructor.
     *
     * @param IFactory              $factory
     * @param IFilterSettingFactory $filterFactory
     * @param IRenderSettingFactory $renderFactory
     */
    public function __construct(
        IFactory $factory,
        IFilterSettingFactory $filterFactory,
        IRenderSettingFactory $renderFactory
    ) {
        $this->factory       = $factory;
        $this->filterFactory = $filterFactory;
        $this->renderFactory = $renderFactory;
    }

    public function getStaffMembersByDivisionAlias($divisionAlias): array
    {
        // Name der MetaModel Tabelle
        $modelName = 'mm_staff';
        // ID der Render-Einstellungen "FE-Liste"
        $renderId = 17;
        // ID des Filters "FE - Abteilung per API"
        $filterId = 15;
        // Filterwert
        $filterUrl = ['division' => $divisionAlias];

        $model = $this->factory->getMetaModel($modelName);
        assert($model instanceof IMetaModel);
        $filter           = $model->getEmptyFilter();
        $filterCollection = $this->filterFactory->createCollection($filterId);
        $filterCollection->addRules($filter, $filterUrl);
        $items    = $model->findByFilter($filter);
        $arrItems = $items->parseAll('html5', $this->renderFactory->createCollection($model, $renderId));

        //dump($arrItems);

        return $arrItems;
    }
}
