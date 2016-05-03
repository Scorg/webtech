<?php

class ControllerArticle extends Controller {
	public function action_index($params=null)
	{
		if (!isset($params) || !isset($params[0])) $this->response->redirect('/');
		
		if ($params[1]=='edit') {
			$this->edit(intval($params[0]));
		} elseif ($params[1]=='delete'){
			$this->delete(intval($params[0]));
		} else {		
			$mdl = $this->load->model('article');
			$art = $mdl->getArticle(intval($params[0]));
			
			//Если указанной записи нету
			if (is_null($art)) $this->response->redirect('/');
			
			// Данные статьи
			$art_data = array(
				'id' => $art->id,
				'title' => $art->title,
				'date' => $art->date,
				'author' => $art->author,
				'text' => $mdl->htmlEncode($art->text)
			);
			
			if ($_SESSION['user']['id'] == $art->author_id) {
				$art_data['controls']['edit'] = "Изменить";
				$art_data['controls']['delete'] = "Удалить";
			}
			
			// Данные для страницы
			$data = array();
			$data['title'] = 'Бложик-лаба';
			$data['aside'] = $this->load->view('common/aside.tpl');
			$data['content'] = $this->load->view('article.tpl', $art_data);
			
			$this->response->setOutput($this->load->view('common/base.tpl', $data));
		}
	}
	
	public function action_new()
	{
		// Если пользователь не залогинился, то посылаем его
		if (!isset($_SESSION['user'])) $this->response->redirect('/account/login');
		
		$art = $this->request->post['article'];
		$form_data = array();
		
		if (isset($art)) {
			//Проверяем посланную статью
			if (empty($art['title'])) {
				$form_data['error']['title'] = 'Заголовок не может быть пустым';
			}
			
			if (empty($art['text'])) {
				$form_data['error']['text'] = 'Текст не может быть пустым';
			}
			
			if (!isset($art['error'])) {
				//Всё хроошо, сохраняем
				$mdl = $this->load->model('article');
				$id = $mdl->addArticle(new Article($art['title'], $art['text']));
				
				if ($id !== false) $this->response->redirect('/article/' . $id);
			}			
		}
		
		// Если ничего не сработало (не перенаправлили), то возвращаем назад
		$form_data['action'] = 'new';
		$form_data['title'] = $art['title'];
		$form_data['text'] = $art['text'];		
		$form = $this->load->view('article-form.tpl', $form_data);
		
		$data = array();
		$data['title'] = 'Новая запись';
		$data['aside'] = $this->load->view('common/aside.tpl');
		$data['content'] = $form;
		
		$this->response->setOutput($this->load->view('common/base.tpl', $data));
	}
	
	function edit($id)
	{
		// Если пользователь не залогинился, то посылаем его
		if (!isset($_SESSION['user'])) $this->response->redirect('/account/login');
		
		$art = $this->request->post['article'];
		$form_data = array();
		$form = null;

		$mdl = $this->load->model('article');
		$obj = $mdl->getArticle($id);
		
		// Если нет такой статьи
		if (is_null($obj)) { var_dump($ID); }//$this->response->redirect('/');
		
		// Если текущий пользователь не автор статьи
		if ($obj->author_id != $_SESSION['user']['id']) {
			var_dump($obj->author_id);
			var_daump($_SESSION['user']['id']);
			//$this->repsonse->redirect('/article/' . $id);
		}
		
		if (isset($art)) {
			/*if (empty($art['id'])) {
				action_new();
				exit(0);
			}*/
			
			//Проверяем посланную статью
			if (empty($art['title'])) {
				$form_data['error']['title'] = 'Заголовок не может быть пустым';
			}
			
			if (empty($art['text'])) {
				$form_data['error']['text'] = 'Текст не может быть пустым';
			}
			
			if (!isset($art['error'])) {
				//Всё хроошо, сохраняем				
				$obj = new Article();
				$obj->id = $id;//$art['id'];
				$obj->title = $art['title'];
				$obj->text = $art['text'];
				
				$result = $mdl->editArticle($obj);
				
				if ($result === true) 
					$this->response->redirect('/article/' . $id);
			} 
			
			// Если сохранение не прошло успешно, выдаём введённые данные
			$form_data['id'] = $id;
			$form_data['title'] = $art['title'];
			$form_data['text'] = $art['text'];
		} else {
			// Если в poste ничего не посылали
			$form_data['id'] = $obj->id;
			$form_data['title'] = $obj->title;
			$form_data['text'] = $obj->text;
		}
		
		$form_data['action'] = $id . '/edit';
		$form = $this->load->view('article-form.tpl', $form_data);
		
		$data = array();
		$data['title'] = 'Редактирование записи';
		$data['aside'] = $this->load->view('common/aside.tpl');
		$data['content'] = $form;
		
		$this->response->setOutput($this->load->view('common/base.tpl', $data));
	}
	
	function delete($id)
	{
		$mdl = $this->load->model('article');
		$mdl->deleteArticle($id);
		$this->response->redirect('/');
	}
}