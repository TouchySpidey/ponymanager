<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Nota Spese', ['notaspese', 'big-switch']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="general-heading">Le tue spese</div>
		<div class="d-flex">
			<div class="ml-auto mr-auto">
				<div class="mdc-data-table" id="speseDT">
					<div class="mdc-data-table__table-container">
						<table class="mdc-data-table__table" aria-label="Dessert calories">
							<thead>
								<tr class="mdc-data-table__header-row">
									<th class="options_cell mdc-data-table__header-cell" role="columnheader" scope="col"><div class="material-icons mdc-icon-button" onclick="reloadExpensesFromDB()">refresh</div></th>
									<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Categoria</th>
									<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Data</th>
									<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Titolo</th>
									<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">Fisso / Variabile</th>
									<th class="mdc-data-table__header-cell mdc-data-table__header-cell--numeric" role="columnheader" scope="col">Importo</th>
								</tr>
							</thead>
							<tbody class="mdc-data-table__content" id="expensesBody">
								<tr class="mdc-data-table__row" id="ghostTableRow">
									<td class="options_cell mdc-data-table__cell mdc-menu-surface--anchor" col_name="option" scope="row">
										<div class="material-icons mdc-icon-button row_options">more_vert</div>
										<div class="mdc-menu mdc-menu-surface">
											<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
												<li class="mdc-list-item" role="menuitem">
													<span class="mdc-list-item__graphic material-icons">edit</span>
													<span class="mdc-list-item__text">This will prompt the edit</span>
												</li>
												<li class="mdc-list-item" role="menuitem">
													<span class="mdc-list-item__graphic material-icons">delete</span>
													<span class="mdc-list-item__text">This will delete the row</span>
												</li>
											</ul>
										</div>
									</td>
									<td class="mdc-data-table__cell" col_name="category" scope="row"></td>
									<td class="mdc-data-table__cell" col_name="date">Data</td>
									<td class="mdc-data-table__cell" col_name="title">Titolo</td>
									<td class="mdc-data-table__cell text-center" col_name="f_v">
										<div id="ghostMiniFabF" class="mdc-fab mdc-fab--micro deep-orange mdc-fab--extended">
											<div class="mdc-fab__label">Fisso</div>
										</div>
										<div id="ghostMiniFabV" class="mdc-fab mdc-fab--micro teal mdc-fab--extended">
											<div class="mdc-fab__label">Variabile</div>
										</div>
									</td>
									<td class="mdc-data-table__cell mdc-data-table__cell--numeric" col_name="value">Importo</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="mdc-data-table__progress-indicator">
						<div class="mdc-data-table__scrim"></div>
						<div class="mdc-linear-progress mdc-linear-progress--indeterminate mdc-data-table__linear-progress" role="progressbar" aria-label="Data is being loaded...">
							<div class="mdc-linear-progress__buffer">
								<div class="mdc-linear-progress__buffer-bar"></div>
								<div class="mdc-linear-progress__buffer-dots"></div>
							</div>
							<div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar">
								<span class="mdc-linear-progress__bar-inner"></span>
							</div>
							<div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar">
								<span class="mdc-linear-progress__bar-inner"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button onclick="promptNewCost()" class="fab blue mdc-fab">
			<i class="material-icons mdc-fab__icon">add</i>
		</button>
	</div>

	<!-- Elementi che poppano sopra al DOM -->

	<div class="mdc-dialog" id="addCostDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog" style="width: auto; max-width: 710px;">
				<div class="mdc-dialog__content">
					<div class="d-flex">
						<div id="classicCalendarTrigger" class="green-400">
							<i class="mdi mdi-calendar"></i>
						</div>
						<input type="date" id="classicCalendar" />
						<div id="dayScroller" class="d-flex">
							<div class="month-box" id="ghostMonthBox">
								<div class="d-flex">
									<div class="month-box__label text-center mr-auto" style="padding-left: 24px;">Luglio</div>
									<div class="month-box__label text-center mr-auto ml-auto">Luglio</div>
									<div class="month-box__label text-center ml-auto" style="padding-right: 24px;">Luglio</div>
								</div>
								<div class="month-box__days-container" style="display: flex;">
									<div class="day-cell" id="ghostDayCell">01</div>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex">
						<div class="d-flex flex-1">
							<div class="mdc-chip-set" id="categoriesContainer" role="grid">
								<div id="ghostCategory" class="mdc-chip expense-category-chip unselected" role="row" data-id_category="">
									<div class="mdc-chip__ripple"></div>
									<i class="material-icons mdc-chip__icon mdc-chip__icon--leading "></i>
									<span role="gridcell">
										<span role="button" tabindex="0" class="mdc-chip__primary-action">
											<span class="mdc-chip__text"></span>
										</span>
									</span>
								</div>
							</div>
						</div>
						<div class="expense-category ml-auto">
							<div class="mdc-fab mdc-fab--mini" onclick="promptNewCategory()">
								<div class="mdc-fab__ripple"></div>
								<div class="mdc-fab__icon material-icons">add</div>
							</div>
						</div>
					</div>
					<div class="d-flex" style="margin-top: 22px;">
						<div class="mr-auto">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label" id="newExpenseTitleLabel">Titolo</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input list="titles_list" id="newExpenseTitle" required type="text" class="mdc-text-field__input" aria-labelledby="newExpenseTitleLabel">
								<datalist id="titles_list"></datalist>
							</label>
						</div>
						<div class="ml-auto">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label" id="newExpenseValueLabel">Importo</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input id="newExpenseValue" required type="text" class="mdc-text-field__input" aria-labelledby="newExpenseValueLabel">
							</label>
						</div>
					</div>
					<div class="d-flex big-switch" style="margin: 22px auto;">
						<div class="service-option active ml-auto leftyOption">
							Fisso
						</div>
						<div class="cp-switch mt-auto mb-auto">
							<input id="one" class="middle-switch cp-input" type="checkbox">
							<label for="one" class="cp-slider"></label>
						</div>
						<div class="service-option mr-auto rightyOption">
							Variabile
						</div>
					</div>
					<div class="d-flex">
						<div class="ml-auto mr-auto">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea mdc-text-field--no-label">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label" id="newExpenseDescriptionLabel">Descrizione</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<span class="mdc-text-field__resizer">
									<textarea id="newExpenseDescription" class="mdc-text-field__input" rows="4" cols="80" aria-labelledby="newExpenseDescriptionLabel"></textarea>
								</span>
							</label>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button id="triggerNewExpense" type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveExpense" disabled>
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div class="mdc-dialog" id="newCategoryDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog" style="width: auto; max-width: 668px;">
				<div class="mdc-dialog__content">
					<div id="newCategoryPreview">
						<div class="d-flex">
							<div class="ml-auto mr-auto mdc-chip expense-category-chip amber-300" data-color="amber-300" role="row">
								<div class="mdc-chip__ripple"></div>
								<i class="material-icons mdc-chip__icon mdc-chip__icon--leading amber-800" data-color="amber-800">bolt</i>
								<span role="gridcell">
									<span role="button" tabindex="0" class="mdc-chip__primary-action">
										<span class="mdc-chip__text">Bollette</span>
									</span>
								</span>
							</div>
						</div>
					</div>
					<div style="margin-top: 16px;">
						<label class="mdc-text-field mdc-text-field--outlined">
							<span class="mdc-notched-outline">
								<span class="mdc-notched-outline__leading"></span>
								<span class="mdc-notched-outline__notch">
									<span class="mdc-floating-label">Titolo</span>
								</span>
								<span class="mdc-notched-outline__trailing"></span>
							</span>
							<input type="text" class="mdc-text-field__input" id="newCategoryTitle" value="Bollette" />
						</label>
					</div>
					<div style="margin-top: 16px;">
						<b class="section-label">Schema di colori</b>
						<div class="flex-wrap">
							<div class="mdc-button schema-sample mdc-ripple-surface" data-schema="original">
								<span class="mdc-button__label">Original</span>
							</div>
							<div class="mdc-button schema-sample mdc-ripple-surface" data-schema="reverse">
								<span class="mdc-button__label">Reverse</span>
							</div>
							<div class="mdc-button schema-sample mdc-ripple-surface" data-schema="plain">
								<span class="mdc-button__label">Plain</span>
							</div>
						</div>
						<div class="flex-wrap">
							<div data-color="red" class="red color-sample"></div>
							<div data-color="pink" class="pink color-sample"></div>
							<div data-color="glos-pink" class="glos-pink color-sample"></div>
							<div data-color="purple" class="purple color-sample"></div>
							<div data-color="deep-purple" class="deep-purple color-sample"></div>
							<div data-color="indigo" class="indigo color-sample"></div>
							<div data-color="blue" class="blue color-sample"></div>
							<div data-color="light-blue" class="light-blue color-sample"></div>
							<div data-color="cyan" class="cyan color-sample"></div>
							<div data-color="teal" class="teal color-sample"></div>
							<div data-color="green" class="green color-sample"></div>
							<div data-color="light-green" class="light-green color-sample"></div>
							<div data-color="lime" class="lime color-sample"></div>
							<div data-color="yellow" class="yellow color-sample"></div>
							<div data-color="amber" class="amber color-sample"></div>
							<div data-color="orange" class="orange color-sample"></div>
							<div data-color="deep-orange" class="deep-orange color-sample"></div>
							<div data-color="brown" class="brown color-sample"></div>
							<div data-color="blue-grey" class="blue-grey color-sample"></div>
							<div data-color="grey" class="grey color-sample"></div>
						</div>
					</div>
					<div>
						<b class="section-label">Scegli un'icona</b>
						<div class="flex-wrap" id="availableIcons">
							<?php foreach ($availableIcons as $icon) { ?>
								<div class="mdc-fab mdc-fab--mini grey-900 icon-container unselected" data-icon_name="<?= $icon ?>">
									<span class="mdc-fab__icon material-icons"><?= $icon ?></span>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveCategory">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<script>let php_categories = JSON.parse(`<?= JSON_encode($categories) ?>`)</script>
	<script>let php_expenses = JSON.parse(`<?= JSON_encode($costs) ?>`)</script>
	<?= import_js('notaspese', 'big-switch') ?>
</body>
</html>
