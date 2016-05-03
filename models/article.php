<?php
class Article {
	public $id;
	public $title;
	public $date;
	public $author;
	public $author_id;
	public $text;
	
	public function __construct($title='', $text='')
	{
		$this->title = $title;
		$this->text = $text;
	}
}

class ModelArticle extends Model {
	public function addArticle($article)
	{
		if (!isset($article->author_id) && isset($_SESSION['user'])) {
			$article->author_id = (int)$_SESSION['user']['id'];
		}
		
		if (empty($article->title) || empty($article->text) || !is_int($article->author_id)) return null;
		
		// Существует ли указанный автор
		$sql = $this->db->prepare('select * from ' . DB_PREFIX . 'user where id=?');
		if (!$sql->execute(array($article->author_id))) return null;
		
		$result = $sql->fetchAll();

		if (empty($result)) return null;
		
		if ($this->db->prepare('insert into ' . DB_PREFIX . 'article (title, date, author_id, text) values (?,?,?,?)')->execute(array($article->title, date('Y-m-d H:i:s', time()), $article->author_id, $article->text))) {
			return $this->db->lastInsertId();
		}
		
		return null;
	}
	
	public function editArticle($article)
	{
		if (empty($article->title) || empty($article->text)) return false;;
		$sql = $this->db->prepare('update ' . DB_PREFIX . 'article set title=?, date=?, text=? where id=?');
		if ($sql===false) return false;
		return $sql->execute(array($article->title, date('Y-m-d H:i:s', time()), $article->text, $article->id));
	}
	
	public function getArticle($id)
	{
		if (!is_int($id)) return null;
		
		$sql = $this->db->prepare('select a.id, a.title, a.date, a.author_id, concat_ws(\' \', u.firstname, u.lastname) as \'author\', text from ' . DB_PREFIX . 'article a join ' . DB_PREFIX . 'user u on (u.id=a.author_id) where a.id=?');
		$sql->bindParam(1, $id, PDO::PARAM_INT);
		
		if (!$sql->execute()) return null;
		
		$row = $sql->fetch();		
		if ($row===false) return null;
		
		$a = new Article();
		$a->id = $id;
		$a->title = $row['title'];
		$a->date = $row['date'];
		$a->author_id = $row['author_id'];
		$a->author = $row['author'];
		$a->text = $row['text'];
		return $a;
	}
	
	public function getArticles($offset=0, $limit=null)
	{
		$sql = $this->db->prepare('select a.id, a.title, a.date, a.author_id, concat_ws(\' \', u.firstname, u.lastname) as \'author\', a.text from ' . DB_PREFIX . 'article a join ' . DB_PREFIX . 'user u on (u.id=a.author_id) order by a.date desc limit ? offset ?');
		
		if (is_null($limit)) $limit = PHP_INT_MAX;
		
		$sql->bindParam(1, $limit, PDO::PARAM_INT);
		$sql->bindParam(2, $offset, PDO::PARAM_INT);
		
		if (false===$sql->execute()) {
			return array();
		}
		
		$arr = $sql->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Article');
		//var_dump($arr);
		if ($arr===false) return array();
		return $arr;
	}
	
	public function htmlEncode($text)
	{
		/*$rep = array(
			'\r' => '',
			'\n' => '<br/>'
		);
		
		return str_replace(array_keys($rep), array_values($rep), $text);*/
		return nl2br($text);
	}
}