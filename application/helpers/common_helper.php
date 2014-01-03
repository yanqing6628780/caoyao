<?php
/*
 *常用函数
 *
 */

/**
* 得到新订单号
* @return  string
*/
function get_order_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
}


/**
 * Get domain
 *
 * Return the domain name only based on the "base_url" item from your config file.
 *
 * @access    public
 * @return    string
 */

function getDomain()
{
    $CI =& get_instance();
    return 'http://'.preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $CI->config->slash_item('base_url'));
}

/**
 * 后台登录检查
 *
 */
function checkIsLoggedIn()
{
    $CI = get_instance();
    if(!$CI->dx_auth->is_logged_in()):
        redirect(site_url('admin/auth'));
        exit();
    endif;
}

/**
 * 权限检查
 * 没有权限，显示系统信息页
 *
 * @param string $perm
 */
function checkPermission($perm)
{
    $CI = get_instance();
    if ($CI->dx_auth->get_permission_value($perm) == NULL or !$CI->dx_auth->get_permission_value($perm))
    {
        exit('<script type="text/javascript">alert("你没有使用权限");');
    }
}

function checkPermission2($perm)
{
    $CI = get_instance();
    if ($CI->dx_auth->get_permission_value($perm) == NULL or ! $CI->dx_auth->get_permission_value($perm))
    {
		return false;
	}else{
		return true;
	}
}

/**
 * 角色权限检查
 * 没有权限，显示系统信息页
 *
 * @param string $perm
 */
function checkRoles($roles = array())
{
    $CI = get_instance();
    if ( ! is_array($roles))
    {
        $roles = array($roles);
    }
    if (!$CI->dx_auth->is_role($roles))
    {
        exit('<script type="text/javascript">alert("你没有使用权限");');
    }
}

/**
 * 返回文件后缀
 *
 *
 * @param string $filename
 * return string $ext
 */
function getLowerExt($filename)
{
    preg_match('|\.(\w+)$|', $filename, $ext);
    $ext = strtolower($ext[1]);
    return $ext;
}

/**
 * 检查上传文件是否为允许类型
 *
 * @param string $allowed_types
 * @param string $ext
 * return Boolean
 */
function checkAllowedTypes($allowed_type, $ext)
{
    $allowed_types = explode("|", $allowed_type); //允许上传的文件类型组
    return in_array($ext, $allowed_types);
}

/**
 * 地区三维数组
 *
 * return array
 */
function getRegionArray($user_id)
{
    $CI = get_instance();
    $CI->load->model('dx_auth/users', 'users');
    $CI->load->model('general_mdl');
    $CI->load->model('shopex/region_mdl', 'region_mdl');
    $CI->load->model('users_region_mdl', 'ur_mdl');

    $query = $CI->ur_mdl->get_query_by_where( array('user_id' => $user_id) );
    $userReigon = $query->row_array() ? $query->row_array() : array();//用户拥有的地区列表
    $userReigon['region_ids'] = '';

    $regions_list = $userReigon ? explode(",", $userReigon['region_ids']) : array();//转为数组
    //获得一级地区
    $where['region_grade'] = 1;
    $regions = $CI->region_mdl->get_query_by_where($where)->result_array();

    foreach($regions as $key => $row){
        $region_arr[$row['region_id']] = $row;
        $region_arr[$row['region_id']]['cando'] = in_array($row['local_name'], $regions_list) || $userReigon['region_ids'] == 'all' ? 1 : 0;
    }

    //获得二级地区
    $where['region_grade'] = 2;
    $regions = $CI->region_mdl->get_query_by_where($where)->result_array();

    foreach($regions as $key => $row){
        $region_arr[$row['p_region_id']]['children'][$row['region_id']] = $row;
        $region_arr[$row['p_region_id']]['children'][$row['region_id']]['cando'] = in_array($row['local_name'], $regions_list) || $userReigon['region_ids'] == 'all' ? 1 : 0;
    }

    //获得三级地区
    $where['region_grade'] = 3;
    $regions = $CI->region_mdl->get_query_by_where($where)->result_array();

    foreach($regions as $key => $row){
        $region_paths = explode(',', $row['region_path']);
        $p1_region_id = $region_paths[1];
        $region_arr[$p1_region_id]['children'][$row['p_region_id']]['children'][$row['region_id']] = $row;
        $region_arr[$p1_region_id]['children'][$row['p_region_id']]['children'][$row['region_id']]['cando'] = in_array($row['local_name'], $regions_list) || $userReigon['region_ids'] == 'all' ? 1 : 0;
    }



    //echo '<pre>';
    //print_r(in_array('123', $regions_list) or $userReigon['region_ids'] == 'all' ? 1 : 0);
    //echo '</pre>';

    return $region_arr;
}

