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

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use MetaModels\AttributeSelectBundle\Attribute\AbstractSelect;
use MetaModels\DcGeneral\Data\Model;
use MetaModels\IFactory;
use MetaModels\IMetaModel;

class GetPropertyOptionsListener
{
    /**
     * @var IFactory
     */
    private IFactory $factory;

    /**
     * GetPropertyOptionsListener constructor.
     *
     * @param IFactory $factory
     *
     * @internal param IFactory $factory
     */
    public function __construct(IFactory $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(GetPropertyOptionsEvent $event): void
    {
        // Check if options set.
        if (null !== $event->getOptions()) {
            return;
        }

        // Check if right model table and type.
        if ('mm_staff' !== $event->getEnvironment()->getDataDefinition()->getName()) {
            return;
        }

        $model = $event->getModel();
        if (!($model instanceof Model)) {
            return;
        }

        // Check if right attribute and type.
        if ('holiday_substitution' !== $event->getPropertyName()) {
            return;
        }

        $attribute = $model->getItem()->getAttribute($event->getPropertyName());
        if (!($attribute instanceof AbstractSelect)) {
            return;
        }

        $options = [];

        // Name der MetaModel Tabelle
        $modelName = 'mm_staff';

        $modelStaff = $this->factory->getMetaModel($modelName);
        assert($modelStaff instanceof IMetaModel);
        $filter = $modelStaff->getEmptyFilter();
        $items  = $modelStaff->findByFilter($filter, 'name');

        if ($items->getCount()) {
            $aliasColumn = $attribute->get('select_alias');
            foreach ($items as $item) {
                $options[$item->get($aliasColumn)] = \sprintf(
                    '%s, %s [%s]',
                    $item->get('name'),
                    $item->get('firstname'),
                    $item->get('division')['name']
                );
            }
        }

        $event->setOptions($options);
    }
}
