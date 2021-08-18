<?php
class Company_model extends CI_Model {

	public function get_shifts() {

		return $this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->order_by('from', 'ASC')
		->get('open_shifts')->result_array() ?: [[
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'from' => '10:10',
			'to' => '10:50',
		]];

	}

	public function set_shifts($_shifts) {
		$this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->delete('open_shifts');
		foreach ($_shifts as $i => $dump) {
			$_shifts[$i]['cod_company'] = _GLOBAL_COMPANY['id_company'];
		}
		$this->db->insert_batch('open_shifts', $_shifts);
	}

	public function get_payments() {

		return $this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('payment_methods')->result_array() ?: [[
			'cod_company' => _GLOBAL_COMPANY['id_company'],
			'description' => 'Contanti',
		]];

	}

	public function set_payments($_payments) {
		$this->db
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->delete('payment_methods');
		foreach ($_payments as $key => $_payment) {
			$_payments[$key] = [
				'cod_company' => _GLOBAL_COMPANY['id_company'],
				'description' => $_payment,
				'active' => 1,
			];
		}
		$this->db->insert_batch('payment_methods', $_payments);
	}

}
