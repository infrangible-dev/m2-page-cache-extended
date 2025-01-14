<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Helper;

use InvalidArgumentException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    /** @var CookieManagerInterface */
    protected $cookieManager;

    /** @var Json */
    protected $serializer;

    /** @var CookieMetadataFactory */
    protected $cookieMetadataFactory;

    /** @var SessionManagerInterface */
    protected $sessionManager;

    /** @var array */
    private $data;

    public function __construct(
        CookieManagerInterface $cookieManager,
        Json $serializer,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager = $cookieManager;
        $this->serializer = $serializer;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }

    public function getData(): array
    {
        if ($this->data === null) {
            $sourceCookie = $this->cookieManager->getCookie('mage_fpc_extended');

            if ($sourceCookie === null) {
                $data = [];
            } else {
                try {
                    $data = $this->serializer->unserialize(rawurldecode($sourceCookie));
                } catch (InvalidArgumentException $exception) {
                    $data = [];
                }
            }

            $this->data = $data;
        }

        return $this->data;
    }

    /**
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function setData(array $data)
    {
        $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();

        $metadata->setDuration($this->sessionManager->getCookieLifetime());
        $metadata->setPath($this->sessionManager->getCookiePath());
        $metadata->setDomain($this->sessionManager->getCookieDomain());

        $this->cookieManager->setPublicCookie(
            'mage_fpc_extended',
            rawurlencode($this->serializer->serialize($data)),
            $metadata
        );
    }
}
