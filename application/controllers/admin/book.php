<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class book extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/book
     *  - or -
     *      http://example.com/index.php/book/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/book/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        
        $this->general_mdl->setTable('sys_config');
        $res = $this->general_mdl->get_query_by_where(array('cat' => 'sys'))->result_array();
        foreach ($res as $key => $item) {
            $this->sys_configs[$item['name']] = $item['value'];
        }
        $this->data['config'] = $this->sys_configs;

        $this->general_mdl->setTable('appointment');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/book/";
    }

    public function index()
    {
        checkPermission('book_view');
        
        $this->data['name'] = $q = $this->input->get_post('q');
        $this->data['book_date'] = $book_date = $this->input->get_post('book_date') ? $this->input->get_post('book_date') : date('Y-m-d');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;

        $like = array();
        $where = array();

        if($q){
            $like['name'] = $q;
        }else{        
            if($book_date){
                $book_date = trans_date_format($book_date,'Y-m-d');
                $this->data['book_date'] = $book_date;
                $where['book_date >'] = $book_date;
                $where['book_date <'] = date('Y-m-d', strtotime('+1 day', strtotime($book_date)));
            }
        }

        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query();
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $this->db->where($where);
        $this->db->or_like($like);
        $query = $this->general_mdl->get_query($start-1, $pageSize);
        $book_data = $query->result_array();

        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url'].'?page='.($start-1);
        $prev_link .= $q ? '&q='.$q : '';

        $next_link = $this->data['controller_url'].'?page='.($start+1);
        $next_link .= $q ? '&q='.$q : '';

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url'].'?page='.$i;
            $page_link[$i] .= $q ? '&q='.$q : '';
        }
        $this->data['page_links'] = $page_link;

        $this->data['result'] = $book_data;
        $this->data['title'] = '预约管理';

        $this->load->view('admin_book/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->data['now'] = date('Y-m-d h:i');
        $this->load->view('admin_book/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $data['book_date'] = $data['book_date']['date']." ".$data['book_date']['time'][0].":".$data['book_date']['time'][1];

        
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

    //修改
    public function edit()
    {
        $this->data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;

        $this->load->view('admin_book/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $where = array('id'=>$data['id']);
        unset($data['id']);

        $this->general_mdl->setData($data);
        $isUpdated = $this->general_mdl->update($where);

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改完成";
        }

        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;
 
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

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
}

/* End of file book.php */
/* Location: ./application/controllers/book.php */
