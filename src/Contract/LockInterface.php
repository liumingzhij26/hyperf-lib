<?php


namespace HyperfLib\Contract;

/**
 * Interface LockInterface
 * @package HyperfLib\Contract
 */
interface LockInterface
{

    /**
     * 获得锁
     *
     * @param $keyName
     * @param int $ttl
     * @return mixed
     */
    public function lock($keyName, int $ttl = 1000);

    /**
     * 解锁
     *
     * @param array $lock
     * @return mixed
     */
    public function unlock(array $lock);
}
