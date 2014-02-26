<?php
class interface_test extends CI_Controller{

    function index() {
    	$this->load->library('curl_tool');
        $http_header = array(
            'Authorization: Basic '.base64_encode("sqt:YWaWMTIzNA")
        );

        $data = array('coupon_num' => 'oh8yxi');
        $url = site_url("/api/my_interface/coupon_print");
        print($this->curl_tool->post($url, $http_header, $data));
    }


}
