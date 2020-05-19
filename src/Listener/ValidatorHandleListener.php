<?php


namespace HyperfLib\Listener;

use HyperfLib\Contract\ValidatorExtendInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Request;
use Hyperf\Utils\Collection;
use Hyperf\Validation\Event\ValidatorFactoryResolved;
use Psr\Container\ContainerInterface;

class ValidatorHandleListener implements ListenerInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $config;

    public function __construct(ContainerInterface $container, RequestInterface $request)
    {
        $this->container = $container;
        $this->request = $request;
        $this->config = $container->get(ConfigInterface::class)->get('validation.extend');
    }

    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof ValidatorFactoryResolved) {
            $validatorFactory = $event->validatorFactory;
            // 注册了 自定义验证器
            $arrList = new Collection($this->config);
            foreach ($arrList as $className) {
                $class = make($className);
                $status = $class instanceof ValidatorExtendInterface &&
                    method_exists($class, 'extend') &&
                    method_exists($class, 'replacer');
                if ($status) {
                    // 规则验证
                    $validatorFactory->extend($class->getRuleName(), sprintf("%s@extend", $className));
                    // 当创建一个自定义验证规则时，你可能有时候需要为错误信息定义自定义占位符这里扩展了 :foo 占位符
                    $validatorFactory->replacer($class->getRuleName(), sprintf("%s@replacer", $className));
                }
            }
        }
    }
}
