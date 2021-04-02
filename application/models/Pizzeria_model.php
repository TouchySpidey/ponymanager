<?php
class Pizzeria_model extends CI_Model {

	public function get_ingredients_categories() {

		$db_ingredients_categories = $this->db
		->select('category')
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->group_by('category')
		->get('ingredients')
		->result_array();
		return array_map(function($ingredient_category) {
			return $ingredient_category['category'];
		}, $db_ingredients_categories);

	}

	public function get_pizzas_categories() {

		$db_pizzas_categories = $this->db
		->select('category')
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->group_by('category')
		->get('pizzas')
		->result_array();
		return array_map(function($pizza_category) {
			return $pizza_category['category'];
		}, $db_pizzas_categories);

	}

	public function get_ingredients() {

		$db_ingredients = $this->db
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('ingredients')->result_array();
		$ingredients = [];
		foreach ($db_ingredients as $ingredient) {
			$ingredients[$ingredient['id_ingredient']] = $ingredient;
		}
		return $ingredients;

	}

	public function get_pizzas() {

		$db_pizzas = $this->db
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->join('pizzas_ingredients', 'pizzas.id_pizza = pizzas_ingredients.cod_pizza', 'left')
		->get('pizzas')->result_array();
		$pizzas = [];
		foreach ($db_pizzas as $pizza) {
			if (!isset($pizzas[$pizza['id_pizza']])) {
				$pizzas[$pizza['id_pizza']] = [
					'id_pizza' => $pizza['id_pizza'],
					'name' => $pizza['name'],
					'category' => $pizza['category'],
					'price' => $pizza['price'],
					'ingredients' => [],
				];
			}
			$pizzas[$pizza['id_pizza']]['ingredients'][] = $pizza['cod_ingredient'];
		}
		return $pizzas;

	}

	public function get_ingredients_by_category() {

		$db_ingredients = $this->get_ingredients();
		$list = [];
		foreach ($db_ingredients as $ingredient) {
			$list[$ingredient['category']][] = [
				'name' => $ingredient['name'],
				'price' => $ingredient['price'],
			];
		}
		debug($list);exit;
		return $list;

	}

	public function save_ingredient($ingredient = false) {

		if (!$ingredient) {
			return false;
		}

		if (
			!isset($ingredient['category']) || !isset($ingredient['name']) || !isset($ingredient['price'])
			||
			!is_string($ingredient['category']) || !is_string($ingredient['name'])
			||
			!is_string($ingredient['price']) && !is_numeric($ingredient['price'])
		) {
			return false;
		}

		$price = number_format(str_replace(',', '.', $ingredient['price']) ?: 0, 2, '.', '');
		$name = $ingredient['name'];
		$ingredients_categories = $this->get_ingredients_categories();

		foreach ($ingredients_categories as $category) {
			if (strtolower($category) == strtolower($ingredient['category'])) {
				$ingredient['category'] = $category;
				break;
			}
		}

		$category = $ingredient['category'];
		if (isset($ingredient['id_ingredient'])) {
			# update
			$db_ingredient = $this->db
			->where('id_ingredient', $ingredient['id_ingredient'])
			->where('active', 1)
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->get('ingredients')->result_array();
			if (empty($db_ingredient)) {
				$id_ingredient = null;
			} else {
				$id_ingredient = $db_ingredient[0]['id_ingredient'];
			}
		} else {
			# insert
			$id_ingredient = null;
		}
		$cod_company = _GLOBAL_COMPANY['id_company'];
		$this->db->replace('ingredients', compact('id_ingredient', 'cod_company', 'price', 'name', 'category'));

		return true;

	}

	public function save_pizza($pizza = false) {

		if (!$pizza) {
			return false;
		}

		if (
			!isset($pizza['category']) || !isset($pizza['name']) || !isset($pizza['price'])
			||
			!is_string($pizza['category']) || !is_string($pizza['name'])
			||
			!is_string($pizza['price']) && !is_numeric($pizza['price'])
		) {
			return false;
		}

		$price = number_format(str_replace(',', '.', $pizza['price']), 2, '.', '');
		$name = $pizza['name'];
		$pizzas_categories = $this->get_pizzas_categories();

		foreach ($pizzas_categories as $category) {
			if (strtolower($category) == strtolower($pizza['category'])) {
				$pizza['category'] = $category;
				break;
			}
		}

		$category = $pizza['category'];
		if (isset($pizza['id_pizza'])) {
			# update
			$db_pizza = $this->db
			->where('id_pizza', $pizza['id_pizza'])
			->where('active', 1)
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->get('pizzas')->result_array();
			if (empty($db_pizza)) {
				$id_pizza = null;
			} else {
				$id_pizza = $db_pizza[0]['id_pizza'];
			}
		} else {
			# insert
			$id_pizza = null;
		}
		$cod_company = _GLOBAL_COMPANY['id_company'];
		$this->db->replace('pizzas', compact('id_pizza', 'cod_company', 'price', 'name', 'category'));

		$id_pizza = $this->db->insert_id();
		$this->db->where('cod_pizza', $id_pizza)->delete('pizzas_ingredients');
		if (!isset($pizza['ingredients'])) {
			$pizza['ingredients'] = [];
		}
		foreach ($pizza['ingredients'] as $id_ingredient) {
			$this->db->insert('pizzas_ingredients', [
				'cod_pizza' => $id_pizza,
				'cod_ingredient' => $id_ingredient,
			]);
		}

		return true;

	}

	public function disable_ingredient($id = false) {
		$db_ingredient = $this->db
		->where('id_ingredient', $id)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->where('active', 1)
		->get('ingredients')->result_array();
		if (empty($db_ingredient)) {
			return false;
		} else {
			$db_ingredient = $db_ingredient[0];
			$db_ingredient['active'] = 0;
			$this->db->replace('ingredients', $db_ingredient);
			return true;
		}
	}

	public function disable_pizza($id = false) {
		$db_pizza = $this->db
		->where('id_pizza', $id)
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('pizzas')->result_array();
		if (empty($db_pizza)) {
			return false;
		} else {
			$db_pizza = $db_pizza[0];
			$db_pizza['active'] = 0;
			$this->db->replace('pizzas', $db_pizza);
			return true;
		}
	}

	public function menu_components() {
		return [
			'ingredients' => $this->get_ingredients(),
			'ingredients_categories' => $this->get_ingredients_categories(),
			'pizzas' => $this->get_pizzas(),
			'pizzas_categories' => $this->get_pizzas_categories(),
		];
	}

}
