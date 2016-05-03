<?php

class ControllerAccount extends Controller {
	public function action_index()
	{
		if (isset($_SESSION['user'])) $this->response->redirect('/');
		
		$this->response->redirect('/account/login');
	}
	
	public function action_login()
	{
		$email = $this->request->post['email'];
		$password = $this->request->post['password'];
		
		$form_data = array();
		
		if (isset($email) && isset($password)) {
			$usermdl = $this->load->model('user');
			
			if ($usermdl->login($email, $password)) {
				$this->response->redirect('/account');
			} else {
				$form_data['error'] = 'Пользователь с таким мылом и паролем не был найден';
			}
		}
		
		$content = $this->load->view('account/login-form.tpl', $form_data);
		
		$data = array();
		$data['title'] = 'Вход';
		$data['content'] = $content;
		
		$this->response->setOutput($this->load->view('common/base.tpl', $data));
	}
	
	public function action_logout()
	{
		$this->load->model('user')->logout();
		$this->response->redirect('/');
	}
	
	public function action_register()
	{
		$input = $this->request->post;
		$data = array();
		$form_data = array();
		
		$data['title'] = 'Регистрация';
		
		//Послали / не послали данные
		if (!empty($input['hash'])) {
			$error = false;
			
			//echo $input['hash'];
			
			//Проверяем хэшку
			if (!$this->checkHash($input['hash'])) {
				$form_data['error']['hash'] = 'Данная форма не была отправлена с нашего сервера';
				$error = true;
			}
			
			//Проверяем имя
			if (empty($input['firstname'])) {
				$form_data['error']['firstname'] = 'Имя не должно быть пустым';
				$error = true;
			}
			
			//Email
			if (!preg_match('/^.+@.+$/', $input['email'])) {
				$form_data['error']['email'] = 'Email должен содержать символ \'@\' и хотя бы по одному символу с обеих сторон от него';
				$error = true;
			}
			
			//Пароль
			if (empty($input['password'])) {
				$form_data['error']['password'] = 'Пароль не должен быть пустым';
				$error = true;
			}
			
			//Подтверждение пароля
			if ($input['password'] != $input['password_confirm']) {
				$form_data['error']['password_confirm'] = 'Пароли не совпадают';
				$error = true;
			}
			
			$form_data = array_merge($form_data, array(
				'firstname' => $input['firstname'],
				'lastname' => $input['lastname'],
				'email' => $input['email'],
				'country' => $input['country']
			));
			
			if ($error) {
				$form_data['hash'] = $this->setNewHash();				
				$data['content'] = $this->load->view('account/register-form.tpl', $form_data);
			} else {
				//Создать нового пользователя и перенаправить на страницу успеха
				$this->unsetHash();
				
				$model = $this->load->model('user');
				
				$user = new User($input['firstname'], $input['lastname'], $input['email'], $input['country'], $input['password']);
				$id = $model->addUser($user);
				
				if (!is_null($id)) {
					$data['content'] = $this->load->view('account/register-success.tpl');
				} else {
					$form_data['hash'] = $this->setNewHash();
					$form_data['error']['other'] = 'Произошла неизвестная ошибка, попробуйте ещё раз';
					
					$data['content'] = $this->load->view('account/register-form.tpl', $form_data);
				}
			}
		} else {
			//Данные не посылали, выдаём пустую форму
			$form_data = array(
				'hash' => $this->setNewHash()
				/*'firstname' => '',
				'lastname' => '',
				'email' => '',
				'country' => ''*/
			);
			
			$data['content'] = $this->load->view('account/register-form.tpl', $form_data);
		}
		
		$this->response->setOutput($this->load->view('common/base.tpl', $data));
	}
	
	public function action_success()
	{
		$this->response->setOutput($this->load->view('common/base.tpl', array('title'=>'', 'content'=>$this->load->view('account/register-success.tpl'))));
	}
	
	function setNewHash()
	{
		$hash = md5(date("H:i:s"));			
		$_SESSION['register_form_hash'] = $hash;
		
		return $hash;
	}
	
	function checkHash($hash = '')
	{
		return isset($_SESSION['register_form_hash']) && $hash == $_SESSION['register_form_hash'];
	}
	
	function unsetHash()
	{
		unset($_SESSION['register_form_hash']);
	}
}