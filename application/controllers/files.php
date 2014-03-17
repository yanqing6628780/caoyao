<?php
class Files extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		checkIsLoggedIn();

        $this->config->load('thumbimage', true);
	}

    //头像上传
    public function imgUpload()
    {
        $dir = $this->input->get_post('dir');
        $dir = $dir ? $dir : "";

        $data['msg'] = '';

        $thumbimage_config = $this->config->item('thumbimage');
        $widths = $thumbimage_config['thumb_width']; // 需要处理的图片宽度尺寸
        $heights = $thumbimage_config['thumb_height']; // 需要处理的图片高度尺寸

        $path = '/uploads/'.$dir.'/';
        $config['upload_path'] = '.'.$path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '2048';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = TRUE;

        //检查并创建目录
        create_folders($config['upload_path']);

        $this->load->library('upload', $config);

        $field_name = "files";
        if ( ! $this->upload->do_upload($field_name))
        {
            $data = array('error' => $this->upload->display_errors());
        } 
        else
        {
            $upload_data = $this->upload->data();
            $data['file']['url'] = get_image_url($path.$upload_data['file_name']);
            $data['file']['width'] = $upload_data['image_width'];
            $data['file']['height'] = $upload_data['image_height'];
        }

        echo json_encode($data);
    }

    //头像上传
    public function ckUpload()
    {
        $funcNum = $this->input->get_post('CKEditorFuncNum');

        $thumbimage_config = $this->config->item('thumbimage');
        $widths = $thumbimage_config['thumb_width']; // 需要处理的图片宽度尺寸
        $heights = $thumbimage_config['thumb_height']; // 需要处理的图片高度尺寸

        $path = '/uploads/ck/';
        $config['upload_path'] = '.'.$path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '2048';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = TRUE;

        //检查并创建目录
        create_folders($config['upload_path']);

        $this->load->library('upload', $config);

        $field_name = "upload";
        if ( ! $this->upload->do_upload($field_name) )
        {
            $message = $this->upload->display_errors();
        } 
        else
        {
            $upload_data = $this->upload->data();
            $url = get_image_url($path.$upload_data['file_name']);
        }

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
}
