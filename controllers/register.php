<?php

class ControllerRegister extends Controller {
	public function action_index()
	{
		$input = $this->request->post;
		$form_data = array();
		
		//Послали / не послали данные
		if (!empty($input)) {
			$error = false;
			
			//Проверяем имя
			if (empty($input['firstname'])) {
				$form_data['error_firstname'] = 'Имя не должно быть пустым';
				$error = true;
			}
			
			//Email
			if (!preg_match('/^.+@.+$/', $input['email'])) {
				$form_data['error_email'] = 'Email должен содержать символ \'@\' и хотя бы по одному символу с обеих сторон от него';
				$error = true;
			}
			
			//Пароль
			if (empty($input['password'])) {
				$form_data['error_password'] = 'Пароль не должен быть пустым';
				$error = true;
			}
			
			//Подтверждение пароля
			if ($input['password'] != $input['password_confirm']) {
				$form_data['error_password_confirm'] = 'Пароли не совпадают';
				$error = true;
			}
			
			if ($error) {
				$form_data = array_merge($form_data, array(
					'firstname' => $input['firstname'],
					'lastname' => $input['lastname'],
					'email' => $input['email'],
					'country' => $input['country']
				));
			} else {
				//Создать нового пользователя и перенаправить на страницу успеха
			}
		} else {
			//Данные не посылали, выдаём пустую форму
			/*$form_data = array(
				'firstname' => '',
				'lastname' => '',
				'email' => '',
				'country' => ''
			);*/
		}
		
		$data = array(
			'title' => 'Регистрация',
			'content' => $this->render('register-form.tpl', $form_data)
		);
		
		$this->response->setOutput($this->render('common/base.tpl', $data));
	}
}