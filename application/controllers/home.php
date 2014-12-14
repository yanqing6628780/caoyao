<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->data['config'] = $this->general_mdl->sys_configs;
    }

    public function index()
    {
        $this->data['title'] = '预约';

        $this->load->view('front/head',$this->data);
        $this->load->view('front/home');
    }	

    public function book_list()
    {
        $this->load->model('book_mdl');

        $book_date = $this->input->get_post('book_date') ? $this->input->get_post('book_date') : date('Y-m-d');

        if($book_date){
            $book_data = trans_date_format($book_date,'Y-m-d');
            $this->data['book_date'] = $book_data;
        }


        $book_data = $this->book_mdl->get_allbookdatetime_data_by_date($book_data);
        
        $this->data['result'] = $book_data;
        $this->data['title'] = '预约';

        $this->load->view('front/book_list', $this->data);
    }

    //添加保存
    public function book_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setTable('appointment');
        
        if( $this->book_date_check($data['book_date']) )
        {
            $this->general_mdl->setData($data);
            if($this->general_mdl->create())
            {
                $response['status'] = "y";
                $response['info'] = "添加成功";
            }else{
                $response['status'] = "n";
                $response['info'] = "添加失败";
            }
        }else{
            $response['status'] = "n";
            $response['info'] = "已有预约";            
        }

        echo json_encode($response);
    }

    public function book_date_check($book_date)
    {
        $query = $this->general_mdl->get_query_by_where(array('book_date' => $book_date));
        if(!$query->num_rows())
        {
            return true;
        }else{
            return false;
       }
    }

    public function show_error($code=404, $head='找不到页面' ,$msg='')
    {
        $data['title'] = "错误信息";
        $data['code'] = $code;
        $data['head'] = $head;
        $data['msg'] = $msg;
        $this->load->view('front/head');
        $this->load->view('front/error', $data);
        die();
    }

}
