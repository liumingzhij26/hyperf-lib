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

namespace HyperfLib\Listener\Server;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\OnWorkerExit;
use HyperfLib\Library\Logger\Logger;


class WorkerExitHandleListener implements ListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function listen(): array
    {
        return [
            OnWorkerExit::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof OnWorkerExit) {
            Logger::get()->info(sprintf('event: %s , server: %s, worker_id: %d',
                OnWorkerExit::class,
                $event->server,
                $event->workerId
            ));
        }
    }
}
