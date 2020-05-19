<?php

declare(strict_types=1);

namespace HyperfLib\Library\Http;

use HyperfLib\Constants\InfoCode;
use HyperfLib\Contract\ResponseInterface;
use HyperfLib\Exception\ServiceException;
use Hyperf\Utils\Context;

/**
 * Class Response.
 * @property array|mixed result
 * @property string msg
 * @property int code
 * @property string action
 */
class ServiceResponse implements ResponseInterface
{
    private $params = [
        'result',
        'code',
        'msg',
        'action',
    ];

    final public function __get($name)
    {
        if (!in_array($name, $this->params)) {
            throw new ServiceException('error param', ['name' => $name]);
        }
        return Context::get(__CLASS__ . ':' . $name);
    }

    final public function __set($name, $value)
    {
        if (!in_array($name, $this->params)) {
            throw new ServiceException('error param', [$name => $value]);
        }
        return Context::set(__CLASS__ . ':' . $name, $value);
    }

    /**
     * {@inheritdoc}
     */
    final public function showResult($result, $msg = '', $code = 0, $action = 'toast')
    {
        $this->result = $result;
        $this->msg = $msg;
        $this->code = $code;
        $this->action = $action;
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    final public function showError($error, array $result = [], $code = InfoCode::CODE_ERROR, $action = 'toast')
    {
        return $this->showResult($result, $error, $code);
    }

    /**
     * {@inheritdoc}
     */
    final public function showSuccess($msg = '', $action = 'toast')
    {
        return $this->showResult(['status' => true], $msg ?: 'success');
    }

    private function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => [
                'text' => $this->msg,
                'action' => $this->action,
            ],
            'result' => $this->result,
        ];
    }
}
