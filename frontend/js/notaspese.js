let $ghostMiniFabV = $('#ghostMiniFabV').remove().removeAttr('id');
let $ghostMiniFabF = $('#ghostMiniFabF').remove().removeAttr('id');
let $ghostTableRow = $('#ghostTableRow').remove().removeAttr('id');
let $ghostCategory = $('#ghostCategory').remove().removeAttr('id');
let $ghostDayCell = $('#ghostDayCell').remove().removeAttr('id');
let $ghostMonthBox = $('#ghostMonthBox').remove().removeAttr('id');
let dayScroller = $('#dayScroller').get(0);

$(function() {

	$('#addCostDialog').data('metadialog').listen('MDCDialog:closing', (why) => {
		if (why.detail.action == 'saveExpense') {
			let expense = validateNewExpense();
			if (expense) {
				$('#speseDT').data('metatable').showProgress();
				$.post(site_url + 'expenses/addCost' + company_url_suffix, expense).done((data) => {
					let json = JSON.parse(data);
					if (json._status) {
						php_expenses = json.costs;
						reloadExpenses();
						php_categories = json.cost_categories;
						reloadCategories();
					}
				}).always(() => {
					$('#speseDT').data('metatable').hideProgress();
				});
			} else {
				// reset dialog, big error?!
			}
		}
	});

	$('#newCategoryDialog').data('metadialog').listen('MDCDialog:closing', (why) => {
		if (why.detail.action == 'saveCategory') {
			color_icon = colorPicked + colorSchemas[schemaPicked].icon;
			color_whole = colorPicked + colorSchemas[schemaPicked].whole;
			$.post(site_url + 'expenses/addCostCategory' + company_url_suffix, {
				title: $('#newCategoryTitle').val(),
				icon: $('#availableIcons .icon-container:not(.unselected)').data('icon_name'),
				color_scheme: schemaPicked,
				color: colorPicked,
				color_icon: color_icon,
				color_whole: color_whole,
			}).done((data) => {
				let json = JSON.parse(data);
				if (json._status) {
					php_categories = json.cost_categories;
					reloadCategories();
				}
			});
		}
	});

});

function promptNewCost() {
	$('#addCostDialog').data('metadialog').open();
}

function promptNewCategory() {
	$('#newCategoryDialog').data('metadialog').open();
}

function reloadExpensesFromDB() {
	$('#speseDT').data('metatable').showProgress();
	$.get(site_url + 'expenses/getExpenses' + company_url_suffix).done(data => {
		let json = JSON.parse(data);
		php_expenses = json;
		reloadExpenses();
	}).always((data) => {
		$('#speseDT').data('metatable').hideProgress();
	});
}

function reloadExpenses() {
	// $('#expensesBody').empty();
	for (let i in php_expenses) {
		let expense = php_expenses[i];
		let $expense = $ghostTableRow.clone();
		if (expense.cod_category in php_categories && '$chip' in php_categories[expense.cod_category] && php_categories[expense.cod_category].$chip) {
			let $chip = php_categories[expense.cod_category].$chip.clone();
			$chip.removeClass('unselected');
			$expense.find('[col_name="category"]').append($chip);
		}
		$expense.find('[col_name="title"]').text(expense.title);
		let competence = new Date(expense.competence);
		$expense.find('[col_name="date"]').text(competence.getDate() + '/' +  String(competence.getMonth() + 1).padStart(2, '0') + '/' + competence.getFullYear());
		if (expense.f_v == '1') {
			// variabile
			let $mfv = $ghostMiniFabV.clone();
			$expense.find('[col_name="f_v"]').append($mfv);
		} else {
			// fisso
			let $mfv = $ghostMiniFabF.clone();
			$expense.find('[col_name="f_v"]').append($mfv);
		}
		$expense.find('[col_name="value"]').text(expense.value);

		$expense.find('.row_options').click(function() {
			// let callerMetrics = $(this).offset();
			// callerMetrics.width = $(this).outerWidth();
			// callerMetrics.height = $(this).outerHeight();
			// $expense.find('.mdc-menu').data('metamenu').setAbsolutePosition(0, 0);
			$expense.find('.mdc-menu').data('metamenu').setAnchorMargin({
				top: 36,
				left: 28,
			});
			$expense.find('.mdc-menu').data('metamenu').open = true;
		});

		$('#expensesBody').append($expense);
	}
}

