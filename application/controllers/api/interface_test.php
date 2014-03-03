<?php
class interface_test extends CI_Controller{

    function index() {
    	$this->load->library('curl_tool');
        $http_header = array(
            'Authorization: Basic '.base64_encode("sqt:YWaWMTIzNA")
        );

        $data = array('username' => 'admin', 'password' => '123456');
        $url = site_url("/api/my_interface/receive_goods");
        print($this->curl_tool->post($url, $http_header, $data));
    }


}
