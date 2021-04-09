<?php
class Pony_model extends CI_Model {

	public function ponies() {

		$db_ponies = $this->db
		->where('active', 1)
		->where('cod_company', _GLOBAL_COMPANY['id_company'])
		->get('ponies')
		->result_array();
		$ponies = [];
		foreach ($db_ponies as $pony) {
			$ponies[$pony['id_pony']] = $pony;
		}
		return $ponies;

	}

}
