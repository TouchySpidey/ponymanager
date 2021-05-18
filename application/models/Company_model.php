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

}
