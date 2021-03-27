let ghostIngredient = $('#ghostIngredient').removeAttr('id').remove().removeClass('hidden');
let ghostPickableIngredient = $('#ghostPickableIngredient').removeAttr('id').remove().removeClass('hidden');
let ghostItem = $('#ghostItem').removeAttr('id').remove().removeClass('hidden');
let ghostCategory = $('#ghostCategory').removeAttr('id').remove().removeClass('hidden');
let ghostIngredientCategory = $('#ghostIngredientCategory').removeAttr('id').remove().removeClass('hidden');

function openNewIngredientModal(id_ingredient = false) {
	if (id_ingredient) {
		for (let i in ingredients) {
			if (ingredients[i].id_ingredient == id_ingredient) {
				$('#saveIngredient [name="category"]').val(ingredients[i].category);
				$('#addIngredientModal [name="id_ingredient"]').val(ingredients[i].id_ingredient);
				$('#saveIngredient [name="price"]').val(ingredients[i].price);
				$('#saveIngredient [name="name"]').val(ingredients[i].name);
			}
		}
	} else {
		$('#saveIngredient input, #saveIngredient select').val('');
	}
	$('#addIngredientModal').css('display', 'block');
}

function openNewPizzaModal(id_pizza = false) {
	$('#ingredientsContainer').empty();
	if (id_pizza) {
		for (let i in pizzas) {
			if (pizzas[i].id_pizza == id_pizza) {
				$('#savePizza [name="category"]').val(pizzas[i].category);
				$('#addPizzaModal [name="id_pizza"]').val(pizzas[i].id_pizza);
				$('#savePizza [name="price"]').val(pizzas[i].price);
				$('#savePizza [name="name"]').val(pizzas[i].name);
				for (let j in pizzas[i].ingredients) {
					$('#elencoIngredienti .ingrediente[data-id_ingredient="' + pizzas[i].ingredients[j] + '"]').trigger('click')
				}
			}
		}
	} else {
		$('#savePizza input, #savePizza select').val('');
	}
	$('#categorieContainer .categoria:first-child').trigger('click');
	$('#addPizzaModal').css('display', 'block');
}

function expand(category) {
	$(category).closest('.menu-category').addClass('expanded');
}

function collapse(category) {
	$(category).closest('.menu-category').removeClass('expanded');
}

function pick(ingredient) {
	let id_ingredient = $(ingredient).data('id_ingredient');
	let ingredient_name = $(ingredient).text();
	let ingredientsListItem = ghostIngredient.clone();
	ingredientsListItem.attr('data-id_ingredient', id_ingredient);
	ingredientsListItem.find('[ingredient-name]').text(ingredient_name);
	$('#ingredientsContainer').append(ingredientsListItem);
}

function unpick(ingredient) {
	let ingredientsListItem = $(ingredient).closest('.pizza-composition');
	ingredientsListItem.remove();
}

function filterIngredients(category_element) {
	let category = $(category_element).data('category');
	$('#elencoIngredienti .ingrediente').addClass('hidden');
	$('#elencoIngredienti .ingrediente[data-category="' + category + '"]').removeClass('hidden');
}

