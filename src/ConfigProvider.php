<?php

declare(strict_types=1);

namespace HyperfLib;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [

            ],
            'commands' => [

            ],
            'listeners' => [

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
                    'id' => 'BaseRequest',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/Request/BaseRequest.php',
                    'destination' => BASE_PATH . '/app/Request/BaseRequest.php',
                ],
                [
                    'id' => 'ErrorCode',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/Constants/ErrorCode.php',
                    'destination' => BASE_PATH . '/app/Constants/ErrorCode.php',
                ],
                [
                    'id' => 'InfoCode',
                    'description' => 'The message bag for validation.',
                    'source' => __DIR__ . '/../publish/Constants/InfoCode.php',
                    'destination' => BASE_PATH . '/app/Constants/InfoCode.php',
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
