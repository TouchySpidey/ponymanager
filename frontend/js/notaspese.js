let $ghostDayCell = $('#ghostDayCell').remove();
let $ghostMonthBox = $('#ghostMonthBox').remove();
let dayScroller = $('#dayScroller').get(0);
$(function() {
	promptNewCost();
	promptNewCategory();
})

function promptNewCost() {
	$('#addCostDialog').data('metadialog').open();
}

function promptNewCategory() {
	$('#newCategoryDialog').data('metadialog').open();
}

function createCalendar() {
	let today = new Date();
	let d = today.getDate();
	let m = today.getMonth(); // January is 0
	let yyyy = today.getFullYear();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!

	today = yyyy + '/' + mm + '/' + dd;

	generateFullMonth(yyyy, m);
	generateFullMonth(m - 1 >= 0 ? yyyy :  yyyy - 1, m - 1 >= 0 ? m - 1 : 11);
	generateFullMonth(m + 1 < 12 ? yyyy : yyyy - 1, m + 1 < 12 ? m + 1 : 0);

	if ($('.day-cell[date="' + today + '"]').get(0)) {
		centerInView($('.day-cell[date="' + today + '"]').get(0));
	}
}

setInterval(() => {
	let $monthBox = false, tolleranza_laterale = 500;
	if (dayScroller.scrollLeft < tolleranza_laterale) {
		let $firstMonthEver = $('#dayScroller .month-box').first();
		if ($firstMonthEver.length) {
			let yyyymm = $firstMonthEver.attr('yyyymm').split('/');
			let yyyy = parseInt(yyyymm[0]);
			let mm = parseInt(yyyymm[1]);
			if (mm - 1 < 1) {
				yyyy--;
				mm = 12;
			} else {
				mm--;
			}
			$monthBox = generateFullMonth(yyyy, mm - 1);
			if ($monthBox && $monthBox.length) {
				monthBox = $monthBox.get(0);
				dayScroller.scrollLeft = dayScroller.scrollLeft + monthBox.offsetWidth;
			}
		} else {
			createCalendar();
		}
	}
	if (dayScroller.scrollLeft + dayScroller.offsetWidth > dayScroller.scrollWidth - tolleranza_laterale) {
		let $lastMonthEver = $('#dayScroller .month-box').last();
		if ($lastMonthEver.length) {
			let yyyymm = $lastMonthEver.attr('yyyymm').split('/');
			let yyyy = parseInt(yyyymm[0]);
			let mm = parseInt(yyyymm[1]);
			if (mm + 1 > 12) {
				yyyy++;
				mm = 1;
			} else {
				mm++;
			}
			$monthBox = generateFullMonth(yyyy, mm - 1);
			if ($monthBox && $monthBox.length) {
				monthBox = $monthBox.get(0);
			}
		} else {
			createCalendar();
		}
	}
}, 300);

function centerInView(dayCell) {
	if (typeof dayCell == 'string') {
		$dayCell = $('.day-cell[date="' + dayCell + '"]');
		if ($dayCell.length) {
			dayCell = $dayCell.get(0);
		} else {
			return;
		}
	}
	dayScroller.scrollTo(dayCell.offsetLeft - dayScroller.offsetWidth / 2 - 210, 0)
}

function generateFullMonth(yyyy, m) { // January is 0
	let mm = String(m + 1).padStart(2, '0');
	let prepended = false, canPrepend = true, $elemToPrepend = false, dayScroller_is_empty = true;
	$('#dayScroller .month-box').each(function(i, v) {
		dayScroller_is_empty = false;
		if (!prepended) { // just an optimizer
			if ($(v).attr('yyyymm') == String(yyyy) + '/' + mm) {
				canPrepend = false; // required!
			} else if ($(v).attr('yyyymm') > String(yyyy) + '/' + mm) {
				prepended = true;
				$elemToPrepend = $(v);
			}
		}
	});
	if (canPrepend) { // because the month requested may already be there
		let last_day_date = new Date(yyyy, m + 1, 0);
		let to_d = last_day_date.getDate();
		let cursor_d = 1;
		let $monthBox = $ghostMonthBox.clone();
		let month_label = 'Error';
		switch (m) {
			case 0: month_label = 'Gennaio'; break; case 1: month_label = 'Febbraio'; break; case 2: month_label = 'Marzo'; break;
			case 3: month_label = 'Aprile'; break; case 4: month_label = 'Maggio'; break; case 5: month_label = 'Giugno'; break;
			case 6: month_label = 'Luglio'; break; case 7: month_label = 'Agosto'; break; case 8: month_label = 'Settembre'; break;
			case 9: month_label = 'Ottobre'; break; case 10: month_label = 'Novembre'; break; case 11: month_label = 'Dicembre'; break;
			default: break;
		}
		$monthBox.find('.month-box__label').text(month_label + ' ' + yyyy);
		$monthBox.attr('yyyymm', String(yyyy) + '/' + mm);
		while (cursor_d <= to_d) {
			let cursor_dd = String(cursor_d).padStart(2, '0');
			let $dayCell = $ghostDayCell.clone();
			$dayCell.text(cursor_dd);
			$dayCell.attr('date', yyyy + '/' + mm + '/' + cursor_dd);
			$monthBox.find('.month-box__days-container').append($dayCell);
			cursor_d++;
		}
		if ($elemToPrepend) {
			$elemToPrepend.before($monthBox);
		} else {
			$('#dayScroller').append($monthBox);
		}
		return $monthBox;
	}

	return false;
}
$('#classicCalendarTrigger').click(() => {
	$('#classicCalendar').focus();
});
$('.mdc-chip-set .expense-category-chip').click(function() {
	$(this).closest('.mdc-chip-set').find('.expense-category-chip').addClass('unselected');
	$(this).removeClass('unselected');
});
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

let colorPicked = 'orange', schemaPicked = 'original';

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
}

$('.color-sample').click(function() {
	colorPicked = $(this).data('color');
	colorApplier();
});

$('.schema-sample').click(function() {
	schemaPicked = $(this).data('schema');
	console.log(schemaPicked);
	colorApplier();
});
