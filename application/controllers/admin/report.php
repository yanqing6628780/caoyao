<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class report extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();

        $this->load->model('tank_auth/profiles_mdl', 'profiles');
        $this->load->model('order_mdl');

        $this->data['controller_url'] = "admin/orders/";

        $this->colors = array("#7cb5ec", "#434348", "#90ed7d", "#f7a35c", "#8085e9", "#f15c80", "#e4d354", "#8085e8", "#8d4653", "#91e8e1");
    }

    // 分公司销售报表页
    public function branch_sales()
    {
        //获取所有分公司
        $this->general_mdl->setTable('exchange_fair');
        $data['exchanges'] = $this->general_mdl->get_query()->result_array();

        $this->load->view('admin_report/branch_sales', $data);
    }    

    // 分公司销售数据
    public function get_branch_sales_data()
    {
        $exchange_id = $this->input->get_post('exchange_id');

        $categories = array();
        $response['data'] = array();

        if($exchange_id)
        {

            //获取所有分公司
            $this->general_mdl->setTable('branch');
            $branchs = $this->general_mdl->get_query()->result_array();

            $base_data = array(
                'y' => 0,
                'drilldown' => array(
                    'name' => '',
                    'categories' => array(),
                    'data' => array()
                )
            );

            foreach ($branchs as $key => $item) {
                $categories[] = $item['branch_name'];
                $base_data['y'] = 0;
                $base_data['drilldown']['name'] = $item['branch_name'];
                $base_data['color'] = $this->colors[rand(0,9)];
                // 获取所有子公司下的门店
                $query = $this->profiles->get_profiles(array('branch_id' => $item['id']));
                if($query->num_rows()){
                    $base_data['drilldown']['categories'] = array();
                    $base_data['drilldown']['data'] = array();
                    foreach ($query->result_array() as $key => $value) {
                        $base_data['drilldown']['categories'][] = $value['name']; //门店做x轴
                        $user_orders = $this->order_mdl->get_orders(array('order.user_id' => $value['user_id'], 'order.exchange_fair_id' => $exchange_id))->result_array(); //用户的所有订单
                        $user_orders_total = 0; // 用户所有订单的总销量价格
                        foreach ($user_orders as $key => $order_item) {
                            $user_orders_total += $this->order_mdl->sum($order_item['id']);
                        }
                        $base_data['drilldown']['data'][] = $user_orders_total;
                        $base_data['y'] += $user_orders_total;
                    }
                }

                $response['data'][] = $base_data;
            }

            $response['categories'] = $categories;
        }

        echo json_encode($response);
    }

    // 门店销售报表页
    public function user_sales()
    {
        //获取所有分公司
        $this->general_mdl->setTable('exchange_fair');
        $data['exchanges'] = $this->general_mdl->get_query()->result_array();

        //获取所有分公司
        $this->general_mdl->setTable('branch');
        $branchs = $this->general_mdl->get_query()->result_array();

        foreach ($branchs as $key => $item) {
            // 获取所有子公司下的门店
            $query = $this->profiles->get_profiles(array('branch_id' => $item['id']));
            $branchs[$key]['users'] = $query->result_array();
        }

        $data['branches'] = $branchs;
        $this->load->view('admin_report/user_sales', $data);
    }

    public function get_user_sales_data()
    {
        $response['data'] = array();

        $exchange_id = $this->input->get_post('exchange_id');
        $user_id = $this->input->get_post('user_id');

        $where = array(
            'exchange_fair_id' => $exchange_id,
            'user_id' => $user_id
        );

        $this->general_mdl->setTable('order');
        $query = $this->general_mdl->get_query_by_where($where);
        $row = $query->row_array();

        
        $rs = $row ? $this->order_mdl->small_class_group($row['id']) : array();

        foreach ($rs as $key => $item) {
            $response['data'][] = array( $item['small_class_name'], intval($item['small_total']) );
        }

        echo json_encode($response);
    }

    public function get_user_sales_list()
    {
        $exchange_id = $this->input->get_post('exchange_id');
        $user_id = $this->input->get_post('user_id');

        $where = array(
            'exchange_fair_id' => $exchange_id,
            'user_id' => $user_id
        );

        $this->general_mdl->setTable('order');
        $query = $this->general_mdl->get_query_by_where($where);
        $row = $query->row_array();

        $data['order_products'] = $row ? $this->order_mdl->product_group($row['id']) : array();

        $this->load->view('admin_report/user_sales_list', $data);
    }
}
