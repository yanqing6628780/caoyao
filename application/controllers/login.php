<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');

        $this->load->model('members_mdl'); //载入前台用户数据模型
		$this->lang->load('tank_auth'); //tank_auth语言包
    }

    /**
	 * 登陆
	 */
    function index()
    {
		$this->login();
    }

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login', '用户名', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', '记住我', 'integer');

			/*手机验证码功能*/
			// $this->form_validation->set_rules('mobile_captcha', '手机验证码', 'trim|required|integer|xss_clean|callback__check_mobile_captcha');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND ($login = $this->input->post('login')))
			{
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			$data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
			if ($this->tank_auth->is_max_login_attempts_exceeded($login))
			{
				if ($data['use_recaptcha'])
				{
					$this->form_validation->set_rules('recaptcha_response_field', '验证码', 'trim|xss_clean|required|callback__check_recaptcha');
				}
				else
				{
					$this->form_validation->set_rules('captcha', '验证码', 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			$data['errors'] = array();

			if ($this->form_validation->run())
			{								// validation ok
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email']
					))
				{	// 登录成功
					redirect('home');
				}
				else //登录失败
				{
					$errors = $this->tank_auth->get_error_message(); //获取错误信息
					if (isset($errors['banned']))
					{	// banned user 被禁用户
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

					}
					elseif (isset($errors['not_activated']))
					{	// not activated user 未激活用户
						redirect('/auth/send_again/');
					}
					else
					{	// fail 其他失败原因
						foreach ($errors as $k => $v){$data['errors'][$k] = $this->lang->line($v);}
					}
				}
			}
			$data['show_captcha'] = FALSE;
			if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				$data['show_captcha'] = TRUE;
				if ($data['use_recaptcha']) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}

			$data['mobile_captcha_expire'] = $this->config->item('mobile_captcha_expire'); //手机验证码存活时间
			$data['title'] = '登录';
			$this->load->view('front/head');
			$this->load->view('front/login', $data);
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();
		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/home/');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('tank_email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('tank_email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 回调函数. 检查手机验证码是否正确
	 *
	 * @return	bool
	 */
	function _check_mobile_captcha($code)
	{
		$time = $this->session->userdata('mobile_captcha_time'); //已生成的手机验证码时间
		$word = $this->session->userdata('mobile_captcha_word'); //已生成的手机验证码
		$captcha_expire = $this->config->item('mobile_captcha_expire'); //手机验证码到期时间

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $captcha_expire)
		{
			$this->form_validation->set_message('_check_mobile_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;
		}
		elseif ($code != $word)
		{
			$this->form_validation->set_message('_check_mobile_captcha', "您验证码不正确");
			return FALSE;
		}
		return TRUE;
	}

	/*
	* 发送验证码短信
	*/
    public function send_mobile_captcha($username = NULL)
    {

        $this->load->config('em_sms'); //读取配置文件
        $sms_txt = $this->config->item('sms_txt'); //验证码发送文本

        $data['success'] = FALSE;
        $data['msg'] = "";

        #亿美短信注册序列号
        $this->em_login();
        if($this->session->userdata('em_is_login') !== TRUE) //注册失败
        {
        	$data['msg'] = $this->session->userdata('em_is_login');
        }
        else //注册成功
        {
	        if($username !== NULL) //用户名不为空时
	        {
	        	$query = $this->members_mdl->get_query_by_where( array('username' => $username) );
	        	if($query->num_rows() > 0)
	        	{
	        		$tel = $query->row()->tel;
	        		if(check_mobile_validity($tel))
	        		{
	        			$mobile_captcha = rand(1000,9999); //生成随机手机验证码
	        			$this->session->set_userdata('mobile_captcha', $mobile_captcha); //保存进session
			        	$content = sprintf($sms_txt, $mobile_captcha); //手机验证码添加到发送内容
			        	$statusCode = $this->em_client->sendSMS(array($tel), $content); //发送
			        	if($statusCode === "0")
			        	{
			        		// 保存手机验证码到SESSION
			        		list($usec, $sec) = explode(" ", microtime());
			        		$now = ((float)$usec + (float)$sec);

			        		$this->session->set_userdata(
			        			array(
			        				'mobile' => $tel,
			        				'mobile_captcha_word' => $mobile_captcha,
									'mobile_captcha_time' => $now
			        			)
		        			);

				        	$data['success'] = TRUE;
				        	$data['msg'] = "发送成功";
			        	}
			        	else
			        	{
			        		$data['msg'] = trans_sendSMS_code($statusCode);
			        	}
	        		}
	        		else
	        		{
	        			$data['msg'] = "登录帐户的手机号码不正确,请联系客服修改";
	        		}
	        	}
	        	else
	        	{
	        		$data['msg'] = "没有该用户,请重新输入";
	        	}
	        }
	        else
	        {
	        	$data['msg'] = "请输入用户名";
	        }
        }

        echo json_encode($data);
    }

	/*
	* 亿美短信平台注册
	*/
    private function em_login()
    {
		$resp_code = $this->em_client->login();
		if($resp_code === "0") //注册成功
		{
			$this->session->set_userdata('em_is_login' , TRUE);
		}else{
			$this->session->set_userdata('em_is_login' , trans_em_login_code($resp_code));
		}
    }

    public function em_test()
    {
    	# code...
    	// $resp_code = $this->em_client->login();
    	// var_dump($resp_code);
    	// var_dump($resp_code === "0");
    	$this->em_login();
    	var_dump($this->session->userdata("em_is_login"));
    	var_dump($this->em_client->getBalance());
    	$this->em_client->registDetailInfo("龙山客运站","梅伟盛","18902569622", "18902569622", "278063337@qq.com","","","");
    }

    private function show_mobile_captcha()
    {
    	# code...
    	var_dump($this->session->userdata("mobile"));
    	var_dump($this->session->userdata("mobile_captcha_word"));
    	var_dump($this->session->userdata("mobile_captcha_time"));
    }
}
