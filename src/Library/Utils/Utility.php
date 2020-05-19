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

use App\Constants\ErrorCode;
use HyperfLib\Exception\ServiceException;
use Hyperf\Contract\ConfigInterface;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Snowflake\IdGenerator\SnowflakeIdGenerator;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Snowflake\Meta;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;

if (!function_exists('encode')) {
    /**
     * 统一封装的encode方法.
     *
     * @param $data
     * @param string $format
     * @return string
     */
    function encode($data, $format = 'json')
    {
        switch ($format) {
            case 'json':
                if (extension_loaded('jsond')) {
                    $ret = jsond_encode($data, JSON_UNESCAPED_UNICODE);
                } else {
                    $ret = json_encode($data, JSON_UNESCAPED_UNICODE);
                }
                break;
            case 'base64':
                $ret = base64_encode($data);
                break;
            case 'serialize':
                $ret = serialize($data);
                break;
            default:
                $ret = $data;
        }

        return $ret;
    }
}

if (!function_exists('decode')) {
    /**
     * 统一封装的decode方法.
     *
     * @param $data
     * @param string $format
     * @return mixed|string
     */
    function decode($data, $format = 'json')
    {
        switch ($format) {
            case 'json':
                if (extension_loaded('jsond')) {
                    $ret = jsond_decode($data, true);
                } else {
                    $ret = json_decode($data, true);
                }
                break;
            case 'base64':
                $ret = base64_decode($data);
                break;
            case 'serialize':
                $ret = unserialize($data);
                break;
            default:
                $ret = $data;
        }

        return $ret;
    }
}

if (!function_exists('generateSnowId')) {
    /**
     * 分布式全局唯一ID生成算法
     * @return int
     */
    function generateSnowId()
    {
        $container = ApplicationContext::getContainer();
        /**
         * @var SnowflakeIdGenerator $generator
         */
        $generator = $container->get(IdGeneratorInterface::class);
        return $generator->generate();
    }
}

if (!function_exists('degenerateSnowId')) {
    /**
     * 根据ID反推对应的Meta
     * @param $id
     * @return Meta
     */
    function degenerateSnowId($id)
    {
        $container = ApplicationContext::getContainer();
        /**
         * @var SnowflakeIdGenerator $generator
         */
        $generator = $container->get(IdGeneratorInterface::class);

        return $generator->degenerate($id);
    }
}

if (!function_exists('arrayGet')) {
    /**
     * 以“.”为分隔符获取多维数组的值
     *
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed
     */
    function arrayGet($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return value($default);
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

if (!function_exists('getUuid')) {
    /**
     * 获取数据库uuid.
     *
     * @return int uuid
     */
    function getUuid()
    {
        $ret = Db::select('select uuid_short() as uuid');
        $uuid = $ret[0] ?? null;
        if (!empty($uuid->uuid)) {
            return intval(substr("{$uuid->uuid}", -19));
        }
        throw new ServiceException('uuid error', [], ErrorCode::SERVER_CODE_ERROR);
    }
}

if (!function_exists('hideStr')) {

    /**
     * 隐藏字符串
     *
     * @param $str
     * @param string $symbol
     * @param int $count
     * @return string
     */
    function hideStr($str, int $count = 0, string $symbol = '*'): string
    {
        $str = strval($str);
        $len = mb_strlen($str, 'UTF-8');
        if ($len < 8) {
            return $str;
        }
        return mb_substr($str, 0, 3) . str_repeat($symbol, $count ?: $len - 6) . mb_substr($str, -3);
    }
}

if (!function_exists('rd_debug')) {

    /**
     * 本地调试
     *
     * @param $data
     */
    function rd_debug($data)
    {
        print_r(['data' => $data]);
    }
}

if (!function_exists('unCamelize')) {

    /**
     * 驼峰命名转下划线命名
     *
     * @param $words
     * @return string
     */
    function unCamelize($words)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $words));
    }
}

if (!function_exists('camelize')) {

    /**
     * 下划线命名转小驼峰命名
     *
     * @param $words
     * @return string
     */
    function camelize($words)
    {
        return lcfirst(str_replace([' ', '_', '-'], '', ucwords($words, ' _-')));
    }
}

if (!function_exists('bigCamelize')) {

    /**
     * 下划线命名转大驼峰命名
     *
     * @param $words
     * @return string
     */
    function bigCamelize($words)
    {
        $separator = '/';
        return preg_replace_callback("~(?<={$separator})([a-z])~", function ($matches) {
            return strtoupper($matches[0]);
        }, $separator . ltrim($words, $separator));
    }
}

if (!function_exists('getServerLocalIp')) {
    /**
     * 获取服务端内网ip地址
     *
     * @return string
     */
    function getServerLocalIp(): string
    {
        $ip = '127.0.0.1';
        $ips = array_values(swoole_get_local_ip());
        foreach ($ips as $v) {
            if ($v && $v != $ip) {
                $ip = $v;
                break;
            }
        }

        return $ip;
    }
}

if (!function_exists('input')) {
    /**
     * 参数请求
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    function input(string $name, $default = null)
    {
        $id = RequestInterface::class . ':params:' . $name;
        if (Context::has($id)) {
            return Context::get($id, $default);
        }

        /**
         * @var RequestInterface $request
         */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        return $request->input($name, $default);
    }
}


if (!function_exists('inputs')) {
    /**
     * 所有请求参数
     *
     * @return array
     */
    function inputs(): array
    {
        /**
         * @var RequestInterface $request
         */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        return $request->all();
    }
}

if (!function_exists('getConfig')) {
    /**
     * 配置文件获取
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function getConfig(string $key, $default = null)
    {
        return ApplicationContext::getContainer()->get(ConfigInterface::class)->get($key, $default);
    }
}
