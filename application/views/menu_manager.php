<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Menù', ['menu_manager']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="sticky-toolbar" style="padding: 16px;">
			<div class="d-flex">
				<div class="d-flex"><div class="mt-auto mb-auto btn blue" onclick="openNewPizzaModal()"><i class="mdi mdi-plus"></i> Piatto</div></div>

				<div class="ml-auto mr-auto"><input class="md-input" type="text" id="finder" placeholder="Cerca" /></div>

				<div class="d-flex"><div class="mt-auto mb-auto btn blue" onclick="openNewIngredientModal()"><i class="mdi mdi-plus"></i> Ingrediente</div></div>
			</div>
		</div>
		<div id="scrollpage">
			<div class="d-flex">
				<div class="col-300">
					<div class="menu-category expanded hidden search-results" id="pizzasResults">
						<div class="menu-category-label" category-title>Ricerca</div>
						<div list></div>
					</div>
					<div id="piatti"></div>
				</div>
				<div class="col-300 ml-auto">
					<div class="menu-category expanded hidden search-results" id="ingredientsResults">
						<div class="menu-category-label" category-title>Ricerca</div>
						<div list></div>
					</div>
					<div id="ingredienti">
						<div class="menu-category hidden expanded" id="ghostCategory">
							<div class="menu-category-label" category-title>Verdure</div>
							<div list>
								<div class="menu-item hidden" id="ghostItem">
									<div class="d-flex">
										<div item-name>Zucchine</div>
										<div class="ml-auto price-tag" item-price>€ 1,00</div>
									</div>
								</div>
							</div>
							<div class="category-collapser" onclick="collapse(this)">
								<div class="d-flex">
									<div><i class="mdi mdi-chevron-up"></i></div>
									<div class="ml-auto mr-auto">Riduci</div>
									<div><i class="mdi mdi-chevron-up"></i></div>
								</div>
							</div>
							<div class="category-expander" onclick="expand(this)">
								<div class="d-flex">
									<div><i class="mdi mdi-chevron-down"></i></div>
									<div class="ml-auto mr-auto">+<span items-count>10</span></div>
									<div><i class="mdi mdi-chevron-down"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="addIngredientModal" class="w3-modal modal-container">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content">
			<div class="w3-container">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<form autocomplete="off" method="POST" action="<?= site_url() ?>menu/add_or_edit_ingredient/company/<?= _GLOBAL_COMPANY['uri_name'] ?>" id="saveIngredient">
					<input type="hidden" name="id_ingredient" />
					<div>
						<div class="d-flex input-block">
							<div>
								Categoria:
								<select class="md-input" name="category" id="selectIngredientCategory">
									<option disabled default-option>───────────</option>
									<option value='' default-option>Crea nuova</option>
								</select>
							</div>
							<div>
								Nome Categoria
								<input type="text" class="md-input" name="new_category" />
							</div>
						</div>
						<div class="d-flex input-block">
							<div>
								Nome Ingrediente
								<input class="md-input" type="text" name="name" />
							</div>
							<div>
								Prezzo
								<input class="md-input" type="text" name="price" />
							</div>
						</div>
					</div>
					<button type="submit" class="hidden"></button>
				</form>
				<div class="d-flex">
					<form method="POST" id="deleteIngredient" action="<?= site_url() ?>menu/delete_ingredient/company/<?= _GLOBAL_COMPANY['uri_name'] ?>">
						<input type="hidden" class="hidden" name="id_ingredient" />
						<button class="btn red-800 js-disable-ingredient"><i class="mdi mdi-delete"></i> Elimina</button>
					</form>
					<button class="ml-auto btn gblue" onclick="$('#saveIngredient').submit()"><i class="mdi mdi-check"></i> Salva</button>
				</div>
			</div>
		</div>
	</div>

	<div id="addPizzaModal" class="w3-modal modal-container">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content">
			<div class="w3-container">
				<form autocomplete="off" method="POST" action="<?= site_url() ?>menu/add_or_edit_pizza/company/<?= _GLOBAL_COMPANY['uri_name'] ?>" id="savePizza">
					<input type="hidden" class="hidden" name="id_pizza" />
					<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
					<div class="d-flex">
						<div>
							<div id="pizzeComponent" order-component>
								<div id="categorieContainer" class="flex-wrap">
									<div class="categoria" id="ghostIngredientCategory" onclick="filterIngredients(this)">Pizze basic</div>
								</div>
								<div id="elencoIngredienti" class="flex-wrap">
									<div class="ingrediente" id="ghostPickableIngredient" onclick="pick(this)" data-elenco="basic">Margherita</div>
								</div>
							</div>
							<div>
								<div class="d-flex input-block">
									<div>
										Categoria
										<select class="md-input" name="category" id="selectPizzaCategory">
											<option disabled default-option>───────────</option>
											<option value='' default-option>Crea nuova</option>
										</select>
									</div>
									<div>
										Categoria
										<input type="text" class="md-input" name="new_category" />
									</div>
								</div>
								<div class="d-flex input-block">
									<div>
										Nome
										<input class="md-input" type="text" name="name" />
									</div>
									<div>
										Prezzo
										<input class="md-input" type="text" name="price" />
									</div>
								</div>
							</div>
						</div>
						<div class="ml-auto" id="ingredientsContainer">
							<div id="ghostIngredient" class="pizza-composition">
								<div class="d-flex">
									<div class="mb-auto mt-auto small-round-button red-800" onclick="unpick(this)"><i class="mdi mdi-close"></i></div>
									<div ingredient-name></div>
								</div>
							</div>
						</div>
					</div>
				</form>
				<div class="d-flex">
					<form method="POST" id="deletePizza" action="<?= site_url() ?>menu/delete_pizza/company/<?= _GLOBAL_COMPANY['uri_name'] ?>">
						<input type="hidden" class="hidden" name="id_pizza" />
						<button class="btn red-800 js-disable-pizza"><i class="mdi mdi-delete"></i> Elimina</button>
					</form>
					<button class="ml-auto btn gblue" onclick="$('#savePizza').submit()" type="submit"><i class="mdi mdi-check"></i> Salva</button>
				</div>
			</div>
		</div>
	</div>

	<script>
	let ingredients = JSON.parse(`<?= JSON_encode($ingredients) ?>`);
	let ingredients_categories = JSON.parse(`<?= JSON_encode($ingredients_categories) ?>`);
	let pizzas = JSON.parse(`<?= JSON_encode($pizzas) ?>`);
	let pizzas_categories = JSON.parse(`<?= JSON_encode($pizzas_categories) ?>`);
	</script>

	<?= import_js('menu_manager') ?>
</body>
</html>
