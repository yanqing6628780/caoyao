<?php
class interface_test extends CI_Controller{

    function index() {
    	$this->load->library('curl_tool');
        $http_header = array(
            'Authorization: Basic '.base64_encode("sqt:YWaWMTIzNA")
        );

        $data = array('order_sn' => '21345354');
        $url = site_url("/api/my_interface/get_order");
        var_dump($this->curl_tool->post($url, $http_header, $data));
    }


}
