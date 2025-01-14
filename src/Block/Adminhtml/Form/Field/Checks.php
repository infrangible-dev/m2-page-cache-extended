<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Checks extends AbstractFieldArray
{
    /** @var Type */
    private $typeRenderer;

    /** @var Collect */
    private $collectRenderer;

    /**
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'type',
            ['label' => __('Type'), 'renderer' => $this->getTypeRenderer()]
        );
        $this->addColumn(
            'name',
            ['label' => __('Name'), 'style' => 'width:175px;', 'class' => 'required-entry']
        );
        $this->addColumn(
            'pattern',
            ['label' => __('Pattern'), 'style' => 'width:175px;', 'class' => 'required-entry']
        );
        $this->addColumn(
            'fpc_name',
            ['label' => __('FPC Name'), 'style' => 'width:150px;', 'class' => 'required-entry']
        );
        $this->addColumn(
            'fpc_value',
            ['label' => __('FPC Value'), 'style' => 'width:150px;', 'class' => 'required-entry']
        );
        $this->addColumn(
            'collect',
            ['label' => __('Collect'), 'renderer' => $this->getCollectRenderer()]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];

        $typeValue = $row->getData('type');

        if ($typeValue !== null) {
            $options[ 'option_' . $this->getTypeRenderer()->calcOptionHash($typeValue) ] = 'selected="selected"';
        }

        $collectValue = $row->getData('collect');

        if ($collectValue !== null) {
            $options[ 'option_' . $this->getCollectRenderer()->calcOptionHash($collectValue) ] = 'selected="selected"';
        }

        $row->setData(
            'option_extra_attrs',
            $options
        );
    }

    /**
     * @throws LocalizedException
     */
    private function getTypeRenderer(): Type
    {
        if (! $this->typeRenderer) {
            $this->typeRenderer = $this->getLayout()->createBlock(
                Type::class,
                '',
                ['data' => ['is_render_to_js_template' => true, 'extra_params' => 'style="width:150px;"']]
            );
        }

        return $this->typeRenderer;
    }

    /**
     * @throws LocalizedException
     */
    private function getCollectRenderer(): Collect
    {
        if (! $this->collectRenderer) {
            $this->collectRenderer = $this->getLayout()->createBlock(
                Collect::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->collectRenderer;
    }
}
