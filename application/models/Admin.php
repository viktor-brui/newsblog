<?php

namespace application\models;

use application\core\Model;
use application\lib\Users;
use Imagick;

class Admin extends Model {

	public $error;
	public $errors;

	public function registrationValidate($post) {
		if ($this->isUniqueEmail($post['email'])) {
			$this->errors['email'] = 'такой email уже занят';
		} else if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'] = 'Email введен неверно';
		}
		if (!preg_match('/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $post['password'])) {
			$this->errors['password'] = 'Пароль должен быть минимум 6 символов, обязательно должны содержать цифру, буквы в разных регистрах и спец символ (знаки)';
		}
		if ($post['password'] !== $post['confirm_password']) {
			$this->errors['confirm_password'] = 'Пароль подтвержден неверно';
		}
		if (mb_strlen($post['name']) < 2 || preg_match('/[^a-zA-Zа-яА-Я0-9]/ui', $post['name'])) {
			$this->errors['name'] = 'Имя должно быть минимум 2 символа , только буквы и цифры';
		}
		return !count($this->errors);
	}
	
	public function postValidate($post, $type) {
		$nameLen = iconv_strlen($post['name']);
		$descriptionLen = iconv_strlen($post['description']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 100) {
			$this->error = 'Название должно содержать от 3 до 100 символов';
			return false;
		} elseif ($descriptionLen < 3 or $descriptionLen > 100) {
			$this->error = 'Описание должно содержать от 3 до 100 символов';
			return false;
		} elseif ($textLen < 10 or $textLen > 5000) {
			$this->error = 'Текст должнен содержать от 10 до 5000 символов';
			return false;
		}
		if (empty($_FILES['img']['tmp_name']) and $type == 'add') {
			$this->error = 'Изображение не выбрано';
			return false;
		}
		return true;
	}

	public function postAdd($post) {
		$params = [
			'id' => null,
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
			'rating' => null,
			'date' => date("Y-m-d H:i:s"),
			'view' => 0,
		];
		$this->db->query('INSERT INTO posts VALUES (:id, :name, :description, :text, :rating, :date, :view)', $params);
		return $this->db->getLastInsertId();
	}

	public function userAdd($post) {
		$mdpassword = md5($post['password']);
		$params = [
			'id' => null,
			'password' => $mdpassword,
			'email' => $post['email'],
			'name' => $post['name'],
			'role' => 2,
		];
		$this->db->query('INSERT INTO users VALUES (:id, :password, :email, :name, :role)', $params);
		return $this->db->getLastInsertId();
	}

	public function postEdit($post, $id) {
		$params = [
			'id' => $id,
			'name' => $post['name'],
			'description' => $post['description'],
			'text' => $post['text'],
		];
		$this->db->query('UPDATE posts SET name = :name, description = :description, text = :text WHERE id = :id', $params);
	}

	public function postUploadImage($path, $id) {
		$img = new Imagick($path);
		$img->cropThumbnailImage(1080, 600);
		$img->setImageCompressionQuality(80);
		$img->writeImage($_SERVER["DOCUMENT_ROOT"].'/public/materials/'.$id.'.jpg');
	}

	public function isPostExists($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->column('SELECT id FROM posts WHERE id = :id', $params);
	}

	public function postDelete($id) {
		$params = [
			'id' => $id,
		];
		$this->db->query('DELETE FROM posts WHERE id = :id', $params);
		unlink('public/materials/'.$id.'.jpg');
	}

	public function postData($id) {
		$params = [
			'id' => $id,
		];
		return $this->db->row('SELECT * FROM posts WHERE id = :id', $params);
	}

	public function login($email, $password) {
		$params = [
			'email' => $email,
			'password' => $password,
		];
		return $this->db->row('SELECT id FROM users WHERE email = :email AND password = :password', $params);
	}

	public function isUniqueEmail($email) {
		$params = [
			'email' => $email,
		];
		return $this->db->row('SELECT id FROM users WHERE email = :email', $params);
	}
}