/**
 * 格式化金额
 * 将金额值处理返回成 %d元 格式
 * return string
 */
function formatAmount($d)
{
    if(preg_match("/\d+/", $d))
    {
        $d = (string)$d;
    }
    else
    {
        $d = "0";
    }
    return sprintf('￥%s', $d);
}

/**
 * 生成tree数组
 *
 * return array
 */
function getTreeArray($text, $href = '', $id = '', $leaf = true, $expanded = false, $qtip = '', $iconCls = '')
{
    $arr = array(
        'text' => $text,
        'leaf' => $leaf,
        'id' => $id,
        'qtip' => $qtip,
        'iconCls' => $iconCls,
        'expanded' => $expanded
    );

    if($href)
    {
        $arr['href'] = site_url($href);
    }

    if( ! $leaf){
        $arr['children'] = array();
    }
    return $arr;
}

//配送公式验算function
function cal_fee($exp,$weight,$totalmoney,$defPrice=0){
    if($str=trim($exp)){
        $dprice = 0;
        $weight = $weight + 0;
        $totalmoney = $totalmoney + 0;
        $str = str_replace("[", "getceil(", $str);
        $str = str_replace("]", ")", $str);
        $str = str_replace("{", "getval(", $str);
        $str = str_replace("}", ")", $str);

        $str = str_replace("w", $weight, $str);
        $str = str_replace("W", $weight, $str);
        $str = str_replace("p", $totalmoney, $str);
        $str = str_replace("P", $totalmoney, $str);
        eval("\$dprice = $str;");
        if($dprice === 'failed'){
            return $defPrice;
        }else{
            return $dprice;
        }
    }else{
        return $defPrice;
    }
}
function getval($expval){
    $expval = trim($expval);
    if($expval !== ''){
    eval("\$expval = $expval;");
        if ($expval > 0){
            return 1;
        }else if ($expval == 0){
            return 1/2;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

function getceil($expval){
    if($expval = trim($expval)){
        eval("\$expval = $expval;");
        if ($expval > 0){
            return ceil($expval);
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

//权限数组
function getPermissionsArray()
{
    $CI = get_instance();
    $CI->config->load('permissions', true);
    $permissions = $CI->config->item('permissions');

    $perms = array();

    $i = 0;
    foreach($permissions as $key => $row)
    {
        foreach($row as $k => $v){
            $perms[$i]['group'] = $key;
            $perms[$i]['action_code'] = $k;
            $perms[$i]['name'] = $v['name'];
            $perms[$i]['hasperm'] = false;
            if($v['url']){
                $perms[$i]['url'] = $v['url'];
            }else{
                $perms[$i]['url'] = $key.'/'.$k;
            }
            $i++;
        }
    }
    return $perms;
}

//二维数组按照指定的键值进行排序
function array_sort($arr, $keys, $type='asc'){
    $keysvalue = $new_array = array();
    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$keys];
    }
    if($type == 'asc'){
        asort($keysvalue);
    }else{
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k=>$v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

//递归创建多级目录
function create_folders($dir){
    return is_dir($dir) or (create_folders(dirname($dir)) and mkdir($dir, 0777));
}

/**
 * 生成图片链接
 * @return  array
 */
function image_path($file_path)
{
    $CI = get_instance();
    $CI->config->load('thumbimage', true);
    $thumbimage_config = $CI->config->item('thumbimage');
    $size = $thumbimage_config['thumb_width'];

    if($file_path){
        $image_path[] = $file_path;


        $file_path_data = pathinfo($file_path);
        foreach($size as $row){
            $image_path[$row] = sprintf("%s/%s_%s.%s", $file_path_data['dirname'], $file_path_data['filename'], $row, $file_path_data['extension']);
        }
    }else{
        foreach($size as $row){
            $image_path[$row] = '';
        }
    }
	return $image_path;
}

//年龄计算
function age($date){
    $year_diff = '';
    $time = strtotime($date);
    if(FALSE === $time){
        return '';
    }

    $date = date('Y-m-d', $time);
    list($year,$month,$day) = explode("-",$date);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff = date("d") - $day;
    if ($day_diff < 0 and $month_diff <= 0) {$year_diff--;}

    return $year_diff;
}

//月龄计算
function month_age($date){
    $year_diff = '';
    $time = strtotime($date);
    if(FALSE === $time){
        return '';
    }

    $date = date('Y-m-d', $time);
    list($year,$month,$day) = explode("-",$date);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff = date("d") - $day;

    if($day_diff <=0){
        $month_age = $year_diff * 12 + $month_diff - 1;
    }else{
        $month_age = ($year_diff) * 12 + $month_diff;
    }

    return $month_age;
}

//单选按钮选中项
function radio_check($val, $checkVal){
    if($val == $checkVal){
        echo 'checked=true';
    }
    return;
}

//下拉选择选中项
function option_select($val, $checkVal){
    if($val == $checkVal){
        echo 'selected=true';
    }
    return;
}

/*月份季度判断*/
 function getSeason($month){
    if($month == '1' or $month == '2' or $month == '3'){
        return '第1季';
    }
    else if($month == '4' or $month == '5' or $month == '6'){
        return '第2季';
    }
    else if($month == '7' or $month == '8' or $month == '9'){
        return '第3季';
    }
    else{
        return '第4季';
    }
}

function msubstr($str, $start, $len) {
    $tmpstr = "";
    $strlen = $start + $len;
    for($i = 0; $i < $strlen; $i++) {
        if(ord(substr($str, $i, 1)) > 0xa0) {
            $tmpstr .= substr($str, $i, 2);
            $i++;
        } else
            $tmpstr .= substr($str, $i, 1);
    }
    return $tmpstr;
}


/**
 * 时间数据处理
 *
 * @param string $datetime 时间 可以是字符串
 * @param string $format 返回的时间格式
 * return string
*/
function trans_date_format($datetime, $format = 'Y-m-d H:i:s')
{
    return date($format,strtotime($datetime));
}

/**
 * 判断日期是否为当天
 *
 * @param string $datetime 时间 可以是字符串
 * return bool
*/
function is_today($datetime)
{
    $test_date = date("Y-m-d",strtotime($datetime));
    $now = date('Y-m-d');
    if($test_date == $now)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * 订单状态转换
 *
 * @param string $type
 * @return string
*/
function order_status_trans($status)
{
    switch ($status)
    {
        case '0':
            return '预订成功';
            break;
        case '1':
            return '请求退票';
            break;
        case '2':
            return '退票成功';
            break;
        case '3':
            return '拒绝退票';
            break;

    }
}

/**
 * 航程类型转换
 *
 * @param string $type
 * @return string
*/
function airline_type_trans($type)
{
    switch (strtolower($type))
    {
        case 's':
            return '单程';
            break;
        case 'd':
            return '往返';
            break;
        case 'm':
            return '联程';
            break;

    }
}

/**
 * 乘客类型转换
 *
 * @param string $type
 * @return string
*/
function psgtype_trans($type)
{
    switch ($type)
    {
        case 'ADT':
            return '成人';
            break;
        case 'CHD':
            return '儿童';
            break;
        case 'UM':
            return '无陪伴儿童';
            break;
        default:
            return "未定义";
            break;
    }
}

/**
 * 证件类型转换
 *
 * @param string $idtype
 * @return string NI身份证,PP护照,ID其他证件
*/
function idtype_trans($idtype)
{
    switch ($idtype)
    {
        case 'NI':
            return '身份证';
            break;
        case 'PP':
            return '护照';
            break;
        case 'ID':
            return '其他证件';
            break;
        default:
            return "未定义";
            break;
    }
}

/**
 * 基本仓位字母对应
 *
 * @param string $cabin
 * return string 头等舱:F,公务舱:C,经济舱:Y
*/
function cabin_trans($cabin)
{
    switch ($cabin)
    {
        case 'F':
            return '头等舱';
            break;
        case 'C':
            return '公务舱';
            break;
        case 'Y':
            return '经济舱';
            break;
        default:
            return $cabin."舱";
            break;
    }
}

/**
 * 仓位可用数量
 *
 * @param string $cabin
 * return string
*/
function cabin_data_trans($cabin_data)
{
    switch ($cabin_data)
    {
        case 'A':
            return '票量充足';
            break;
        case 'L':
            return FALSE;
            break;
        case 'Q':
            return FALSE;
            break;
        case 'S':
            return FALSE;
            break;
        case 'C':
            return FALSE;
            break;
        case 'X':
            return FALSE;
            break;
        case 'Z':
            return FALSE;
            break;
        case '0':
            return FALSE;
            break;
        default:
            return "仅剩".$cabin_data."张票";
            break;
    }
}

/**
 * 中转次数转换为中文
 *
 * @param string $num
 * return string
*/
function trans_s_number($num)
{
    switch ($num)
    {
        case '1':
            return '直飞';
            break;
        default:
            return "中转".($num-1)."次";
            break;
    }
}


/**
 * 得到订单单号
 * @return  string
 */
function gen_order_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('YmdHi') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * 数组或对像数据print
 */
function html_print($data)
{
    echo "<pre style='text-align:left'>";
    print_r($data);
    echo "</pre>";
}

function encode_json($str) {
    return urldecode(json_encode(url_encode($str)));
}

/**
 *
 */
function url_encode($str) {
    if(is_array($str)) {
        foreach($str as $key=>$value) {
            $str[urlencode($key)] = url_encode($value);
        }
    } else {
        $str = urlencode($str);
    }

    return $str;
}


// 手机号验证
function check_mobile_validity($mobilephone)
{
    $exp = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
    if(preg_match($exp, $mobilephone))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_week_num($date)
{
    $week = trans_date_format($date, "w");
    if($week == 0){
        return 7;
    }
    else{
        return $week;
    }
}

//对象转换为数组
function object_to_array($obj)
{
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val)
    {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

//二维数组中找出某个键的最小值
/*
* $arr array 二维数组
* $arr_key string 要比较的键名
* return strin 键名
*/
function getMinInArray($arr, $arr_key) {
    $arr = is_array($arr) ? $arr : object_to_array($arr);
    foreach($arr as $key => $value)
    {
        if(!isset($min) || ($min > $arr[$key][$arr_key]))
        {
            $min = $arr[$key][$arr_key];
            $min_key = $key;
        }
    }
    return $min_key;
}

//城市三字码转换城市名
function trans_city_name($citycode)
{
    $CI = get_instance();
    $CI->load->model('citycode_mdl');
    $CI->load->model('inter_city_mdl');

    if($citycode)
    {
        $query = $CI->inter_city_mdl->get_query_by_where(array('CITYCODE' => $citycode));

        if($query->num_rows > 0)
        {
            return $query->row()->CITYCNAME;
        }
        else
        {
            $query = $CI->citycode_mdl->get_query_by_where(array('CITYCODE' => $citycode));
            if($query->num_rows > 0)
            {
                return $query->row()->CITYCNAME;
            }
        }
    }
    return false;
}

//根据航班号获取航空公司中文名称
/*
* fltNo 航班号
*/
function get_airline_by_fltNo($fltNo)
{
    $CI = get_instance();
    $CI->config->load('airlines_code', TRUE);
    $airlines_code = $CI->config->item('airlines_code');

    $CI->config->load('inter_aircode', TRUE);
    $inter_aircode = $CI->config->item('inter_aircode');

    $key = get_aircode_by_fltNo($fltNo);
    if(array_key_exists($key, $airlines_code))
    {
        return $airlines_code[$key];
    }
    else if(array_key_exists($key, $inter_aircode))
    {
        return $inter_aircode[$key];
    }
    else
    {
        return "";
    }
}

//根据航班号获取航空公司二字码
/*
* fltNo 航班号
*/
function get_aircode_by_fltNo($fltNo)
{
    $aircode = substr($fltNo, 0 ,2);
    return $aircode;
}


//亿美发送短信操作结果状态码转换
/*
* fltNo 航班号
*/
function trans_sendSMS_code($statusCode)
{
    switch ($statusCode)
    {
        case 17:
            $result = "亿美短信提示:发送信息失败";
            break;
        case 18:
            $result = "亿美短信提示:发送定时信息失败";
            break;
        case 303:
            $result = "亿美短信提示:客户端网络故障";
            break;
        case 305:
            $result = "亿美短信提示:服务器端返回错误，错误的返回值（返回值不是数字字符串）";
            break;
        case 307:
            $result = "亿美短信提示:目标电话号码不符合规则，电话号码必须是以0、1开头";
            break;
        case 997:
            $result = "亿美短信提示:平台返回找不到超时的短信，该信息是否成功无法确定";
            break;
        case 998:
            $result = "亿美短信提示:由于客户端网络问题导致信息发送超时，该信息是否成功下发无法确定";
            break;
        default:
            $result = "亿美短信提示:短信发送成功";
            break;
    }
    return $result;
}

//亿美注册序列号功能结果状态码转换
/*
* fltNo 航班号
*/
function trans_em_login_code($statusCode)
{
    switch ($statusCode)
    {
        case 10:
            $result = "亿美短信提示:客户端注册失败";
            break;
        case 303:
            $result = "亿美短信提示:客户端网络故障";
            break;
        case 305:
            $result = "亿美短信提示:服务器端返回错误，错误的返回值（返回值不是数字字符串）";
            break;
        case 999:
            $result = "亿美短信提示:操作频繁";
            break;
        default:
            $result = "亿美短信提示:短信功能注册失败";
            break;
    }
    return $result;
}
// END common_helper.php
