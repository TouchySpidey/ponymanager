<?php
class Expenses_model extends CI_Model {

	public function getExpenses() {
		$expenses_db = $this->db
		->select('*')
		->select('expense_title AS title')
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('expenses')->result_array();
		return $expenses_db;
	}

	public function getCostCategories() {
		# che sarebbero i cdc / centri di costo
		$categories_db = $this->db
		->select(['expense_categories.*', 'expense_title'])
		->join('expenses', 'id_category = cod_category AND expenses.active = 1', 'LEFT')
		->where('expense_categories.active', 1)
		->where('expense_categories.cod_company', _GLOBAL_COMPANY['id_company'])
		->group_by(['id_category', 'expense_title'])
		->get('expense_categories')->result_array();
		$categories = [];
		foreach ($categories_db as $category_expense) {
			if (!isset($categories[$category_expense['id_category']])) {
				$category_expense['expense_title'] = [$category_expense['expense_title']];
				$categories[$category_expense['id_category']] = $category_expense;
			} else {
				array_push($categories[$category_expense['id_category']]['expense_title'], $category_expense['expense_title']);
			}
		}
		return $categories;
	}

	public function addCostCategory($data) {
		$val = $this->db->insert('expense_categories', [
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'title' => $data['title'],
			'icon' => $data['icon'],
			'scheme' => $data['color_scheme'],
			'color' => $data['color'],
			'color_icon' => $data['color_icon'],
			'color_whole' => $data['color_whole']
		]);
		return true;
	}

	public function addExpense($data) {
		$val = $this->db->insert('expenses', [
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'expense_title' => $data['title'],
			'description' => $data['description'],
			'competence' => $data['competence'],
			'f_v' => $data['f_v'],
			'cod_category' => $data['cod_category'],
			'value' => $data['value'],
		]);
		return true;
	}

}
