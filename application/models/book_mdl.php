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

    public function get_allbookdatetime_data_by_date($date){
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

        $data = array();
        foreach ($all_book_datetime as $key => $datetime) {
            $where['book_date'] = $datetime;
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
}