<?php

declare(strict_types=1);

namespace Infrangible\PageCacheExtended\Helper;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Process
{
    /** @var Json */
    protected $serializer;

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var Http */
    protected $request;

    public function __construct(Json $serializer, ScopeConfigInterface $scopeConfig, Http $request)
    {
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    public function processChecks(array $data): array
    {
        $checksConfig = $this->scopeConfig->getValue('infrangible_pagecachextended/settings/checks');

        $checks = [];

        if (! empty($checksConfig)) {
            try {
                $checks = $this->serializer->unserialize($checksConfig);
            } catch (InvalidArgumentException $exception) {
            }
        }

        foreach ($checks as $check) {
            $type = array_key_exists(
                'type',
                $check
            ) ? $check[ 'type' ] : null;

            $name = array_key_exists(
                'name',
                $check
            ) ? $check[ 'name' ] : null;

            $pattern = array_key_exists(
                'pattern',
                $check
            ) ? $check[ 'pattern' ] : null;

            $fpcName = array_key_exists(
                'fpc_name',
                $check
            ) ? $check[ 'fpc_name' ] : null;

            $fpcValue = (int)array_key_exists(
                'fpc_value',
                $check
            ) ? $check[ 'fpc_value' ] : 1;

            $collect = (int)array_key_exists(
                'collect',
                $check
            ) ? $check[ 'collect' ] : 1;

            if ($name === null || $fpcName === null) {
                continue;
            }

            if ($type === 'param') {
                $varValue = (string)$this->request->getParam($name);
            } elseif ($type === 'header') {
                $varValue = (string)$this->request->getHeader($name);
            } elseif ($type === 'cookie') {
                $varValue = (string)$this->request->getCookie($name);
            } elseif ($type === 'server') {
                $varValue = (string)$this->request->getServer($name);
            } elseif ($type === 'env') {
                $varValue = (string)$this->request->getEnv($name);
            } else {
                $varValue = null;
            }

            if ($varValue !== null && $pattern !== null && preg_match(
                    sprintf(
                        '<%s>',
                        $pattern
                    ),
                    $varValue
                )) {
                $data[ $fpcName ] = $fpcValue;
            } elseif (! $collect) {
                unset($data[ $fpcName ]);
            }
        }

        return $data;
    }
}
