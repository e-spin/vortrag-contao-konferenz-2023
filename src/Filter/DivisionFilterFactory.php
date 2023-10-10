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

use ContaoCommunityAlliance\DcGeneral\Contao\RequestScopeDeterminator;
use Doctrine\DBAL\Connection;
use MetaModels\Filter\Setting\IFilterSettingTypeFactory;
use MetaModels\Filter\Setting\ISimple;
use MetaModels\IFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class DivisionFilterFactory implements IFilterSettingTypeFactory
{
    private TranslatorInterface $translator;

    private IFactory $factory;

    private Connection $connection;

    private RequestScopeDeterminator $scopeMatcher;

    private RequestStack $requestStack;

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
    public function getTypeName(): string
    {
        return 'divisionfilter';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeIcon(): string
    {
        return 'bundles/app/icons/filter_division.png';
    }

    /**
     * {@inheritdoc}
     */
    public function createInstance($information, $filterSettings): ISimple|DivisionFilter|null
    {
        return new DivisionFilter(
            $this->translator,
            $this->factory,
            $this->connection,
            $this->scopeMatcher,
            $this->requestStack
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isNestedType(): bool
    {
        return false;
    }

    public function getMaxChildren(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getKnownAttributeTypes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function addKnownAttributeType($typeName): self
    {
        return $this;
    }
}
