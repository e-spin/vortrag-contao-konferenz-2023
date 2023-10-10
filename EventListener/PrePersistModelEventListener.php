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

namespace App\EventListener;

use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\Event\PrePersistModelEvent;
use MetaModels\IFactory;
use MetaModels\IItem;
use MetaModels\IMetaModel;

class PrePersistModelEventListener
{
    /**
     * @var IFactory
     */
    private IFactory $factory;

    /**
     * PrePersistModelEventListener constructor.
     *
     * @param IFactory $factory
     *
     * @internal param IFactory $factory
     */
    public function __construct(IFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param PrePersistModelEvent $event
     */
    public function __invoke(PrePersistModelEvent $event): void
    {
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        $model = $event->getModel();
        $email = \sprintf(
            '%s.%s@example.com',
            $model->getProperty('firstname'),
            $model->getProperty('name')
        );
        $model->setProperty('email', $email);
    }

    public function x__invoke(PrePersistModelEvent $event): void
    {
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        // Vergleich DB vs. Eingabe
        $originalModel = $event->getOriginalModel();
        assert($originalModel instanceof ModelInterface);
        $model = $event->getModel();
        if (
            $originalModel->getProperty('name')
            !== $model->getProperty('name')
        ) {
            $model->setProperty('name_changed', time());
        }
    }

    public function xx__invoke(PrePersistModelEvent $event): void
    {
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        // Neu erstellt?
        $model = $event->getModel();
        if (null === $model->getId()) {
            $model->setProperty('created', time());
        }
    }

    public function xxx__invoke(PrePersistModelEvent $event): void
    {
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        // Item gerendert
        $model = $event->getModel();
        $item  = $model->getItem();
        assert($item instanceof IItem);
        $arrItem       = $item->parseValue();
        $divisionPhone = $arrItem['raw']['division']['phone'];
    }

    public function xxxx__invoke(PrePersistModelEvent $event): void
    {
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        // Name der MetaModel Tabelle
        $modelName = 'mm_foobar';

        $modelFoobar = $this->factory->getMetaModel($modelName);
        assert($modelFoobar instanceof IMetaModel);
        $filter = $modelFoobar->getEmptyFilter();
        $items  = $modelFoobar->findByFilter($filter);
    }
}
