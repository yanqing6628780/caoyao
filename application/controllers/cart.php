<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');
        $this->load->library('cart');

        if(!$this->tank_auth->is_logged_in()){
            redirect('/login/');
        }

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
        $this->general_mdl->setTable('product');
    }

    public function index()
    {
        $this->load->model('attr_mdl');
        $data['contents'] = $this->cart->contents();

        $this->load->view('front/cart', $data);
	}

    public function destroy()
    {
        $this->cart->destroy();
        $this->index();
    }    

    public function update()
    {
        $rowid = $this->input->post('rowid');
        $qty = $this->input->post('qty');

        $data = array('rowid' => $rowid,'qty' => $qty);

        $this->cart->update($data);
        $this->index();
    }

    public function to_order()
    {
        $response['success'] = false;

        //检查是否已经创建订单
        if(!$this->session->userdata('order_id')){        
            $this->general_mdl->setTable('order');
            do {
                $data['order_number'] = get_order_sn();
                $query = $this->general_mdl->get_query_by_where(array('order_number' => $data['order_number'] ));
            } while ($query->num_rows() > 0);

            //创建订单
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['exchange_fair_id'] = 1;
            $data['create_time'] = date("Y-m-d H:i:s");;
            $this->general_mdl->setData($data);
            $order_id = $this->general_mdl->create();
            $this->session->set_userdata('order_id', $order_id);
        }

        if($this->session->userdata('order_id')){        
            $product_data['order_id'] = $this->session->userdata('order_id');
            $this->general_mdl->setTable('order_product');
            foreach ($this->cart->contents() as $key => $items){
                $product_data['product_id'] = $items['id'];
                $product_data['first_attribute_values_id'] = $items['options'][0];
                $product_data['second_attribute_values_id'] = $items['options'][1];
                $product_data['qty'] = $items['qty'];
                $this->general_mdl->setData($product_data);
                $this->general_mdl->create();
            }
            $this->cart->destroy();
            $response['success'] = true;
        }

        echo json_encode($response);
    }
}
