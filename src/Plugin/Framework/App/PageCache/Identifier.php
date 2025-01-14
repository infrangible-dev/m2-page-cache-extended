<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Plugin\Framework\App\PageCache;

use Infrangible\PageCacheExtended\Helper\Data;
use Infrangible\PageCacheExtended\Helper\Process;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Identifier
{
    /** @var Data */
    protected $pageCacheExtendedHelper;

    /** @var Process */
    protected $processHelper;

    /** @var Json */
    protected $serializer;

    public function __construct(Data $cookieHelper, Process $processHelper, ?Json $serializer)
    {
        $this->pageCacheExtendedHelper = $cookieHelper;
        $this->processHelper = $processHelper;
        $this->serializer = $serializer;
    }

    /**
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetValue(\Magento\Framework\App\PageCache\Identifier $subject, string $result): string
    {
        $data = $this->pageCacheExtendedHelper->getData();

        $data = $this->processHelper->processChecks($data);

        $this->pageCacheExtendedHelper->setData($data);

        $extendedValue = sha1($this->serializer->serialize($data));

        return sha1($this->serializer->serialize([
            'core'     => $result,
            'extended' => $extendedValue
        ]));
    }
}
