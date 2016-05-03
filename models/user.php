<?php

class User {
	public function __construct($firstname='', $lastname='', $email='', $country='', $password=null)
	{
		$this->id = null;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->country = $country;
		$this->password = $password;
	}
	
	public $id;
	public $firstname;
	public $lastname;
	public $email;
	public $country;
	public $password;
}
	

class ModelUser extends Model {
	static $saltlen = 8;
	
	public function addUser($user)
	{
		if (empty($user) || empty($user->firstname) || empty($user->email) || empty($user->password)) return null;
		
		$salt = base64_encode(mcrypt_create_iv(self::$saltlen));
		$sql = $this->db->prepare('insert into ' . DB_PREFIX . 'user (firstname, lastname, email, country, salt, password) values (?,?,?,?,?,?)');
				
		if ($sql->execute(array($user->firstname, $user->lastname, $user->email, $user->country, $salt, $this->hashPassword($salt, $user->password)))) {
			return $this->db->lastInsertId();
		}
		
		return null;
	}
	
	public function editUser($user)
	{
		if (empty($user) || empty($user->firstname) || empty($user->email) || empty($user->password)) return null;
		$sql = $this->db->prepare('update ' . DB_PREFIX . 'user set firstname=?, lastname=?, email=?, country=? where id=?');
		return $sql->execute(array($user->firstname, $user->lastname, $user->email, $user->country, (int)$user->id));
	}
	
	public function editPassword($id, $password)
	{
		$salt = base64_encode(mcrypt_create_iv(self::$saltlen));
		$sql = $this->db->prepare('update ' . DB_PREFIX . 'user set salt=?, password=? where id=?');
		return $sql->execute(array($salt, $this->hashPassword($salt, $password), (int)$id));
	}
	
	public function getUser($id)
	{
		$sql = $this->db->prepare('select firstname, lastname, email, country from ' . DB_PREFIX . 'user where id=?');
		
		if ($sql->execute(array((int)$id))) {
			$row = $sql->fetch();	
			if ($row===false) return null;
			return new User($row['firstname'], $row['lastname'], $row['email'], $row['country']);
		}
		
		return null;
	}
	
	public function getUsers($offset=0, $limit=null)
	{
		$sql = $this->db->prepare('select id, firstname, lastname, email, country from ' . DB_PREFIX . 'user limit ? offset ?');
		
		if (is_null($limit)) $limit = PHP_INT_MAX;
		
		$sql->bindParam(1, $limit, PDO::PARAM_INT);
		$sql->bindParam(2, $offset, PDO::PARAM_INT);
		
		if ($sql->execute()) {
			return $sql->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
		}
		
		return array();
	}
	
	public function login($email, $password)
	{
		$sql = $this->db->prepare('select id, firstname, lastname, email, country from ' . DB_PREFIX . 'user where email=? and password=md5(concat(salt, md5(concat(salt, ?))))');
		
		if ($sql->execute(array($email, md5($password))) && $row = $sql->fetch()) {
			// $_SESSION['user']['id'] = $row['id'];
			// $_SESSION['user']['email'] = $row['email'];
			// $_SESSION['user']['firstname'] = $row['firstname'];
			// $_SESSION['user']['lastname'] = $row['lastname'];
			// $_SESSION['user']['country'] = $row['country'];
			$_SESSION['user'] = $row;
			
			return true;
		}
		
		return false;
	}
	
	public function logout()
	{
		if (!isset($_SESSION['user'])) return false;
		
		unset($_SESSION['user']);
		return true;
	}
	
	protected function hashPassword($salt, $password)
	{
		return md5($salt . md5($salt . md5($password)));
	}
}