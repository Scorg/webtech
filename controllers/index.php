<?php

class ControllerIndex extends Controller {	
	public function action_index()
	{
		$model = $this->load->model('article');
		$articles = $model->getArticles(0, 10);
		
		$rendered = array();
		
		if (empty($articles)) {
			$rendered[] = '<span class="article-title">Записей ещё нет</span>';
		} else {
			foreach ($articles as $a) {
				$data = array(
					'id' => $a->id,
					'title' => $a->title,
					'date' => $a->date,
					'author' => $a->author,
					'text' => $model->htmlEncode($a->text)
				);
			
				// Если текущий пользователь - автор, даём ему возможность изменить / удалить статью
				if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $a->author_id) {
					$data['controls']['edit'] = "Изменить";
					$data['controls']['delete'] = "Удалить";
				}
				
				$rendered[] = $this->load->view('article.tpl', $data);
			}
		}
		//var_dump($rendered);
		$aside_data = $this->load->view('common/aside.tpl');
		
		$data = array(
			'title' => 'Бложик-лаба',
			'aside' => $aside_data,
			'content' => implode('', $rendered)
		);
		
		$this->response->setOutput($this->load->view('common/base.tpl', $data));
	}
}