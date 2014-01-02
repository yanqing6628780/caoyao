<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');


        $config['ManualMode'] = TRUE;
        $this->load->library('HxClient', $config);

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/login/');
        }
    }

	public function index()
	{
		$this->load->view('front/head');
		$this->load->view('front/home');
		$this->load->view('front/footer');
	}

    /*航班查询页*/
    public function flight_inquiry()
    {

        $data['org'] = $this->input->get_post('org'); //起飞城市
        $data['dst'] = $this->input->get_post('dst'); //抵达城市
        $data['routeType'] = $this->input->get_post('routeType'); //航班类型
        $data['date'] = $this->input->get_post('date') ? trans_date_format($this->input->get_post('date'), "Ymd") : date("Ymd", time()); //出发日期
        $data['return_date'] = $data['date'] ? date( "Ymd", strtotime("+1 day", strtotime($data['date'])) ) : date("Ymd", strtotime("+1 day")); //返回日期

        $data['orgcity'] = trans_city_name($data['org']);
        $data['dstcity'] = trans_city_name($data['dst']);

        //申请令牌
        $this->hxclient->apply_token();

        $token = $this->session->userdata('hx_token');

        if(!$token)
        {
            $this->hxclient->apply_token();
        }

        //机场代码
        $this->config->load('airlines_code', TRUE);
        $data['airlines_code'] = $this->config->item('airlines_code');


        $this->load->view('front/head');
        $this->load->view('front/flight_inquiry', $data);
    }

    /*航班查询*/
    public function ajax_flight_inquiry()
    {

        //文本记录返回数据配置
        $logger = $this->logger;
        $logger->conf["log_file"] = rtrim(SBE_WORK_DIR, '\\/') . '/' . "datas/sbe_datas_" . date("Y-m-d") . ".log";
        $logger->conf["separator"] = "\n";

        //获取SBE接口令牌
        $token = $this->session->userdata('hx_token');

        //获得航空公司代码
        $this->config->load('airlines_code', TRUE);
        $airlines_code = $this->config->item('airlines_code');
        $data['airlines_code'] = $airlines_code;

        //获取机场三字码对应表
        $this->general_mdl->setTable('airport_code');
        $airport_result = $this->general_mdl->get_query()->result();
        $airport_codes = array();
        foreach ($airport_result as $value)
        {
            $airport_codes[$value->code] = $value->name;
        }

        //提交参数
        $data['flighttype'] = $flighttype = $this->input->post('flighttype'); //航线类型
        $org = $this->input->post('org'); //起飞城市
        $dst = $this->input->post('dst'); //抵达城市
        $date = $this->input->post('date'); //出发日期
        $airline_code = $this->input->post('airlines_code'); //航空公司

        $result = array();
        $result_fd = array();
        if($token)
        {
            /*查询不同航空公司的航班*/

            if($airline_code) //当指定航空公司时,替换航空公司数组
            {
                $airlines_code = array($airline_code => $data['airlines_code'][$airline_code]);
            }

            foreach ($airlines_code as $key => $value)
            {
                $av_data = array(
                    'airline' => $key,
                    'date' => $date,
                    'direct' => 'false',
                    'dst' => $dst,
                    'fltNo' => "",
                    'ibeFlag' => 'false',
                    'isTest' => null,
                    'nonstop' => 'false',
                    'org' => $org,
                    'page' => 30,
                    'stopCity' => "",
                );

                $resp = $this->hxclient->execute('sbe_av', $av_data, $token);

                //查询AV数据成功后,才查询运价数据
                if( isset($resp->resultCode) && $resp->resultCode == 0)
                {
                    $result[$key] = $resp;

                    $fd_data = array(
                        "airline" => $key,
                        "date" => trans_date_format($date, "Y-m-d"),
                        "dst" => $dst,
                        "fullFareBasis" => "true",
                        "ibeFlag" => "false",
                        "org" => $org,
                        "passType" => null,
                        "planeModel" => null
                    );

                    $fd_resp = $this->hxclient->execute('sbe_fd', $fd_data, $token);

                    //运价数据返回成功
                    if( isset($fd_resp->resultCode) && $fd_resp->resultCode == 0)
                    {
                        $result_fd[$key] = $fd_resp;
                    }
                    else //数据返回失败,清空AV数据数组
                    {
                        unset($result[$key]);
                    }
                }
                else if(isset($resp->resultCode) && $resp->resultCode == 20005)
                {
                    die('请求数据失败,请重新登录系统 <a href="'.site_url("/login/logout").'">退出</a>');
                }
                else
                {
                    $result[$key] = FALSE;
                    $result_fd[$key] = FALSE;
                }
            }
        }

        //将航班信息中的共享航班删除;
        foreach ($result as $key => $value)
        {
            if($value)
            {
                foreach ($value->avItems as $item_key => $item)
                {
                    if($item->segments[0]->codeShare == "true")
                    {
                        unset($value->avItems[$item_key]);
                    }
                }
            }
        }

        //组织FD运价数据
        $new_result_fd = array();
        $basicSinglePrice = 0; //基础单程价格
        if($result_fd)
        {
            foreach ($result_fd as $key => $value)
            {
                if($value)
                {
                    //对返回结果,进行处理
                    foreach ($value->fare->sortedfares as $sf_key => $sf_value)
                    {
                        if($sf_value->discountRate === "100.0"){ $basicSinglePrice = $sf_value->singlePrice; } //取得基础价格

                        $new_result_fd[$key][$sf_value->cabin]['basicCabin'] = $sf_value->basicCabin; //基本仓位 头等舱:F,公务舱:C,经济舱:Y
                        $new_result_fd[$key][$sf_value->cabin]['cabin'] = $sf_value->cabin; //仓位 不同航空公司仓位字母不同,价格也不同
                        $new_result_fd[$key][$sf_value->cabin]['dstCity'] = $sf_value->dstCity; //抵达城市
                        $new_result_fd[$key][$sf_value->cabin]['orgCity'] = $sf_value->orgCity; //始发城市
                        $new_result_fd[$key][$sf_value->cabin]['airportTax'] = $sf_value->airportTax; //机场税
                        $new_result_fd[$key][$sf_value->cabin]['fueltax'] = $sf_value->fueltax; //燃油税
                        $new_result_fd[$key][$sf_value->cabin]['validDate'] = $sf_value->validDate; //生效日期
                        $new_result_fd[$key][$sf_value->cabin]['invalidDate'] = $sf_value->invalidDate; //失效日期
                        $new_result_fd[$key][$sf_value->cabin]['discountRate'] = $sf_value->discountRate; //折扣率
                        $new_result_fd[$key][$sf_value->cabin]['singlePrice'] = $sf_value->singlePrice; //单程票价
                        $new_result_fd[$key][$sf_value->cabin]['roundtripPrice'] = $sf_value->roundtripPrice; //双程票价
                        $new_result_fd[$key][$sf_value->cabin]['mileage'] = $value->mileage; //航班飞行里程
                    }
                }
            }

        }

        //组织AV查询航线数据
        if($result && $new_result_fd) //检查是否为空数组
        {

            foreach ($result as $key => $value)
            {
                if($value)
                {
                    //对返回结果,进行处理
                    foreach ($value->avItems as $item_key => $item)
                    {
                        foreach ($item->segments as $s_key => $s_value)
                        {
                            $s_value->cn_dstcity = isset($airport_codes[$s_value->dstcity]) ? $airport_codes[$s_value->dstcity] : trans_city_name($s_value->dstcity); //抵达城市三字码对应机场
                            $s_value->cn_orgcity = isset($airport_codes[$s_value->orgcity]) ? $airport_codes[$s_value->orgcity] : trans_city_name($s_value->orgcity); //出发城市三字码对应机场
                            $s_value->time_diff = $s_value->arriveDate - $s_value->depDate; //抵达日期与出发日期时间差
                            $s_value->new_depDate = trans_date_format($s_value->depDate, 'H:i'); //出发时间转换成13:00格式
                            $s_value->new_arriveDate = trans_date_format($s_value->arriveDate, 'H:i'); //到达时间转换成13:00格式

                            //仓位数据处理
                            foreach ($s_value->cangwei_index as $cw_i_key => $cw_i_value)
                            {
                                unset($min);
                                //从运价数组找出仓位对应信息
                                if (isset($new_result_fd[$key][$cw_i_value]))
                                {
                                    $fd = $new_result_fd[$key][$cw_i_value];

                                    $s_value->cabin[$cw_i_value]['cabin'] = $cw_i_value; //仓位
                                    $s_value->cabin[$cw_i_value]['basicCabin'] = cabin_trans($fd['basicCabin']); //基本仓位对应文字
                                    $s_value->cabin[$cw_i_value]['cabin_data'] = cabin_data_trans($s_value->cangwei_data[$cw_i_key]); //对应仓位可用座位数
                                    $s_value->cabin[$cw_i_value]['singlePrice'] = $fd['singlePrice'] ? number_format($fd['singlePrice'], 2) : number_format($basicSinglePrice*$fd['discountRate']/100, 2) ; //票面价格 如果价格为空,用基础价格乘以折扣
                                    $s_value->cabin[$cw_i_value]['discountRate'] = $fd['discountRate']; //折扣
                                    $s_value->cabin[$cw_i_value]['fueltax'] = $fd['fueltax']; //燃油税
                                    $s_value->cabin[$cw_i_value]['airportTax'] = $fd['airportTax']; //机场税

                                    //找出有座位而且最便宜的仓位
                                    if(cabin_data_trans($s_value->cangwei_data[$cw_i_key]))
                                    {
                                        if(!isset($min) || ($min > $fd['singlePrice']))
                                        {
                                            $min = $fd['singlePrice'];
                                            $min_value = $cw_i_value;
                                            $s_value->cheapest_cabin = $new_result_fd[$key][$cw_i_value]; //取出最便宜的仓位数据
                                            $s_value->cheapest_cabin['cabin_data'] = cabin_data_trans($s_value->cangwei_data[$cw_i_key]); //对应仓位可用座位数
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $data['result'] = $result;
            $data['result_fd'] = $result_fd;
            $this->load->view('front/flight_inquiry_result', $data);
        }
        else
        {
            die('很抱歉，无法匹配到结果，请联系客服或重新选择后查询');
        }
    }

    //RT提取PNR内容
    public function rt($pnrNo = NULL)
    {

        //获取SBE接口令牌
        $token = $this->session->userdata('hx_token');

        if($pnrNo !== NULL) //类内使用时
        {
            $rt_data = array("pnrNo" => $pnrNo);
            $rt_resp = $this->hxclient->execute('sbe_rt', $rt_data, $token);
            switch ($rt_resp->resultCode) {
                case 10033:
                    sleep(1);
                    $this->rt($pnrNo);
                    break;
                default:
                    return $rt_resp;
                    break;
            }
        }
        else //用链接查询时,输出提取的PNR内容
        {
            $get_post_pnrNo = $this->input->get_post("pnrNo");
            if($get_post_pnrNo)
            {
                $rt_data = array("pnrNo" => $get_post_pnrNo);
                $rt_resp = $this->hxclient->execute('sbe_rt', $rt_data, $token);

                html_print($rt_resp);
            }

        }
    }

    //查询运价
    /*
    * $token string 会话
    * $airlineCode string 航空公司代码
    * $dstcity string 抵达城市
    * $orgcity string 起飞城市
    * $date string 乘机日期
    */
    public function sbe_fd($token, $airlineCode, $dstcity, $orgcity, $date)
    {
        $ibeFlag = $this->hxclient->TestMode ? "FALSE" : "TRUE"; //测试环境时,不能查询税费
        //如果其中一个城市是国外的,用XSFSD接口查询
        $fd_data = array(
            "airline" => $airlineCode,
            "date" => trans_date_format($date, "Y-m-d"),
            "dst" => $dstcity,
            "fullFareBasis" => "true",
            "ibeFlag" => $ibeFlag,
            "org" => $orgcity,
            "passType" => null,
            "planeModel" => null
        );

        $fd_resp = $this->hxclient->execute('sbe_fd', $fd_data, $token);

        if(isset($fd_resp->resultCode))
        {
            //对返回结果,进行处理
            switch ($fd_resp->resultCode)
            {
                case 0:
                    foreach ($fd_resp->fare->sortedfares as $sf_key => $sf_value)
                    {
                        //各舱位的全价价格
                        switch ($sf_value->fareBasis)
                        {
                            case 'Y':
                                $result_fd['fareBasis_Y'] = $sf_value->singlePrice;
                                break;
                            case 'F':
                                $result_fd['farebasis_F'] = $sf_value->singlePrice;
                                break;
                            case 'FOW':
                                $result_fd['fareBasis_F'] = $sf_value->singlePrice;
                                break;
                            case 'C':
                                $result_fd['fareBasis_C'] = $sf_value->singlePrice;
                                break;
                        }

                        $result_fd['fares'][$sf_value->cabin]['basicCabin'] = $sf_value->basicCabin; //基本仓位 头等舱:F,公务舱:C,经济舱:Y
                        $result_fd['fares'][$sf_value->cabin]['cabin'] = $sf_value->cabin; //仓位 不同航空公司仓位字母不同,价格也不同
                        $result_fd['fares'][$sf_value->cabin]['dstCity'] = $sf_value->dstCity; //抵达城市
                        $result_fd['fares'][$sf_value->cabin]['orgCity'] = $sf_value->orgCity; //始发城市
                        $result_fd['fares'][$sf_value->cabin]['airportTax'] = $sf_value->airportTax; //机场税
                        $result_fd['fares'][$sf_value->cabin]['fueltax'] = $sf_value->fueltax; //燃油税
                        $result_fd['fares'][$sf_value->cabin]['validDate'] = $sf_value->validDate; //生效日期
                        $result_fd['fares'][$sf_value->cabin]['invalidDate'] = $sf_value->invalidDate; //失效日期
                        $result_fd['fares'][$sf_value->cabin]['discountRate'] = $sf_value->discountRate; //折扣率
                        $result_fd['fares'][$sf_value->cabin]['singlePrice'] = $sf_value->singlePrice; //单程票价
                        $result_fd['fares'][$sf_value->cabin]['roundtripPrice'] = $sf_value->roundtripPrice; //双程票价
                        $result_fd['fares'][$sf_value->cabin]['mileage'] = $fd_resp->mileage; //航班飞行里程
                    }
                    return $result_fd;
                    break;
                case 10033:
                    sleep(1);
                    $this->sbe_fd($token, $airlineCode, $dstcity, $orgcity, $date);
                    break;
                default:
                    return FALSE;
                    break;
            }
        }
    }

    public function sbe_av($fltNo = NULL, $date = NULL, $airline = NULL, $dst = NULL, $org = NULL)
    {
        $token = $this->session->userdata('hx_token');

        $date = trans_date_format($date, "Ymd");

        if($fltNo === NULL)
        {
            $av_data = array(
                'airline' => $airline,
                'date' => $date,
                'direct' => 'false',
                'dst' => $dst,
                'fltNo' => "",
                'ibeFlag' => 'false',
                'isTest' => null,
                'nonstop' => 'false',
                'org' => $org,
                'page' => 30,
                'stopCity' => ""
            );
        }
        else
        {
            $av_data = array(
                'date' => $date,
                'fltNo' => $fltNo
            );
        }

        if($token)
        {
            $resp = $this->hxclient->execute('sbe_av', $av_data, $token);
            if(isset($resp->resultCode))
            {
                switch ($resp->resultCode)
                {
                    case 0:
                        return $resp;
                        break;
                    case 10033:
                        usleep(1000);
                        $this->sbe_av($fltNo, $date, $airline, $dst, $org);
                        break;
                    default:
                        return FALSE;
                        break;
                }
            }
        }
        return FALSE;
    }

    //查询两地航班信息
    /*
    * $airline string 航空公司
    * $org string 起飞城市
    * $dst string 抵达城市
    * $date string 出发日期
    */
    public function av_fd($airline = NULL, $org = NULL, $dst = NULL,  $date = NULL)
    {

        $resp = $this->sbe_av(NULL, $date, $airline, $dst, $org);
        html_print($resp);

        $fd_data = array(
            "airline" => $airline,
            "date" => trans_date_format($date, "Y-m-d"),
            "dst" => $dst,
            "fullFareBasis" => "true",
            "ibeFlag" => "false",
            "org" => $org,
            "passType" => null,
            "planeModel" => null
        );

        $token = $this->session->userdata('hx_token');
        $fd_resp = $this->hxclient->execute('sbe_fd', $fd_data, $token);

        html_print($fd_resp);
    }
}
