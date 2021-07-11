<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Nota Spese', ['notaspese']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div>
			<div class="general-heading">Le tue spese</div>
			<div id="tableContainer">
				<table style="width: 100%;">
					<thead>
						<tr>
							<th>Categoria</th>
							<th>Data</th>
							<th>Titolo</th>
							<th>Importo</th>
							<th>Descrizione</th>
						</tr>
					</thead>
					<tbody id="costList">
						<tr>
							<td>
								<div class="round-btn light-blue"><i class="mdi mdi-home"></i></div>
							</td>
							<td>
								<div>22/06/2021</div>
							</td>
							<td>
								<div>Title</div>
							</td>
							<td>
								<div>1.400,00</div>
							</td>
							<td>
								<div>Subtitle</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="round-btn light-blue"><i class="mdi mdi-home"></i></div>
							</td>
							<td>
								<div>22/06/2021</div>
							</td>
							<td>
								<div>Title</div>
							</td>
							<td>
								<div>1.400,00</div>
							</td>
							<td>
								<div>Subtitle</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<button onclick="promptNewCost()" class="fab blue"><i class="mdi mdi-plus"></i></button>
	</div>


	<div id="addCostModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content order-modal-content v-flex" style="width: auto;">
			<div class="modal-topbar">
				<div class="d-flex">
					<div onclick="closeModal(this)" class="action-container actionable"><i class="mdi mdi-arrow-left"></i></div>
					<div class="ml-auto action-container actionable" onclick="delete_order()"><i class="mdi mdi-delete"></i></div>
				</div>
			</div>
			<div id="addCost" class="flex-1 v-flex">
				<div class="d-flex">
					<div class="ml-auto mr-auto"><input class="md-input" type="text" placeholder="Titolo" /></div>
				</div>
				<div class="d-flex">
					<div>
						<i class="mdi mdi-calendar"></i>
					</div>
					<div id="dayScroller">
						<div class="d-flex days-container">
						</div>
					</div>
				</div>
				<div class="d-flex">
					<div class="radio-group">
						<div>
							<label>
								<input type="radio" name="f_v" />
								Fisso
							</label>
						</div>
						<div>
							<label>
								<input type="radio" name="f_v" />
								Variabile
							</label>
						</div>
					</div>
					<div class="radio-group">
						<div>
							<label>
								<input type="radio" name="d_i" />
								Diretto
							</label>
						</div>
						<div>
							<label>
								<input type="radio" name="d_i" />
								Indiretto
							</label>
						</div>
					</div>
					<div><input class="md-input" type="text" placeholder="Importo" /></div>
				</div>
				<div class="d-flex">
					<div class="d-flex flex-1">
						<div class="expense-category">
							<div class="round-btn light-blue"><i class="mdi mdi-home"></i></div>
						</div>
						<div class="expense-category">
							<div class="round-btn green-300"><i class="mdi mdi-cart"></i></div>
						</div>
						<div class="expense-category">
							<div class="round-btn red-600"><i class="mdi mdi-fire"></i></div>
						</div>
						<div class="expense-category">
							<div class="round-btn amber"><i class="mdi mdi-flash"></i></div>
						</div>
					</div>
					<div class="expense-category">
						<div class="round-btn grey"><i class="mdi mdi-plus"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?= import_js('notaspese') ?>
</body>
</html>
