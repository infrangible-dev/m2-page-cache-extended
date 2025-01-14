<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Type implements OptionSourceInterface
{
    /**
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'param', 'label' => __('Parameter')],
            ['value' => 'header', 'label' => __('Header')],
            ['value' => 'cookie', 'label' => __('Cookie')],
            ['value' => 'server', 'label' => __('Server')],
            ['value' => 'env', 'label' => __('Environment')]
        ];
    }
}
