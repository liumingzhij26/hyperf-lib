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
use App\Constants\ServerCode;
use Throwable;

class EmptyException extends ServiceException
{
    public function __construct(string $message = '数据不存在！', array $data = [], int $code = InfoCode::CODE_ERROR, Throwable $previous = null, int $httpStatus = ServerCode::BAD_REQUEST)
    {
        parent::__construct($message, $data, $code, $previous, $httpStatus);
    }
}
