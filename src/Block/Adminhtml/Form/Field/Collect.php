<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Block\Adminhtml\Form\Field;

use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Collect extends Select
{
    /** @var Yesno */
    protected $yesNoSource;

    public function __construct(Context $context, Yesno $yesNoSource, array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );

        $this->yesNoSource = $yesNoSource;
    }

    public function setInputName(string $value): Collect
    {
        return $this->setData(
            'name',
            $value
        );
    }

    public function setInputId(string $value): Collect
    {
        return $this->setId($value);
    }

    public function _toHtml(): string
    {
        if (! $this->getOptions()) {
            $this->setOptions($this->yesNoSource->toOptionArray());
        }

        return parent::_toHtml();
    }
}