function reloadCategories() {
	$('#categoriesContainer').empty();
	for (let i in php_categories) {
		let category = php_categories[i];
		let $category = $ghostCategory.clone();
		$category.attr('data-id_category', category.id_category);
		$category.addClass(category.color_whole);
		$category.find('.mdc-chip__icon').addClass(category.color_icon).text(category.icon);
		$category.find('.mdc-chip__text').text(category.title);
		$category.click(function() {
			$(this).closest('.mdc-chip-set').find('.expense-category-chip').addClass('unselected');
			$(this).removeClass('unselected');
			$('#titles_list').empty();
			for (let i in category.expense_title) {
				let title = document.createElement('option');
				title.value = category.expense_title[i];
				$('#titles_list').append(title);
			}
		});
		category.$chip = $category;
		$('#categoriesContainer').append($category);
	}
}

function validateNewExpense() {
	let valid = true;
	let newExpense = {
		title: $('#newExpenseTitle').val(),
		description: $('#newExpenseDescription').val(),
		competence: $('#classicCalendar').val(),
		f_v: $('#one').prop('checked') ? 1 : 0,
		cod_category: $('#categoriesContainer .expense-category-chip:not(.unselected)').data('id_category'),
		value: $('#newExpenseValue').val(),
	}

	if (!newExpense.title.length || !newExpense.competence || !newExpense.value.length) {
		valid = false;
	}

	$('#triggerNewExpense').prop('disabled', !valid);

	return valid ? newExpense : false;
}

// function createCalendar() {
// 	let today = new Date();
// 	let d = today.getDate();
// 	let m = today.getMonth(); // January is 0
// 	let yyyy = today.getFullYear();
// 	var dd = String(today.getDate()).padStart(2, '0');
// 	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
//
// 	today = yyyy + '/' + mm + '/' + dd;
//
// 	generateFullMonth(yyyy, m);
// 	generateFullMonth(m - 1 >= 0 ? yyyy :  yyyy - 1, m - 1 >= 0 ? m - 1 : 11);
// 	generateFullMonth(m + 1 < 12 ? yyyy : yyyy - 1, m + 1 < 12 ? m + 1 : 0);
//
// 	if ($('.day-cell[date="' + today + '"]').get(0)) {
// 		centerInView($('.day-cell[date="' + today + '"]').get(0));
// 	}
// }

// setInterval(() => {
// 	let $monthBox = false, tolleranza_laterale = 500;
// 	if (dayScroller.scrollLeft < tolleranza_laterale) {
// 		let $firstMonthEver = $('#dayScroller .month-box').first();
// 		if ($firstMonthEver.length) {
// 			let yyyymm = $firstMonthEver.attr('yyyymm').split('/');
// 			let yyyy = parseInt(yyyymm[0]);
// 			let mm = parseInt(yyyymm[1]);
// 			if (mm - 1 < 1) {
// 				yyyy--;
// 				mm = 12;
// 			} else {
// 				mm--;
// 			}
// 			$monthBox = generateFullMonth(yyyy, mm - 1);
// 			if ($monthBox && $monthBox.length) {
// 				monthBox = $monthBox.get(0);
// 				dayScroller.scrollLeft = dayScroller.scrollLeft + monthBox.offsetWidth;
// 			}
// 		} else {
// 			createCalendar();
// 		}
// 	}
// 	if (dayScroller.scrollLeft + dayScroller.offsetWidth > dayScroller.scrollWidth - tolleranza_laterale) {
// 		let $lastMonthEver = $('#dayScroller .month-box').last();
// 		if ($lastMonthEver.length) {
// 			let yyyymm = $lastMonthEver.attr('yyyymm').split('/');
// 			let yyyy = parseInt(yyyymm[0]);
// 			let mm = parseInt(yyyymm[1]);
// 			if (mm + 1 > 12) {
// 				yyyy++;
// 				mm = 1;
// 			} else {
// 				mm++;
// 			}
// 			$monthBox = generateFullMonth(yyyy, mm - 1);
// 			if ($monthBox && $monthBox.length) {
// 				monthBox = $monthBox.get(0);
// 			}
// 		} else {
// 			createCalendar();
// 		}
// 	}
// }, 300);

// function centerInView(dayCell) {
// 	if (typeof dayCell == 'string') {
// 		$dayCell = $('.day-cell[date="' + dayCell + '"]');
// 		if ($dayCell.length) {
// 			dayCell = $dayCell.get(0);
// 		} else {
// 			return;
// 		}
// 	}
// 	dayScroller.scrollTo(dayCell.offsetLeft - dayScroller.offsetWidth / 2 - 210, 0)
// }

