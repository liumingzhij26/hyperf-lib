<?php

declare(strict_types=1);

namespace HyperfLib\Service;

use HyperfLib\Contract\LockInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class BaseService
{

    /**
     * 锁，目前是使用 redis 现实.
     *
     * @Inject
     * @var LockInterface
     */
    protected $lock;

    /**
     * 触发事件.
     *
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
}
