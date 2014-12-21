<?php

class book_mdl extends General_mdl
{

    private $table_fields;

    function __construct()
    {
        parent::__construct();
        $this->setTable('appointment');
        $this->table_fields = array('id','name','phone','book_date');
    }

    public function get_allbookdatetime_data_by_date($date, $doctor_id = 0){
        $am_book_datetime = date_time_joint(
            $date, 
            $this->sys_configs['bh_am_start'], 
            $this->sys_configs['bh_am_end'], 
            $this->sys_configs['am_time_interval']
        );
        $pm_book_datetime = date_time_joint(
            $date, 
            $this->sys_configs['bh_pm_start'], 
            $this->sys_configs['bh_pm_end'], 
            $this->sys_configs['pm_time_interval']
        );
        $all_book_datetime = array_merge($am_book_datetime,$pm_book_datetime);

        if($doctor_id){
            $where['doctor_id'] = $doctor_id;
        }
        $data = array();
        foreach ($all_book_datetime as $key => $datetime) {
            $where['book_date'] = $datetime;
            $this->db->select('appointment.*,doctor.name as doctor_name');
            $this->db->join('doctor', 'doctor.id = appointment.doctor_id');
            $query = $this->get_query_by_where($where);
            if($query->num_rows()){
                $data[] = $query->row_array();
            }else{
                foreach ($this->table_fields as $key => $filed) {
                    $row[$filed] = $filed == 'book_date' ? trans_date_format($datetime, 'Y-m-d H:i:s') : '';
                }
                $data[] = $row;
            }
        }
        return $data;
    }

    //返回 true 为有预约, falsh 为无预约
    public function check_doctor_book_date($doctor_id, $date)
    {
        $where = array(
            'book_date' => $date,
            'doctor_id' => $doctor_id
        );
        $query = $this->get_query_by_where($where);
        $bool = $query->num_rows() ? true : false;
        return $bool;
    }
}