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

namespace App\Filter;

use Contao\FormSelectMenu;
use Contao\Widget;
use ContaoCommunityAlliance\DcGeneral\Contao\RequestScopeDeterminator;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\SimpleQuery;
use MetaModels\Filter\Setting\ISimple;
use MetaModels\FrontendIntegration\FrontendFilterOptions;
use MetaModels\IFactory;
use MetaModels\IItem;
use MetaModels\IMetaModel;
use MetaModels\ITranslatedMetaModel;
use MetaModels\Render\Setting\ICollection as IRenderSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class DivisionFilter implements ISimple
{
    private TranslatorInterface $translator;

    private IFactory $factory;

    private Connection $connection;

    private RequestScopeDeterminator $scopeMatcher;

    private RequestStack $requestStack;


    private string $attributeKey = 'division';

    /**
     * Create a new instance.
     *
     * @param TranslatorInterface      $translator   The translator.
     * @param IFactory                 $factory      The model factory.
     * @param Connection               $connection   The DB connection.
     * @param RequestScopeDeterminator $scopeMatcher The request scope determinator.
     * @param RequestStack             $requestStack The request stack.
     */
    public function __construct(
        TranslatorInterface $translator,
        IFactory $factory,
        Connection $connection,
        RequestScopeDeterminator $scopeMatcher,
        RequestStack $requestStack
    ) {
        $this->translator   = $translator;
        $this->factory      = $factory;
        $this->connection   = $connection;
        $this->scopeMatcher = $scopeMatcher;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function get($strKey)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl): void
    {
        if (!isset($arrFilterUrl[$this->attributeKey])) {
            return;
        }

        // Retrieve staff with related division.
        $subQuery = $this->connection->createQueryBuilder();
        $subQuery
            ->select('mmtt.item_id AS id')
            ->from('tl_metamodel_translatedtext', 'mmtt')
            ->where('mmtt.att_id=65')
            ->andWhere('mmtt.value=:division');

        $query = $this->connection->createQueryBuilder();
        $query
            ->select('staff.id')
            ->from('mm_staff', 'staff')
            ->where('division=(' . $subQuery->getSQL() . ')')
            ->setParameter('division', $arrFilterUrl['division']);

        $objFilter->addFilterRule(SimpleQuery::createFromQueryBuilder($query));
    }

    /**
     * {@inheritdoc}
     */
    public function generateFilterUrlFrom(IItem $objItem, IRenderSettings $objRenderSetting): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [$this->attributeKey];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterDCA(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterFilterNames(): array
    {
        return [$this->attributeKey => $this->getAttributeLabel()];
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterFilterWidgets(
        $arrIds,
        $arrFilterUrl,
        $arrJumpTo,
        FrontendFilterOptions $objFrontendFilterOptions
    ): array {
        $autoSubmit = false;
        if ($objFrontendFilterOptions->isAutoSubmit() && $this->scopeMatcher->currentScopeIsFrontend()) {
            $autoSubmit                             = true;
            $GLOBALS['TL_JAVASCRIPT']['metamodels'] = 'bundles/metamodelscore/js/metamodels.min.js';
        }

        $selectedValue = $arrFilterUrl[$this->attributeKey] ?? null;

        $objWidget = $this->generateFormFieldWidget($selectedValue, $arrIds, $autoSubmit);
        $formField = $objWidget->generateWithError();
        $raw       = [
            'label' => [$this->getAttributeLabel(), 'GET: ' . $this->attributeKey],
            'value' => $selectedValue
        ];

        $this->addFilterParam();

        // See prepareFrontendFilterWidget in Simple.php
        return [
            $this->attributeKey => [
                'class'      => \sprintf(
                    'mm_%s %s%s%s',
                    'select',
                    $this->attributeKey,
                    ($selectedValue !== null ? ' used' : ' unused'),
                    ($autoSubmit ? ' submitonchange' : '')
                ),
                'label'      => \sprintf(
                    '<label for="ctrl_%s">%s</label>',
                    $this->attributeKey,
                    $this->getAttributeLabel()
                ),
                'hide_label' => true,
                'inputType'  => 'select',
                'formfield'  => $formField,
                'raw'        => $raw,
                'urlparam'   => $this->attributeKey,
                'urlvalue'   => $selectedValue,
                'errors'     => $objWidget->hasErrors() ? $objWidget->getErrors() : [],
                'used'       => $selectedValue !== null,
                'cssID'      => ''
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getReferencedAttributes(): array
    {
        return [];
    }

    /**
     * Add filter param to global.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function addFilterParam(): void
    {
        $GLOBALS['MM_FILTER_PARAMS'][] = $this->attributeKey;
    }

    /**
     * Generate widget.
     *
     * @param string|null $selectedValue
     * @param array|null  $allowedIds
     * @param bool        $autoSubmit
     *
     * @return Widget
     */
    private function generateFormFieldWidget(?string $selectedValue, ?array $allowedIds, bool $autoSubmit): Widget
    {
        $divisions = $this->factory->getMetaModel('mm_division');
        assert($divisions instanceof IMetaModel);

        $filter = $divisions->getEmptyFilter();
        if (null !== $allowedIds) {
            $query = $this->connection->createQueryBuilder();
            $query
                ->select('staff.division')
                ->from('mm_staff', 'staff')
                ->where('staff.id IN (:ids)')
                ->setParameter('ids', $allowedIds, ArrayParameterType::STRING);
            $filter->addFilterRule(SimpleQuery::createFromQueryBuilder($query, 'division'));
        }

        // Add empty option.
        $options = [
            [
                'value' => '',
                'label' => $this->translator->trans(
                    'tl_metamodel_filtersetting.all_divisions',
                    [],
                    'contao_tl_metamodel_filtersetting'
                )
            ],
        ];

        if ($divisions instanceof ITranslatedMetaModel) {
            $currentRequest = $this->requestStack->getCurrentRequest();
            assert($currentRequest instanceof Request);
            $language = $currentRequest->getLocale();
            $divisions->selectLanguage($language);
        }

        $all = $divisions->findByFilter($filter);
        foreach ($all as $item) {
            $options[] = [
                'value' => $item->parseAttribute('alias')['text'],
                'label' => $item->parseAttribute('name')['text'],
            ];
        }

        return new FormSelectMenu(
            [
                'name'    => $this->attributeKey,
                'id'      => $this->attributeKey,
                'label'   => $this->getAttributeLabel(),
                'value'   => $selectedValue,
                'options' => $options,
                'class'   => 'form-control' . ($autoSubmit ? ' submitonchange' : '')
            ]
        );
    }

    /**
     * Get label to key.
     *
     * @return string
     */
    private function getAttributeLabel(): string
    {
        return $this->translator->trans(
            'tl_metamodel_filtersetting.' . $this->attributeKey,
            [],
            'contao_tl_metamodel_filtersetting'
        );
    }
}
