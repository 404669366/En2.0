<?php
/**
 * Created by PhpStorm.
 * User: d
 * Date: 2018/3/31
 * Time: 10:40
 */

namespace vendor\project\helpers;


class Helper
{
    /**
     * 处理特殊字符
     * @param string $str
     * @return mixed|string
     */
    public static function handleStr($str = '')
    {
        if ($str) {
            $str = str_replace('\'', '&#39;', $str);
            $str = str_replace('"', '&quot;', $str);
            $str = str_replace("\r\n", '<br/>', $str);
            return $str;
        }
        return '';
    }

    /**
     * 获取指定时间对应周所有日期
     * @param int $time
     * @return array
     */
    public static function getWeekDay($time = 0)
    {
        $time = $time != '' ? $time : time();
        $week = date('w', $time);
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[$i] = date('Y-m-d', strtotime('+' . $i - $week . ' days', $time));
        }
        return $date;
    }

    /**
     * 百度坐标系 (BD-09) 与 火星坐标系 (GCJ-02)的转换
     * 即 百度 转 谷歌、高德、腾讯
     * @param $lat
     * @param $lng
     * @return array
     */
    public static function bd09ToGcj02($lat, $lng)
    {
        $x = (double)$lng - 0.0065;
        $y = (double)$lat - 0.006;
        $x_pi = 3.14159265358979324;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $gb = number_format($z * cos($theta), 15);
        $ga = number_format($z * sin($theta), 15);
        return ['lat' => $ga, 'lng' => $gb];
    }

    /**
     * 拼接当前域名路由
     * @param string $url
     * @return string
     */
    public static function spliceUrl($url = '')
    {
        return \Yii::$app->request->hostInfo . $url;
    }

    /**
     * 将数组key合并进value里
     * @param array $arr
     * @return array
     */
    public static function arrayKeyToV($arr = [])
    {
        $new = [];
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $v['k'] = $k;
                array_push($new, $v);
            } else {
                array_push($new, ['k' => $k, 'v' => $v]);
            }
        }
        return $new;
    }

    /**
     * 多维数组排序
     * eg: self::arraySort($arr, 'id', SORT_DESC, 'num', SORT_DESC)
     * @return array|mixed
     */
    public static function arraySort()
    {
        $funcArgs = func_get_args();
        if (empty($funcArgs)) {
            return [];
        }
        $arr = array_shift($funcArgs);
        if (!is_array($arr)) {
            return [];
        }
        foreach ($funcArgs as $key => $value) {
            if (is_string($value)) {
                $tempArr = array();
                foreach ($arr as $k => $v) {
                    $tempArr[$k] = $v[$value];
                }
                $funcArgs[$key] = $tempArr;
            }
        }
        $funcArgs[] = &$arr;
        call_user_func_array('array_multisort', $funcArgs);
        return array_pop($funcArgs);
    }

    /**
     * 补全图片
     * @param string $img
     * @param int $length
     * @return array
     */
    public static function completionImg($img = '', $length = 5)
    {
        $img = explode(',', $img);
        $count = count($img);
        if ($count < $length) {
            $completion = [];
            while (count($completion) < ($length - $count)) {
                array_push($completion, $img[array_rand($img)]);
            }
            return array_merge($img, $completion);
        }
        return $img;
    }

    /**
     * 生成编号
     * @param string $prefix
     * @return string 不加前缀14位
     */
    public static function createNo($prefix = '')
    {
        $prefix .= date('YmdH') . mt_rand(1000, 9999);
        return $prefix;
    }

    /**
     * 导出excle
     * @param array $data
     * @param array $title
     * @param string $filename
     */
    public static function excel($title = [], $data = [], $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $re = "<table border='1'><thead>";
        if ($title) {
            $re .= "<tr>";
            foreach ($title as $k => $v) {
                $re .= "<th style='background-color:rgb(189,215,238);'>" . iconv("UTF-8", "GBK//IGNORE", $v) . "</th>";
            }
        }
        $re .= "</tr></thead><tbody>";
        if ($data) {
            foreach ($data as $key => $val) {
                $re .= "<tr>";
                foreach ($val as $ck => $cv) {
                    $re .= "<td>" . iconv("UTF-8", "GBK//IGNORE", $cv) . "</td>";
                }
                $re .= "</tr>";
            }
            $re .= "</tbody></table>";
        }
        echo $re;
        exit();
    }

    /**
     * 导出复杂excle
     * @param array $data
     * @param string $filename
     */
    public static function complexExcel($data = [], $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $excel = "<table style='text-align: right' border='1'>";
        foreach ($data as $k => $v) {
            $blockInfo = explode('-', $k);
            $name = iconv("UTF-8", "GBK//IGNORE", $blockInfo[0]);
            $count = $blockInfo[1];
            $excel .= "<tr style='text-align: center'><td colspan='$count'>$name</td></tr>";
            foreach ($v as $row) {
                $excel .= "<tr>";
                foreach ($row as $one) {
                    $style = " style='background-color:rgb(189,215,238);'";
                    if (strpos($one, '!T') === false) {
                        $style = '';
                    }
                    $info = iconv("UTF-8", "GBK//IGNORE", str_replace('!T', '', $one));
                    $excel .= "<td$style>$info</td>";
                }
                $excel .= "</tr>";
            }
        }
        $excel .= "</table>";
        echo $excel;
        exit();
    }


    /**
     * 根据经纬度计算距离
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param float $radius
     * @param int $round
     * @return float KM
     */
    public static function distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.137, $round = 2)
    {
        $rad = floatval(M_PI / 180.0);
        $lat1 = floatval($lat1) * $rad;
        $lon1 = floatval($lon1) * $rad;
        $lat2 = floatval($lat2) * $rad;
        $lon2 = floatval($lon2) * $rad;
        $theta = $lon2 - $lon1;
        $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));
        if ($dist < 0) {
            $dist += M_PI;
        }
        $dist = $dist * $radius;
        return round($dist, $round);
    }

    /**
     * 计算时长
     * @param int $begin
     * @param int $end
     * @return string
     */
    public static function timeLong($begin = 0, $end = 0)
    {
        $duration = '00:00:00';
        if ($begin && $end && $begin <= $end) {
            //计算天数
            $timeDiff = $end - $begin;
            $day = intval($timeDiff / 86400);
            if ($day) {
                $res['日'] = $day;
            }
            //计算小时数
            $remain = $timeDiff % 86400;
            $res['小时'] = intval($remain / 3600);
            //计算分钟数
            $remain = $remain % 3600;
            $res['分'] = intval($remain / 60);
            //计算秒数
            $res['秒'] = $remain % 60;
            $duration = implode(':', $res);
        }
        return $duration;
    }

    /**
     * 计算时长
     * @param int $begin
     * @param int $end
     * @return string
     */
    public static function duration($begin = 0, $end = 0)
    {
        $duration = '';
        if ($begin && $end && $begin <= $end) {
            //计算天数
            $timeDiff = $end - $begin;
            $res['日'] = intval($timeDiff / 86400);
            //计算小时数
            $remain = $timeDiff % 86400;
            $res['小时'] = self::repair(intval($remain / 3600), 2, 0);
            //计算分钟数
            $remain = $remain % 3600;
            $res['分'] = self::repair(intval($remain / 60), 2, 0);
            //计算秒数
            $res['秒'] = self::repair($remain % 60, 2, 0);
            foreach ($res as $k => $v) {
                $val = (int)$v;
                if ($val) {
                    $duration .= $v . $k;
                }
            }
        }
        return $duration;
    }

    /**
     * 补全
     * @param $str
     * @param $len
     * @param $rep
     * @param int $type
     * @return string
     */
    public static function repair($str, $len, $rep, $type = 1)
    {
        $length = $len - strlen($str);
        if ($length < 1) return $str;
        if ($type == 1) {
            $str = str_repeat($rep, $length) . $str;
        } else {
            $str .= str_repeat($rep, $length);
        }
        return $str;
    }

    /**
     * 返回数组维度
     * @param array $array
     * @return int|mixed
     */
    public static function arrayDepth($array = [])
    {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::arrayDepth($value) + 1;
                $max_depth = max($max_depth, $depth);
            }
        }
        return $max_depth;
    }

    /**
     * 生成唯一字符串
     * @param int $type 字符串的类型
     *   0-存数字字符串；1-小写字母字符串；2-大写字母字符串；3-大小写数字字符串；4-字符；
     *   5-数字，小写，大写，字符混合；6-小写数字字符串；7-大写数字字符串
     * @param int $length 字符串的长度
     * @param int $time [是否带时间1-带，0-不带
     * @return false|string
     */
    public static function randStr($type = 0, $length = 18, $time = 0)
    {
        $str = $time == 0 ? '' : date('YmdHis', time());
        switch ($type) {
            case 0:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $str .= rand(0, 9);
                    }
                }
                break;
            case 1:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "qwertyuioplkjhgfdsazxcvbnm";
                        $str .= $rand{mt_rand(0, 26)};
                    }
                }
                break;
            case 2:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "QWERTYUIOPLKJHGFDSAZXCVBNM";
                        $str .= $rand{mt_rand(0, 26)};
                    }
                }
                break;
            case 3:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
                        $str .= $rand{mt_rand(0, 35)};
                    }
                }
                break;
            case 4:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "!@#$%^&*()_+=-~`";
                        $str .= $rand{mt_rand(0, 17)};
                    }
                }
                break;
            case 5:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM!@#$%^&*()_+=-~`";
                        $str .= $rand{mt_rand(0, 52)};
                    }
                }
                break;
            case 6:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "1234567890qwertyuioplkjhgfdsazxcvbnm";
                        $str .= $rand{mt_rand(0, 35)};
                    }
                }
                break;
            case 7:
                for ((int)$i = 0; $i <= $length; $i++) {
                    if (mb_strlen($str) == $length) {
                        $str = $str;
                    } else {
                        $rand = "1234567890QWERTYUIOPLKJHGFDSAZXCVBNM";
                        $str .= $rand{mt_rand(0, 35)};
                    }
                }
                break;
        }
        return $str;
    }

    /**
     * 获取用户IP
     * @return string
     */
    public static function getIp()
    {
        $arr_ip_header = array(
            'HTTP_CDN_SRC_IP',
            'HTTP_PROXY_CLIENT_IP',
            'HTTP_WL_PROXY_CLIENT_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        );
        $client_ip = 'unknown';
        foreach ($arr_ip_header as $key) {
            if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown') {
                $client_ip = $_SERVER[$key];
                break;
            }
        }
        return $client_ip;
    }

    /**
     * 时间戳转换
     * @param int $timestamp
     * @param string $format
     * @return false|string
     */
    public static function realTime($timestamp = 0, $format = 'Y-m-d H:i:s')
    {
        return date($format, $timestamp);
    }

    /**
     * 验证手机号合法性
     * @param string $tel
     * @return bool
     */
    public static function validateTel($tel = '')
    {
        if ($tel) {
            if (preg_match("/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/", $tel)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取关联数组的上一个键或下一个键
     * @param array $arr
     * @param string $now 当前键
     * @param string $do next/prev
     * @return string
     */
    public static function steps($arr = [], $now = '', $do = 'next')
    {
        if ($arr && $now && $do) {
            $arr = array_keys($arr);
            $now = array_search($now, $arr);
            if ($do == 'next' && isset($arr[$now + 1])) {
                return $arr[$now + 1];
            }
            if ($do == 'prev' && isset($arr[$now - 1])) {
                return $arr[$now - 1];
            }
        }
        return '';
    }

    /**
     * 将二维数组里某个键的值作为一维的键
     * @param string $key
     * @param array $arr
     * @return array
     */
    public static function changeKey($key = '', $arr = [])
    {
        $new = [];
        if ($key && $arr) {
            foreach ($arr as $v) {
                $new[$v[$key]] = $v;
            }
        }
        return $new;
    }

    /**
     * 一维数组转字符串
     * @param array $arr
     * @param string $link 链接符号
     * @param int $wrap 换行位置
     * @return string
     */
    public static function arrToStr($arr = [], $link = '', $wrap = 0)
    {
        $str = '';
        if ($arr) {
            $count = count($arr);
            foreach ($arr as $k => $v) {
                if ($count - 1 == $k) {
                    $str .= $v;
                } elseif (($k + 1) % $wrap == 0) {
                    $str .= $v . '<br>';
                } else {
                    $str .= $v . $link;
                }
            }
        }
        return $str;
    }

    /**
     * 一维数组截取
     * @param array $arr
     * @param string $beginKey
     * @param string $endKey
     * @return array
     */
    public static function arrSlice($arr = [], $beginKey = '', $endKey = '')
    {
        if ($arr && $beginKey) {
            $i = 0;
            $begin = 0;
            $end = 0;
            foreach ($arr as $k => $v) {
                if ($k == $beginKey) {
                    $begin = $i;
                }
                if ($k == $endKey) {
                    $end = $i;
                }
                $i++;
            }
            if ($begin && $end) {
                return array_slice($arr, $begin, $end - $begin + 1, true);
            }
            if ($begin) {
                return array_slice($arr, $begin, null, true);
            }
        }
        return $arr;
    }

    /**
     * 取出一维数组值并删除
     * @param array $data
     * @param string $key
     * @return bool|mixed
     */
    public static function arrGetV(&$data = [], $key = '')
    {
        if ($data && $key && isset($data[$key])) {
            $value = $data[$key];
            unset($data[$key]);
            return $value;
        }
        return false;
    }

    /**
     * 设置数组的值
     * @param array $arr
     * @param string $value
     */
    public static function arrSet(&$arr = [], $value = '')
    {
        if ($arr) {
            foreach ($arr as &$v) {
                $v = $value;
            }
        }
    }

    /**
     * 给定目录,没有就创建
     * @param $name
     * @return bool
     */
    public static function mkDir($name)
    {
        if (file_exists($name)) {
            return true;
        }
        $dir = iconv("UTF-8", "GBK", $name);
        return mkdir($dir, 0777, true);
    }

    /**
     * 给定文件/目录,有就删除
     * @param $name
     * @return bool
     */
    public static function delDir($name)
    {
        if (file_exists($name)) {
            return unlink($name);
        }
        return true;
    }

    /**
     * 下载远程文件
     * @param $url
     * @param string $save_dir
     * @param string $filename
     * @param int $type
     * @return bool|string
     */
    public static function getRemoteFile($url, $save_dir = '', $filename = '', $type = 0)
    {
        if (trim($url) == '') {
            return false;
        }
        if (trim($save_dir) == '') {
            $save_dir = './';
        }
        if (0 !== strrpos($save_dir, '/')) {
            $save_dir .= '/';
        }
        if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
            return false;
        }
        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $content = ob_get_contents();
            ob_end_clean();
        }
        $fp2 = @fopen($save_dir . $filename, 'a');
        fwrite($fp2, $content);
        fclose($fp2);
        unset($content, $url);

        return $save_dir . $filename;
    }

    /**
     * curlPost
     * @param string $url
     * @param array $data
     * @param bool $isJson
     * @return mixed
     */
    public static function curlPost($url = '', $data = [], $isJson = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($isJson) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($data)
            ]);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }

    /**
     * curlGet
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public static function curlGet($url = '', $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }

    /**
     * 发送xml
     * @param string $url
     * @param string $xmlData
     * @param int $second
     * @return array|bool|mixed
     */
    public static function curlXml($url = '', $xmlData = '', $second = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            $result = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $result;
    }

    /**
     * 接收xml数据
     * @param string $key
     * @param string $default
     * @return array|mixed|string
     */
    public static function getXml($key = '', $default = '')
    {
        $file_in = file_get_contents("php://input"); //接收post数据
        $xml = (array)simplexml_load_string($file_in, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($key) {
            if (isset($xml[$key])) {
                return $xml[$key];
            } else {
                return $default;
            }
        }
        return $xml;
    }

    /**
     * 构建xml数据
     * @param array $data
     * @return string
     */
    public static function spliceXml($data = [])
    {
        $xml = '<xml>';
        foreach ($data as $k => $v) {
            $xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
        }
        $xml .= '</xml>';

        echo $xml;
        exit();
    }
}