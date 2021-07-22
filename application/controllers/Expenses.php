<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CB_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function index() {
		$costs = $this->expenses_model->getExpenses();
		$categories = $this->expenses_model->getCostCategories();
		$availableIcons = [
			'search', 'home', 'settings', 'account_circle',
			'info', 'delete', 'shopping_cart', 'visibility',
			'favorite', 'description', 'face', 'lock',
			'schedule', 'language', /* x */ 'thumb_up', 'phone_android',
			'event', 'dashboard', 'list', 'lightbulb',
			'question_answer', 'article', 'paid', 'trending_up',
			'shopping_bag', 'account_balance', 'credit_card', 'star_rate', /* x */
			'build', 'print', 'autorenew', 'work',
			'savings', 'store', 'pets', 'room',
			'accessibility_new', 'supervisor_account', 'leaderboard', 'pending',
			'pan_tool', 'nightlight_round', /* x */ 'bolt', 'people',
			'construction', 'health_and_safety', 'water_drop', 'euro',
			'camera_alt', 'audiotrack', 'call', 'mail_outline',
			'attachment', 'local_shipping', 'restaurant', 'delivery_dining', /* x */
			'local_gas_station', 'liquor', 'ac_unit', 'kitchen',
			'countertops', 'fire_extinguisher', 'smoking_rooms', 'chair',
			'coffee', 'local_fire_department', 'wifi', 'priority_high',
			'menu_book', 'vpn_key'
		];
		$this->load->view('notaspese', compact('costs', 'categories', 'availableIcons'));
	}

	public function getExpenses() {
		$expenses = $this->expenses_model->getExpenses();
		echo JSON_encode($expenses);
	}

	public function addCost() {
		# ajax
		$_status = false;
		if (
			   $this->input->post('title')
			&& $this->input->post('competence')
			&& $this->input->post('value')
		) {
			$_status = $this->expenses_model->addExpense($this->input->post());
		}
		$costs = $this->expenses_model->getExpenses();
		$cost_categories = $this->expenses_model->getCostCategories();
		echo JSON_encode(compact('costs', '_status', 'cost_categories'));
	}

	public function addCostCategory() {
		# ajax
		$_status = false;
		if (
			   $this->input->post('title')
			&& $this->input->post('icon')
			&& $this->input->post('color_scheme')
			&& $this->input->post('color')
			&& $this->input->post('color_icon')
			&& $this->input->post('color_whole')
		) {
			$_status = $this->expenses_model->addCostCategory($this->input->post());
		}
		$cost_categories = $this->expenses_model->getCostCategories();
		$costs = $this->expenses_model->getExpenses();
		echo JSON_encode(compact('cost_categories', '_status', 'costs'));
	}

	/*
		un utente può registrarsi, accedere, creare pizzerie
			> quando la crea ne diventa il master
		puoi dare privilegi su una pizzeria ad altri utenti
			> ma nessun utente può mai smuovere il master, né gli altri amministratori
		un utente tipicamente pony può anche non registrarsi mai
	*/

}
