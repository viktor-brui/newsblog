<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

	public $error;
	public $errors;

	public function loginValidate($post) {
		if (!($post['email'])) {
			$this->errors['email'] = "Введите email";
			return false;
		} else if (!($post['password'])) {
			$this->errors['password'] = "Введите пароль";
			return false;
		}
		return true;
	}
	
	public function login($email, $password) {
		$params = [
			'email' => $email,
			'password' => md5($password),
		];
		return $this->db->row('SELECT * FROM users WHERE email = :email AND password = :password', $params);
	}

	public function contactValidate($post) {
		$nameLen = iconv_strlen($post['name']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 2 or $nameLen > 30) {
			$this->error = 'Имя должно содержать от 2 до 30 символов';
			return false;
		} elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error = 'E-mail указан неверно';
			return false;
		} elseif ($textLen < 10 or $textLen > 500) {
			$this->error = 'Сообщение должно содержать от 10 до 500 символов';
			return false;
		}
		return true;
	}

	public function postRatingAdd($rating, $id) {
		$post_rating = $this->db->row('SELECT `rating` FROM posts WHERE id = :id', ['id' => $id]);
		$post_rating = $post_rating[0]["rating"];
		$params = [
			'id' => $id,
			'rating' => $post_rating ? ($post_rating + $rating)/2 : $rating,
		];
		$this->db->query('UPDATE posts SET rating = :rating WHERE id = :id', $params);
	}

	public function postsCount() {
		return $this->db->column('SELECT COUNT(id) FROM posts');
	}

	public function postsList($route, $order = 'id') {
		$max = 10;
		$params = [
			'max' => $max,
			'start' => (((isset($route['page']) ? $route['page'] : 1) - 1) * $max),
		];
		return $this->db->row("SELECT * FROM posts ORDER BY $order DESC LIMIT :start, :max", $params);
	}

	public function getUser($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->row('SELECT * FROM users WHERE id = :id', $params);
	}

	public function updateViews($id) {
		$post_views = $this->db->row('SELECT `view` FROM posts WHERE id = :id', ['id' => $id]);
		$post_views = $post_views[0]["view"] + 1;
		$params = [
			'id' => $id,
			'view' => $post_views,
		];
		$this->db->query('UPDATE posts SET view = :view WHERE id = :id', $params);
	}

}