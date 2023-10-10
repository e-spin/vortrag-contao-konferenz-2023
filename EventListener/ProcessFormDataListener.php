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

//use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use MetaModels\IFactory;
use MetaModels\Item;

/**
 * Alternative:
 * #[AsHook('processFormData')]
 * https://docs.contao.org/dev/reference/hooks/processFormData/
 */
class ProcessFormDataListener
{
    /**
     * @var IFactory
     */
    private IFactory $factory;

    /**
     * MmForms constructor.
     *
     * @param IFactory $factory
     */
    public function __construct(IFactory $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(
        array $submittedData,
        array $formData,
        ?array $files,
        array $labels,
        Form $form
    ): void {
        if ($formData['id'] === 1) {
            $modelName = 'mm_staff';

            $model = $this->factory->getMetaModel($modelName);
            $item  = new Item($model, []);

            $item->set('firstname', $submittedData['firstname']);
            $item->set('name', $submittedData['name']);

            $select = $model->getAttribute('division');
            $item->set('division', $select->widgetToValue($submittedData['division'], 0));

            $item->save();
        }
    }
}
