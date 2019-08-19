<?php

namespace vendor\project\helpers;
/**
 *  Global data client.
 * @version 1.0.3
 */
class client
{
    /**
     * Timeout.
     * @var int
     */
    public $timeout = 5;

    /**
     * Heartbeat interval.
     * @var int
     */
    public $pingInterval = 25;

    /**
     * Global data server address.
     * @var array
     */
    protected $_globalServers = array();

    /**
     * Connection to global server.
     * @var resource
     */
    protected $_globalConnections = null;

    /**
     * Cache.
     * @var array
     */
    protected $_cache = array();

    /**
     * client constructor.
     * @param string $servers
     * @throws \Exception
     */
    public function __construct($servers = '127.0.0.1:30001')
    {
        if (empty($servers)) {
            throw new \Exception('servers empty');
        }
        $this->_globalServers = array_values((array)$servers);
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    protected function getConnection($key)
    {
        $offset = crc32($key) % count($this->_globalServers);
        if ($offset < 0) {
            $offset = -$offset;
        }

        if (!isset($this->_globalConnections[$offset]) || !is_resource($this->_globalConnections[$offset]) || feof($this->_globalConnections[$offset])) {
            $connection = stream_socket_client("tcp://{$this->_globalServers[$offset]}", $code, $msg, $this->timeout);
            if (!$connection) {
                throw new \Exception($msg);
            }
            stream_set_timeout($connection, $this->timeout);
            if (class_exists('\Workerman\Lib\Timer') && php_sapi_name() === 'cli') {
                $timer_id = \Workerman\Lib\Timer::add($this->pingInterval, function ($connection) use (&$timer_id) {
                    $buffer = pack('N', 8) . "ping";
                    if (strlen($buffer) !== @fwrite($connection, $buffer)) {
                        @fclose($connection);
                        \Workerman\Lib\Timer::del($timer_id);
                    }
                }, array($connection));
            }
            $this->_globalConnections[$offset] = $connection;
        }
        return $this->_globalConnections[$offset];
    }


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function __set($key, $value)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'set',
            'key' => $key,
            'value' => $value,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return null !== $this->__get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __unset($key)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'delete',
            'key' => $key
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'get',
            'key' => $key,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $key
     * @param $old_value
     * @param $new_value
     * @return mixed
     */
    public function cas($key, $old_value, $new_value)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'cas',
            'md5' => md5(serialize($old_value)),
            'key' => $key,
            'value' => $new_value,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function add($key, $value)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'add',
            'key' => $key,
            'value' => $value,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $key
     * @param int $step
     * @return mixed
     */
    public function increment($key, $step = 1)
    {
        $connection = $this->getConnection($key);
        $this->writeToRemote(array(
            'cmd' => 'increment',
            'key' => $key,
            'step' => $step,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param string $hashKey
     * @param string $value
     * @param bool $increment
     * @return mixed
     */
    public function hSet($hashName = '', $hashKey = '', $value = '', $increment = false)
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hSet',
            'key' => $hashName,
            'hKey' => $hashKey,
            'value' => $value,
            'increment' => $increment,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param string $hashKey
     * @param string $field
     * @param string $value
     * @param bool $increment
     * @return mixed
     */
    public function hSetField($hashName = '', $hashKey = '', $field = '', $value = '', $increment = false)
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hSetField',
            'key' => $hashName,
            'hKey' => $hashKey,
            'field' => $field,
            'value' => $value,
            'increment' => $increment,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param string $hashKey
     * @return mixed
     */
    public function hGet($hashName = '', $hashKey = '')
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hGet',
            'key' => $hashName,
            'hKey' => $hashKey,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param string $hashKey
     * @param string $field
     * @return mixed
     */
    public function hGetField($hashName = '', $hashKey = '', $field = '')
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hGetField',
            'key' => $hashName,
            'hKey' => $hashKey,
            'field' => $field,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param int $start
     * @param int $length
     * @return mixed
     */
    public function hPageGet($hashName = '', $start = 0, $length = 10)
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hPageGet',
            'key' => $hashName,
            'start' => $start,
            'length' => $length,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @return mixed
     */
    public function hGetAll($hashName = '')
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hGetAll',
            'key' => $hashName,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @return mixed
     */
    public function hLen($hashName = '')
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hLen',
            'key' => $hashName,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param string $hashName
     * @param string $hashKey
     * @return mixed
     */
    public function hDel($hashName = '', $hashKey = '')
    {
        $connection = $this->getConnection($hashName);
        $this->writeToRemote(array(
            'cmd' => 'hDel',
            'key' => $hashName,
            'hKey' => $hashKey,
        ), $connection);
        return $this->readFromRemote($connection);
    }

    /**
     * @param $data
     * @param $connection
     * @throws \Exception
     */
    protected function writeToRemote($data, $connection)
    {
        $buffer = serialize($data);
        $buffer = pack('N', 4 + strlen($buffer)) . $buffer;
        $len = fwrite($connection, $buffer);
        if ($len !== strlen($buffer)) {
            throw new \Exception('writeToRemote fail');
        }
    }

    /**
     * @param $connection
     * @return mixed
     * @throws \Exception
     */
    protected function readFromRemote($connection)
    {
        $all_buffer = '';
        $total_len = 4;
        $head_read = false;
        while (1) {
            $buffer = fread($connection, 8192);
            if ($buffer === '' || $buffer === false) {
                throw new \Exception('readFromRemote fail');
            }
            $all_buffer .= $buffer;
            $recv_len = strlen($all_buffer);
            if ($recv_len >= $total_len) {
                if ($head_read) {
                    break;
                }
                $unpack_data = unpack('Ntotal_length', $all_buffer);
                $total_len = $unpack_data['total_length'];
                if ($recv_len >= $total_len) {
                    break;
                }
                $head_read = true;
            }
        }
        @fclose($connection);
        return self::mb_unserialize(substr($all_buffer, 4));
    }

    protected static function mb_unserialize($str)
    {
        return unserialize(preg_replace_callback('#s:(\d+):"(.*?)";#s', function ($match) {
            return 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        }, $str));
    }
}
