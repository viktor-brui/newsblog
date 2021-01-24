<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Admin;
use application\lib\Users;

use application\lib\Db;

class MainController extends Controller {

	public function indexAction() {
		$order = 'id';
		if (isset($_GET['order'])) {
			$order = $_GET['order'];
		}
		$pagination = new Pagination($this->route, $this->model->postsCount());
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $this->model->postsList($this->route, $order),
		];
		$this->view->render('Главная страница', $vars);
	}

	public function aboutAction() {
		$this->view->render('Обо мне');
	}


	public function contactAction() {
		if (!empty($_POST)) {
			if (!$this->model->contactValidate($_POST)) {
				$this->view->message('error', $this->model->error);
			}
			mail('viktor-brui@mail.ru', 'Сообщение из блога', $_POST['name'].'|'.$_POST['email'].'|'.$_POST['text']);
			$this->view->message('success', 'Сообщение отправлено Администратору');
		}
		$this->view->render('Контакты');
	}

	public function loginAction() {
		if (isset($_SESSION['admin'])) {
			$this->view->redirect('admin/add');
		}
		if (!empty($_POST)) {
			$validationForm = $this->model->loginValidate($_POST);
			if (!$validationForm) {
				$this->view->responseValidate('error', $this->model->errors);
			}
			$user = $this->model->login($_POST['email'], $_POST['password']);
			if ($user) {
				$_SESSION['user'] = $user[0]['id'];
				if ($user[0]['role'] == 1) {
					$_SESSION['admin'] = true;
					$this->view->location('admin/add');
				} else {
					$this->view->location('account');
					$this->view-render("Личный кабинет", $vars);
				}
			} else {
				$this->view->message('error', $this->model->error);
			}
		}
		$this->view->render('Вход');
	}

	public function ratingAction() {
		if (!empty($_POST)) {
			if (isset($_COOKIE["post_rating"]) && in_array($_POST['id'], json_decode($_COOKIE["post_rating"], true))) {
				$this->view->message('error', 'но вы уже сотавляли оценку.');
			} else {
				if (isset($_COOKIE["post_rating"])) {
					$post_rating = json_decode($_COOKIE["post_rating"], true);
					$post_rating[] = $_POST['id'];
				} else {
					$post_rating = [$_POST['id']];
				}
				setcookie("post_rating", json_encode($post_rating));
				$this->model->postRatingAdd($_POST['rating'], $_POST['id']);
			}
				$this->view->message('success', 'Спасибо за Вашу оценку');
		}
		$this->view->render('Пост');
	}

	public function logoutAction() {
		unset($_SESSION['user']);
		$this->view->redirect('');
	}

	public function accountAction() {
		$user = $this->model->getUser($_SESSION['user']);
		$vars['name'] = $user[0]['name'];
		$vars['email'] = $user[0]['email'];
		$this->view->render('Личный кабинет', $vars);
	}

	public function postAction() {
		$this->model->updateViews($this->route['id']);
		$adminModel = new Admin;
		if (!$adminModel->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$vars = [
			'data' => $adminModel->postData($this->route['id'])[0],
		];
		$this->view->render('Пост', $vars);
	}
}