function db_has_changed() {

	$('#selectIngredientCategory option:not([default-option])').remove();
	$('#categorieContainer').empty();
	for (let cat in ingredients_categories.reverse()) { // reverse perché faccio prepend
		// aggiorna select categoria ingrediente in add_or_edit
		let option = document.createElement('option');
		option.value = option.textContent = ingredients_categories[cat];
		$('#selectIngredientCategory').prepend(option);

		// aggiorno la lista di categorie di ingredienti in add_or_edit pizza
		let ingredientCategory_ELEMENT = ghostIngredientCategory.clone();
		ingredientCategory_ELEMENT.text(ingredients_categories[cat]);
		ingredientCategory_ELEMENT.data('category', ingredients_categories[cat]);
		$('#categorieContainer').append(ingredientCategory_ELEMENT);
	}

	$('#selectPizzaCategory option:not([default-option])').remove();
	for (let cat in pizzas_categories.reverse()) { // reverse perché faccio prepend
		// aggiorna select categoria pizza in add_or_edit
		let option = document.createElement('option');
		option.value = option.textContent = pizzas_categories[cat];
		$('#selectPizzaCategory').prepend(option);
	}

	$('#ingredienti').empty();
	let ingredients_categories_ELEMENTS = {};
	for (let i in ingredients_categories.reverse()) {
		let category_ELEMENT = $(ghostCategory).clone();
		category_ELEMENT.find('[category-title]').text(ingredients_categories[i]);
		ingredients_categories_ELEMENTS[ingredients_categories[i]] = category_ELEMENT;
	}

	$('#elencoIngredienti').empty();
	for (let i in ingredients) {
		let item_ELEMENT = $(ghostItem).clone();
		item_ELEMENT.attr('data-id_ingredient', ingredients[i].id_ingredient);
		item_ELEMENT.click(function() {openNewIngredientModal(ingredients[i].id_ingredient)});
		item_ELEMENT.find('[item-name]').text(ingredients[i].name);
		item_ELEMENT.find('[item-price]').text(ingredients[i].price);
		ingredients_categories_ELEMENTS[ingredients[i].category].find('[list]').append(item_ELEMENT);

		// aggiorno la lista di ingredienti in add_or_edit pizza
		let ingredient_ELEMENT = ghostPickableIngredient.clone();
		ingredient_ELEMENT.text(ingredients[i].name);
		ingredient_ELEMENT.attr('data-category', ingredients[i].category);
		ingredient_ELEMENT.attr('data-id_ingredient', ingredients[i].id_ingredient);
		$('#elencoIngredienti').append(ingredient_ELEMENT);
	}
	for (let i in ingredients_categories_ELEMENTS) {
		let category_length = ingredients_categories_ELEMENTS[i].find('[list] .menu-item').length;
		if (category_length > 3) {
			ingredients_categories_ELEMENTS[i].find('.category-expander [items-count]').text(category_length - 3);
		} else {
			ingredients_categories_ELEMENTS[i].find('.category-expander, .category-collapser').remove();
		}
		if (category_length == 1) {
			ingredients_categories_ELEMENTS[i].find('[list]').addClass('one-item');
		} else if (category_length == 2) {
			ingredients_categories_ELEMENTS[i].find('[list]').addClass('two-items');
		} else {
			ingredients_categories_ELEMENTS[i].find('[list]').addClass('regular');
		}
		$('#ingredienti').append(ingredients_categories_ELEMENTS[i]);
		let current_height = ingredients_categories_ELEMENTS[i].find('[list]').css('height');
		ingredients_categories_ELEMENTS[i].find('[list]').css('height', current_height);
		ingredients_categories_ELEMENTS[i].removeClass('expanded');
	}

	$('#piatti').empty();
	let pizzas_categories_ELEMENTS = {};
	for (let cat in pizzas_categories) {
		let category_ELEMENT = $(ghostCategory).clone();
		category_ELEMENT.find('[category-title]').text(pizzas_categories[cat]);
		pizzas_categories_ELEMENTS[pizzas_categories[cat]] = category_ELEMENT;
	}
	for (let i in pizzas) {
		let item_ELEMENT = $(ghostItem).clone();
		item_ELEMENT.attr('data-id_pizza', pizzas[i].id_pizza);
		item_ELEMENT.click(function() {openNewPizzaModal(pizzas[i].id_pizza)});
		item_ELEMENT.find('[item-name]').text(pizzas[i].name);
		item_ELEMENT.find('[item-price]').text(pizzas[i].price);
		pizzas_categories_ELEMENTS[pizzas[i].category].find('[list]').append(item_ELEMENT);
	}
	for (let i in pizzas_categories_ELEMENTS) {
		let category_length = pizzas_categories_ELEMENTS[i].find('[list] .menu-item').length;
		if (category_length > 3) {
			pizzas_categories_ELEMENTS[i].find('.category-expander [items-count]').text(category_length - 3);
		} else {
			pizzas_categories_ELEMENTS[i].find('.category-expander, .category-collapser').remove();
		}
		if (category_length == 1) {
			pizzas_categories_ELEMENTS[i].find('[list]').addClass('one-item');
		} else if (category_length == 2) {
			pizzas_categories_ELEMENTS[i].find('[list]').addClass('two-items');
		} else {
			pizzas_categories_ELEMENTS[i].find('[list]').addClass('regular');
		}
		$('#piatti').append(pizzas_categories_ELEMENTS[i]);
		let current_height = pizzas_categories_ELEMENTS[i].find('[list]').css('height');
		pizzas_categories_ELEMENTS[i].find('[list]').css('height', current_height);
		pizzas_categories_ELEMENTS[i].removeClass('expanded');
	}

	$('#finder').trigger('input');
}

