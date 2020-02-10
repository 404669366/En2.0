<?php
/**
 * Created by PhpStorm.
 * User: miku
 * Date: 2019/2/28
 * Time: 12:38
 */

namespace Workerman\Protocols;

class Jm
{
    public static function input($buffer)
    {
        if (strlen($buffer) < 4) {
            return 0;
        }
        return unpack('vv', substr($buffer, 2, 2))['v'];
    }

    public static function decode($buffer)
    {
        $length = unpack('vv', substr($buffer, 2, 2))['v'];
        $checkPlus1 = self::checkPlus(substr($buffer, 6, $length - 7));
        $checkPlus2 = unpack('Cv', substr($buffer, -1))['v'];
        if ($checkPlus1 == $checkPlus2) {
            $data = [
                'cmd' => unpack('vv', substr($buffer, 6, 2))['v'],
                'no' => trim(unpack('a32v', substr($buffer, 12, 32))['v']),
            ];
            $buffer = substr($buffer, 8, $length - 9);
            switch ($data['cmd']) {
                case 4:
                    $data['type'] = unpack('Cv', substr($buffer, 36, 1))['v'];
                    $data['code'] = unpack('Vv', substr($buffer, 37, 4))['v'];
                    $data['result'] = unpack('Cv', substr($buffer, 41, 1))['v'];
                    break;
                case 6:
                    $data['gun'] = unpack('Cv', substr($buffer, 36, 1))['v'];
                    $data['code'] = unpack('Vv', substr($buffer, 37, 4))['v'];
                    $data['result'] = unpack('Cv', substr($buffer, 42, 1))['v'];
                    if ($data['code'] == 2) {
                        $data['cmd'] = 62;
                    }
                    break;
                case 8:
                    $data['gun'] = unpack('Cv', substr($buffer, 36, 1))['v'];
                    $data['result'] = unpack('Vv', substr($buffer, 37, 4))['v'];
                    $data['orderNo'] = trim(unpack('a32v', substr($buffer, 41, 32))['v']);
                    break;
                case 102:
                    $data['heartNo'] = unpack('vv', substr($buffer, 36, 2))['v'];
                    break;
                case 104:
                    $data['gunCount'] = unpack('Cv', substr($buffer, 36, 1))['v'];
                    $data['gun'] = unpack('Cv', substr($buffer, 37, 1))['v'];
                    $data['gunType'] = unpack('Cv', substr($buffer, 38, 1))['v'];
                    $data['workStatus'] = unpack('Cv', substr($buffer, 39, 1))['v'];
                    $data['soc'] = unpack('Cv', substr($buffer, 40, 1))['v'];
                    $data['v'] = unpack('vv', substr($buffer, 59, 2))['v'];
                    $data['i'] = unpack('vv', substr($buffer, 61, 2))['v'];
                    $data['linkStatus'] = unpack('Cv', substr($buffer, 45, 1))['v'];
                    $data['remainingTime'] = unpack('vv', substr($buffer, 79, 2))['v'];
                    $data['duration'] = unpack('Vv', substr($buffer, 81, 4))['v'];
                    $data['e'] = round(unpack('Vv', substr($buffer, 85, 4))['v'] / 100, 2);
                    $data['power'] = unpack('Vv', substr($buffer, 153, 4))['v'];
                    break;
                case 106:
                    $data['count'] = unpack('Cv', substr($buffer, 51, 1))['v'];
                    $data['random'] = unpack('Vv', substr($buffer, 90, 4))['v'];
                    break;
                case 108:
                    $data['alarmInfo'] = self::parseBin(substr($buffer, -32, 32));
                    break;
                case 110:
                    $data['gun'] = unpack('Cv', substr($buffer, 36, 1))['v'];
                    $data['failType'] = unpack('Vv', substr($buffer, 37, 4))['v'];
                    $data['sendType'] = unpack('vv', substr($buffer, 41, 2))['v'];
                    break;
                case 202:
                    $data['gun'] = unpack('Cv', substr($buffer, 37, 1))['v'];
                    $data['cardNo'] = unpack('a32v', substr($buffer, 38, 32))['v'];
                    $data['duration'] = unpack('Vv', substr($buffer, 86, 4))['v'];
                    $data['e'] = round(unpack('Vv', substr($buffer, 96, 4))['v'] / 100, 2);
                    $data['index'] = unpack('Vv', substr($buffer, 112, 4))['v'];
                    $data['orderNo'] = trim(unpack('a32v', substr($buffer, 256, 32))['v']);
                    break;
            }
            return $data;
        }
        return [];
    }

