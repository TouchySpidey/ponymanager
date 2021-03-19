<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

	public function add_customer()
	{
		$name = $this->input->post('name');
		$telephone = $this->input->post('telephone');
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
			$new_customer = [
				'name' => strtoupper($name),
				'metaphone' => metaphone($name),
				'telephone' => $telephone,
				'address' => $address,
				'north' => $north,
				'east' => $east,
				'created' => date('Y-m-d H:i:s'),
			];
			$this->db->insert('customers', $new_customer);
			echo JSON_encode(['created' => true]);
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

	public function old_find() {
		$string = $this->input->post('string');
		$uid = intval($this->input->post('uid'));
		$mp = metaphone($string);
		$results = $this->db
		->select('*')
		->like('metaphone', $mp)
		->get('customers')
		->result_array();
		$utf8ized = [];
		foreach ($results as $result) {
			$dummy = [];
			foreach ($result as $key => $val) {
				if (!JSON_encode($val)) {
					$val = utf8ize($val);
				}
				$dummy[$key] = $val;
			}
			$utf8ized[] = $dummy;
		}

		echo JSON_encode([
			'results' => $utf8ized,
			'uid' => $uid
		]);

	}

}