function menu_XHR(formElement = false, url = false, payload = false) {
	if (formElement && url && payload) {
		$.post(url, payload).always(function(data) {
			try {
				let response = JSON.parse(data);
				if ('success' in response || !response.success) {
					if ('errors' in response) {
						console.log(response.errors);
					} else {
						if ('ingredients' in response) {
							ingredients = response.ingredients;
						}
						if ('ingredients_categories' in response) {
							ingredients_categories = response.ingredients_categories;
						}
						if ('pizzas' in response) {
							pizzas = response.pizzas;
						}
						if ('pizzas_categories' in response) {
							pizzas_categories = response.pizzas_categories;
						}
					}
				}
				db_has_changed();
			} catch(e) {
				console.log(e);
			}
			closeModal(formElement);
		});
	}
}

$('#saveIngredient').submit(function(e) {
	e.preventDefault();

	let self = this;

	let url = $(self).attr('action');

	let payload = {
		id_ingredient: $(self).find('[name="id_ingredient"]').val(),
		category: $(self).find('[name="category"]').val(),
		new_category: $(self).find('[name="new_category"]').val(),
		name: $(self).find('[name="name"]').val(),
		price: $(self).find('[name="price"]').val(),
	};

	menu_XHR(self, url, payload);

	return false;
});

$('#savePizza').submit(function(e) {
	e.preventDefault();

	let self = this;

	let url = $(self).attr('action');

	let pizzaIngredients = [];
	$('#ingredientsContainer .pizza-composition').each(function(i, v) {
		pizzaIngredients.push($(v).attr('data-id_ingredient'));
	});

	let payload = {
		id_pizza: $(self).find('[name="id_pizza"]').val(),
		category: $(self).find('[name="category"]').val(),
		new_category: $(self).find('[name="new_category"]').val(),
		name: $(self).find('[name="name"]').val(),
		price: $(self).find('[name="price"]').val(),
		ingredients: pizzaIngredients,
	};

	menu_XHR(self, url, payload);

	return false;
});

$('#deleteIngredient').submit(function(e) {
	e.preventDefault();

	let self = this;

	let url = $(self).attr('action');

	let payload = {
		id_ingredient: $(self).find('[name="id_ingredient"]').val(),
	};

	menu_XHR(self, url, payload);

	return false;
});

$('#deletePizza').submit(function(e) {
	e.preventDefault();

	let self = this;

	let url = $(self).attr('action');

	let payload = {
		id_pizza: $(self).find('[name="id_pizza"]').val(),
	};

	menu_XHR(self, url, payload);

	return false;
});

db_has_changed(); // not really;

function optimizeSearchResults(results) {
	let returnSet = [];
	results.sort(function(x, y) {
		if (x.match < y.match) {
			return 1;
		}
		if (x.match > y.match) {
			return -1;
		}
		return 0;
	});
	for (let i in results) {
		returnSet.push(results[i].element);
	}
	return returnSet;
}

$('#finder').on('input', function() {
	let find = $(this).val();

	$('.search-results [list]').empty();

	let pizzas_matches = [];
	for (let i in pizzas) {
		let name = pizzas[i].name;
		let match = string_similarity(name, find);
		if (match > 0.3) {
			// match
			let id_pizza = pizzas[i].id_pizza;
			let pizzaItemElement = $('#piatti .menu-item[data-id_pizza="' + id_pizza + '"]').clone(true);
			pizzas_matches.push({
				match: match,
				element: pizzaItemElement
			});
		}
	}
	$('#pizzasResults [list]').append(optimizeSearchResults(pizzas_matches));

	let ingredients_matches = [];
	for (let i in ingredients) {
		let name = ingredients[i].name;
		let match = string_similarity(name, find);
		if (match > 0.3) {
			// match
			let id_ingredient = ingredients[i].id_ingredient;
			let ingredientItemElement = $('#ingredienti .menu-item[data-id_ingredient="' + id_ingredient + '"]').clone(true);
			ingredients_matches.push({
				match: match,
				element: ingredientItemElement
			});
		}
	}
	$('#ingredientsResults [list]').append(optimizeSearchResults(ingredients_matches));

	$('.search-results').each(function(i, v) {
		if ($(v).find('[list]').children().length) {
			$(v).removeClass('hidden');
		} else {
			$(v).addClass('hidden');
		}
	});
});
