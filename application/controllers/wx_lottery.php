<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wx_lottery extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/lottery
     *  - or -
     *      http://example.com/index.php/lottery/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/lottery/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();

        $this->general_mdl->setTable('lottery_items');

        $this->data['controller_url'] = "lottery/";
    }

    public function index()
    {
        
    }

    public function rotate()
    {
        $query = $this->general_mdl->get_query(0, '', 'probability ASC');

        $lottery_items = $query->result_array();
        foreach ($lottery_items as $key => $value) {
            $prize_arr[] = $value['id'];
        }

        $this->data['result'] = $prize_arr;
        $this->load->view('front/rotate_lottery', $this->data);
    }

    // 抽奖
    public function draw()
    {
        $query = $this->general_mdl->get_query(0, '', 'probability ASC');

        $this->data['total'] = $query->num_rows();
        $prize_arr = $query->result_array();

        foreach ($prize_arr as $key => $val) { 
            $arr[] = $val['probability']; 
        }

        $rkey = get_rand($arr); //根据概率获取奖项在数组内的key

        //当用户中奖时写入数据库
        if($prize_arr[$rkey]['id'] != 1){
            $this->general_mdl->setTable('lottery_use');
            //检查中奖号是否重复
            do {
                $lottery_num = generate_password(6, 1);
            } while ($this->general_mdl->get_query_by_where( array('lottery_num' => $lottery_num) )->num_rows() > 0);

            $data['lottery_num'] = $lottery_num;
            $data['prize'] = $prize_arr[$rkey]['name'];
            $this->general_mdl->setData($data);
            $this->general_mdl->create();
        }
        echo json_encode($prize_arr[$rkey]);
    }
}

/* End of file lottery.php */
/* Location: ./application/controllers/lottery.php */
