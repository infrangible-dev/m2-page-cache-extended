<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Type extends Select
{
    /** @var \Infrangible\PageCacheExtended\Model\Config\Source\Type */
    protected $typeSource;

    public function __construct(
        Context $context,
        \Infrangible\PageCacheExtended\Model\Config\Source\Type $typeSource,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->typeSource = $typeSource;
    }

    public function setInputName(string $value): Type
    {
        return $this->setData(
            'name',
            $value
        );
    }

    public function setInputId(string $value): Type
    {
        return $this->setId($value);
    }

    public function _toHtml(): string
    {
        if (! $this->getOptions()) {
            $this->setOptions($this->typeSource->toOptionArray());
        }

        return parent::_toHtml();
    }
}