    public static function encode($params)
    {
        $buffer = pack('v', 0);
        $buffer .= pack('v', 0);
        switch ($params['cmd']) {
            case 3:
                $buffer .= pack('C', $params['type']);
                $buffer .= pack('V', $params['code']);
                $buffer .= pack('v', strlen($params['val']));
                $buffer .= $params['val'];
                break;
            case 5:
                $buffer .= pack('C', $params['gun']);
                $buffer .= pack('V', $params['code']);
                $buffer .= pack('C', 1);
                $buffer .= pack('v', 4);
                $buffer .= pack('V', $params['val']);
                break;
            case 7:
                $buffer .= pack('C', $params['gun']);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= self::getTime();
                $buffer .= pack('C', 0);
                $buffer .= pack('a32', $params['orderNo']);
                $buffer .= pack('C', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('a32', $params['orderNo']);
                break;
            case 101:
                $buffer .= pack('v', $params['times']);
                break;
            case 103:
                $buffer .= pack('C', $params['gun']);
                break;
            case 105:
                $buffer .= pack('V', $params['random']);
                $buffer .= pack('C', 0);
                $buffer .= pack('C', 0);
                $buffer .= pack('a', 128);
                $buffer .= pack('V', 0);
                $buffer .= pack('C', 0);
                break;
            case 201:
                $buffer .= pack('C', $params['gun']);
                $buffer .= pack('a32', $params['cardNo']);
                $buffer .= pack('V', $params['index']);
                $buffer .= pack('C', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                $buffer .= pack('V', 0);
                break;
        }
        return self::composeMsg($params['cmd'], $buffer);
    }

    /**
     * 组装报文
     * @param int $cmd
     * @param string $data
     * @return string
     */
    private static function composeMsg($cmd = 0, $data = '')
    {
        $msg = pack('v', 62890);
        $msg .= pack('v', strlen($data) + 9);
        $msg .= pack('C', 0);
        $msg .= pack('C', 0);
        $msg .= pack('v', $cmd);
        $msg .= $data;
        $msg .= pack('C', self::checkPlus(substr($msg, 6, strlen($data) + 2)));
        return $msg;
    }

    /**
     * 计算校验和
     * @param string $buffer
     * @return int
     */
    private static function checkPlus($buffer = '')
    {
        $bufferArr = str_split($buffer);
        $plus = 0;
        foreach ($bufferArr as $v) {
            $plus += (int)base_convert(bin2hex($v), 16, 10);
        }
        return $plus & 0xFF;
    }

    /**
     * 解析二进制信息
     * @param string $buffer
     * @return string
     */
    private static function parseBin($buffer = '')
    {
        $bufferArr = str_split($buffer);
        $status = '';
        foreach ($bufferArr as $v) {
            $status .= str_pad(base_convert(bin2hex($v), 16, 2), 8, 0, STR_PAD_LEFT);
        }
        return $status;
    }

    /**
     * 获取时间
     * @param int $timeStamp
     * @return string
     */
    private static function getTime($timeStamp = 0)
    {
        $timeStamp = $timeStamp ?: time();
        $timeArr = str_split(date('YmdHis', $timeStamp + 8 * 3600), 2);
        $timeStr = '';
        foreach ($timeArr as $v) {
            $timeStr .= pack('C', (int)$v);
        }
        return $timeStr . pack('C', 255);
    }
}