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
		if ($id_customer) {
			$old_customer = $this->db
			->where('id_customer', $id_customer)
			->where('cod_company', _GLOBAL_COMPANY['id_company'])
			->get('customers')->result_array();
			$id_customer = null;
			if (!empty($old_customer)) {
				$old_customer = $old_customer[0];
				$id_customer = $old_customer['id_customer'];
			} else {
				$old_customer = false;
			}
		} else {
			$old_customer = false;
		}
		if ($name) {
			$north = 0;
			$east = 0;
			$travel_duration = null;
			if ($address) {
				if (!$old_customer || !$old_customer['north'] && !$old_customer['east'] || $old_customer['address'] != $address) {
					# motivi per fare il geocode:
						# cliente nuovo
						# cliente in update, era senza coordinate
						# cliente in update, indirizzo cambiato
					$geo = geocode($city, $address);
					if ($geo) {
						$north = $geo['north'];
						$east = $geo['east'];
						$travel_duration = distancematrix(_GLOBAL_COMPANY, $geo);
					}
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
					'travel_duration' => $travel_duration,
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
					'travel_duration' => $travel_duration,
					'created' => date('Y-m-d H:i:s'),
				];
				$this->db->insert('customers', $new_customer);
				$new_customer['id_customer'] = $id_customer = $this->db->insert_id();
			}
			$utf8ized = [];
			foreach ($new_customer as $key => $val) {
				if (!JSON_encode($val)) {
					$val = utf8ize($val);
				}
				$utf8ized[$key] = $val;
			}

			echo JSON_encode(['created' => true, 'id_customer' => $id_customer, 'customer_data' => $utf8ized]);
		} else {
			echo JSON_encode(['errors' => ['Non è stato possibile creare il cliente']]);
		}
	}

	public function find() {
		$string = $this->input->post('string');
		$uid = intval($this->input->post('uid'));
		$all = $this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
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
