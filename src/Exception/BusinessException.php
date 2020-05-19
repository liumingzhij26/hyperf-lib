<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace HyperfLib\Exception;

use App\Constants\ErrorCode;
use App\Constants\InfoCode;
use Throwable;

/**
 * 业务异常，只能抛 code
 *
 * Class BusinessException
 * @package HyperfLib\Exception
 */
class BusinessException extends ServiceException
{
    public function __construct(int $code, array $replace = [], array $data = [], Throwable $previous = null, int $httpStatus = ErrorCode::BAD_REQUEST)
    {
        $message = InfoCode::getMessage($code, $replace);

        if (empty($message)) {//如果返回信息为空，就抛默认的错误
            $message = InfoCode::getMessage(InfoCode::CODE_ERROR_NULL, ['code' => $code]);
        }

        parent::__construct((string)$message, $data, $code, $previous, $httpStatus);
    }
}
