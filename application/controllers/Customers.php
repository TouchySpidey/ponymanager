<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CB_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function add_or_edit_customer() {
		$id_customer = $this->input->post('id_customer') ?: false;
		$name = $this->input->post('name');
		$telephone = $this->input->post('telephone');
		$city = $this->input->post('city');
		$doorbell = $this->input->post('doorbell');
		$address = $this->input->post('address');
		$north = $this->input->post('north');
		$east = $this->input->post('east');
		$position_ok = $address || ($north && $east);
		if ($name && $position_ok) {
			if ($north && $east && !$address) {
				$address = 0;
			} elseif (!$north && !$east && $address) {
				$north = 0;
				$east = 0;
			}
			if ($id_customer) {
				$old_customer = $this->db
				->where('id_customer', $id_customer)
				->where('cod_company', _GLOBAL_COMPANY['id_company'])
				->get('customers')->result_array();
				$id_customer = null;
				if (!empty($old_customer)) {
					$old_customer = $old_customer[0];
					$id_customer = $old_customer['id_customer'];
				}
			}
			if ($id_customer) {
				$new_customer = [
					'id_customer' => $id_customer,
					'cod_company' => _GLOBAL_COMPANY['id_company'],
					'name' => trim(strtoupper($name)),
					'metaphone' => metaphone($name),
					'doorbell' => $doorbell,
					'telephone' => $telephone,
					'city' => $city,
					'address' => $address,
					'north' => $north,
					'east' => $east,
					'created' => $old_customer['created'],
				];
				$this->db->replace('customers', $new_customer);
			} else {
				$new_customer = [
					'cod_company' => _GLOBAL_COMPANY['id_company'],
					'name' => trim(strtoupper($name)),
					'metaphone' => metaphone($name),
					'doorbell' => $doorbell,
					'telephone' => $telephone,
					'city' => $city,
					'address' => $address,
					'north' => $north,
					'east' => $east,
					'created' => date('Y-m-d H:i:s'),
				];
				$this->db->insert('customers', $new_customer);
			}
			echo JSON_encode(['created' => true]);
		} else {
			echo JSON_encode(['errors' => ['Non è stato possibile creare il cliente']]);
		}
	}

	public function find() {
		$string = $this->input->post('string');
		$uid = intval($this->input->post('uid'));
		$all = $this->db
		->select('*')
		->get('customers')
		->result_array();
		$matches = [];
		foreach ($all as $one) {
			$matches[] = [
				'match_perc' => string_similarity($string, $one['name']),
				'data' => $one
			];
		}
		usort($matches, function($a, $b) {
			return $a['match_perc'] < $b['match_perc'];
		});
		$utf8ized = [];
		foreach ($matches as $result) {
			if ($result['match_perc'] > 0) {
				$dummy = [];
				foreach ($result['data'] as $key => $val) {
					if (!JSON_encode($val)) {
						$val = utf8ize($val);
					}
					$dummy[$key] = $val;
				}
				$utf8ized[] = $dummy;
				if (count($utf8ized) > 4) {
					break;
				}
			}
		}

		echo JSON_encode([
			'results' => $utf8ized,
			'uid' => $uid
		]);

	}

}
