<?php
declare(strict_types=1);

/**
 * Log类
 */

namespace HyperfLib\Library\Logger;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * 日志
     *
     * @param string $name
     * @return LoggerInterface
     */
    public static function get(string $name = '')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name ?: env('APP_NAME'));
    }
}
