<?php
declare(strict_types=1);

namespace HyperfLib\Exception\Handler;

use HyperfLib\Exception\BusinessException;
use HyperfLib\Exception\EmptyException;
use HyperfLib\Exception\ServiceException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 业务异常处理
 *
 * Class ServiceExceptionHandler
 * @package HyperfLib\Exception\Handler
 */
class ServiceExceptionHandler extends ExceptionHandler
{

    /**
     * Handle the exception, and return the specified result.
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 阻止异常冒泡
        $this->stopPropagation();
        /**
         * @var ServiceException $throwable
         */
        $data = $throwable->getData();
        $result = $this->serviceResponse->showError(
            $throwable->getMessage(),
            array_merge_recursive($data, ['exception' => get_class($throwable)]),
            $throwable->getCode()
        );
        return $response->withBody(new SwooleStream(encode($result)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ServiceException ||
            $throwable instanceof BusinessException ||
            $throwable instanceof EmptyException;
    }
}