// function generateFullMonth(yyyy, m) { // January is 0
// 	let mm = String(m + 1).padStart(2, '0');
// 	let prepended = false, canPrepend = true, $elemToPrepend = false, dayScroller_is_empty = true;
// 	$('#dayScroller .month-box').each(function(i, v) {
// 		dayScroller_is_empty = false;
// 		if (!prepended) { // just an optimizer
// 			if ($(v).attr('yyyymm') == String(yyyy) + '/' + mm) {
// 				canPrepend = false; // required!
// 			} else if ($(v).attr('yyyymm') > String(yyyy) + '/' + mm) {
// 				prepended = true;
// 				$elemToPrepend = $(v);
// 			}
// 		}
// 	});
// 	if (canPrepend) { // because the month requested may already be there
// 		let last_day_date = new Date(yyyy, m + 1, 0);
// 		let to_d = last_day_date.getDate();
// 		let cursor_d = 1;
// 		let $monthBox = $ghostMonthBox.clone();
// 		let month_label = 'Error';
// 		switch (m) {
// 			case 0: month_label = 'Gennaio'; break; case 1: month_label = 'Febbraio'; break; case 2: month_label = 'Marzo'; break;
// 			case 3: month_label = 'Aprile'; break; case 4: month_label = 'Maggio'; break; case 5: month_label = 'Giugno'; break;
// 			case 6: month_label = 'Luglio'; break; case 7: month_label = 'Agosto'; break; case 8: month_label = 'Settembre'; break;
// 			case 9: month_label = 'Ottobre'; break; case 10: month_label = 'Novembre'; break; case 11: month_label = 'Dicembre'; break;
// 			default: break;
// 		}
// 		$monthBox.find('.month-box__label').text(month_label + ' ' + yyyy);
// 		$monthBox.attr('yyyymm', String(yyyy) + '/' + mm);
// 		while (cursor_d <= to_d) {
// 			let cursor_dd = String(cursor_d).padStart(2, '0');
// 			let $dayCell = $ghostDayCell.clone();
// 			$dayCell.text(cursor_dd);
// 			$dayCell.attr('date', yyyy + '/' + mm + '/' + cursor_dd);
// 			$monthBox.find('.month-box__days-container').append($dayCell);
// 			cursor_d++;
// 		}
// 		if ($elemToPrepend) {
// 			$elemToPrepend.before($monthBox);
// 		} else {
// 			$('#dayScroller').append($monthBox);
// 		}
// 		return $monthBox;
// 	}
//
// 	return false;
// }
//
// $('#classicCalendarTrigger').click(() => {
// 	$('#classicCalendar').focus();
// });

let colorSchemas = {
	plain: {
		icon: '',
		whole: '',
	},
	original: {
		icon: '-800',
		whole: '-300',
	},
	reverse: {
		icon: '-300',
		whole: '-800',
	},
};

let colorPicked = 'orange', schemaPicked = 'original', iconPicked = 'bolt';

function colorApplier() {
	let ex_color = $('#newCategoryPreview .expense-category-chip').data('color');
	$('#newCategoryPreview .expense-category-chip').removeClass(ex_color);
	$('#newCategoryPreview .expense-category-chip')
	.addClass(colorPicked + colorSchemas[schemaPicked].whole)
	.data('color', colorPicked + colorSchemas[schemaPicked].whole);
	ex_color = $('#newCategoryPreview .mdc-chip__icon').data('color');
	$('#newCategoryPreview .mdc-chip__icon').removeClass(ex_color);
	$('#newCategoryPreview .mdc-chip__icon')
	.addClass(colorPicked + colorSchemas[schemaPicked].icon)
	.data('color', colorPicked + colorSchemas[schemaPicked].icon);
	$('#newCategoryPreview .mdc-chip__icon').text(iconPicked);
}

$('.color-sample').click(function() {
	colorPicked = $(this).data('color');
	colorApplier();
});

$('.schema-sample').click(function() {
	$('.schema-sample').removeClass('mdc-button--raised')
	$(this).addClass('mdc-button--raised')
	schemaPicked = $(this).data('schema');
	colorApplier();
});

$('#newCategoryTitle').on('input', function() {
	$('#newCategoryPreview .mdc-chip__text').text(this.value);
});

$('#availableIcons .icon-container').click(function() {
	$('#availableIcons .icon-container').addClass('unselected');
	$(this).removeClass('unselected');
	iconPicked = $(this).attr('data-icon_name');
	colorApplier();
});

$('#newExpenseTitle, #classicCalendar, #newExpenseValue').on('input change', validateNewExpense);

/* Bottom Line functions */
reloadCategories();
reloadExpenses();
colorApplier();
