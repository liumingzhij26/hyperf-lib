<?php

declare(strict_types=1);

namespace HyperfLib;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Devtool\VendorPublishCommand;
use Hyperf\HttpServer\CoreMiddleware;
use Hyperf\TfConfig\ConfigFactory;
use HyperfLib\Contract\LockInterface;
use HyperfLib\Contract\ResponseInterface;
use HyperfLib\Library\Http\ServiceResponse;
use HyperfLib\Library\Lock\RedisLockFactory;
use HyperfLib\Library\Logger\StdoutLoggerFactory;
use HyperfLib\Listener\DbQueryExecutedListener;
use HyperfLib\Listener\ErrorHandleListener;
use HyperfLib\Listener\RouterHandleListener;
use HyperfLib\Listener\Server\WorkerErrorHandleListener;
use HyperfLib\Listener\Server\WorkerExitHandleListener;
use HyperfLib\Listener\Server\WorkerStopHandleListener;
use HyperfLib\Listener\ValidatorHandleListener;
use HyperfLib\Middleware\Core\ServiceMiddleware;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ConfigInterface::class => ConfigFactory::class,
                CoreMiddleware::class => ServiceMiddleware::class,
                ResponseInterface::class => ServiceResponse::class,
                StdoutLoggerInterface::class => StdoutLoggerFactory::class,
                LockInterface::class => RedisLockFactory::class,
            ],
            'listeners' => [
                ErrorHandleListener::class,
                RouterHandleListener::class,
                ValidatorHandleListener::class,
                DbQueryExecutedListener::class,
                WorkerStopHandleListener::class,
                WorkerErrorHandleListener::class,
                WorkerExitHandleListener::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'ServerCode',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/Constants/ServerCode.php',
                    'destination' => BASE_PATH . '/app/Constants/ServerCode.php',
                ],
                [
                    'id' => 'InfoCode',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/Constants/InfoCode.php',
                    'destination' => BASE_PATH . '/app/Constants/InfoCode.php',
                ],
                [
                    'id' => 'push',
                    'description' => 'The config for push',
                    'source' => __DIR__ . '/../publish/push.php',
                    'destination' => BASE_PATH . '/config/autoload/push.php',
                ],
                [
                    'id' => 'translation',
                    'description' => 'The config for translation',
                    'source' => __DIR__ . '/../publish/translation.php',
                    'destination' => BASE_PATH . '/config/autoload/translation.php',
                ],
                [
                    'id' => 'validation',
                    'description' => 'The config for validation',
                    'source' => __DIR__ . '/../publish/validation.php',
                    'destination' => BASE_PATH . '/config/autoload/validation.php',
                ],
                [
                    'id' => 'zh_CN',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/languages/zh_CN/validation.php',
                    'destination' => BASE_PATH . '/config/i18n/languages/zh_CN/validation.php',
                ],
                [
                    'id' => 'en',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/languages/en/validation.php',
                    'destination' => BASE_PATH . '/config/i18n/languages/en/validation.php',
                ],
                [
                    'id' => 'auth',
                    'description' => 'The config for auth.',
                    'source' => __DIR__ . '/../publish/auth.php',
                    'destination' => BASE_PATH . '/config/autoload/auth.php',
                ],
                [
                    'id' => 'email',
                    'description' => 'The config for email',
                    'source' => __DIR__ . '/../publish/email.php',
                    'destination' => BASE_PATH . '/config/autoload/email.php',
                ],
                [
                    'id' => 'lock',
                    'description' => 'The config for lock',
                    'source' => __DIR__ . '/../publish/lock.php',
                    'destination' => BASE_PATH . '/config/autoload/lock.php',
                ],
                [
                    'id' => 'env',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/.env.example',
                    'destination' => BASE_PATH . '/.env.example',
                ],
            ],
        ];
    }
}
