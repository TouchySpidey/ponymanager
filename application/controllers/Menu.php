<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CB_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->menu();
	}

	public function menu() {
		$this->load->view('menu_manager', $this->pizzeria->menu_components());
	}

	public function delete_ingredient() {
		# AJAX
		$id_ingredient = $this->input->post('id_ingredient');
		$success = $this->pizzeria->disable_ingredient($id_ingredient);
		echo JSON_encode(array_merge(['success' => $success], $this->pizzeria->menu_components()));
	}

	public function delete_pizza() {
		# AJAX
		$id_pizza = $this->input->post('id_pizza');
		$success = $this->pizzeria->disable_pizza($id_pizza);
		echo JSON_encode(array_merge(['success' => $success], $this->pizzeria->menu_components()));
	}

	public function add_or_edit_ingredient() {
		# AJAX
		$ingredient_data = $this->input->post();
		if (!$this->input->post('category')) {
			$ingredient_data['category'] = $this->input->post('new_category');
		}
		$success = $this->pizzeria->save_ingredient($ingredient_data);
		echo JSON_encode(array_merge(['success' => $success], $this->pizzeria->menu_components()));
	}

	public function add_or_edit_pizza() {
		# AJAX
		$pizza_data = $this->input->post();
		if (!$this->input->post('category')) {
			$pizza_data['category'] = $this->input->post('new_category');
		}
		$success = $this->pizzeria->save_pizza($pizza_data);
		echo JSON_encode(array_merge(['success' => $success], $this->pizzeria->menu_components()));
	}

}
