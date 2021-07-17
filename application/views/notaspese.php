<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Nota Spese', ['notaspese', 'big-switch']) ?>
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
							<div class="mdc-chip-set" role="grid">
								<div class="mdc-chip expense-category-chip red-300" role="row">
									<div class="mdc-chip__ripple"></div>
									<i class="material-icons mdc-chip__icon mdc-chip__icon--leading red-800">bolt</i>
									<span role="gridcell">
										<span role="button" tabindex="0" class="mdc-chip__primary-action">
											<span class="mdc-chip__text">Bollette</span>
										</span>
									</span>
								</div>
								<div class="mdc-chip expense-category-chip blue-300" role="row">
									<div class="mdc-chip__ripple"></div>
									<i class="material-icons mdc-chip__icon mdc-chip__icon--leading blue-800">home</i>
									<span role="gridcell">
										<span role="button" tabindex="0" class="mdc-chip__primary-action">
											<span class="mdc-chip__text">Affitto</span>
										</span>
									</span>
								</div>
							</div>
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
					<div class="d-flex" style="margin-top: 22px;">
						<div class="mr-auto">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label" id="my-label-id">Importo</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id">
							</label>
						</div>
						<div class="ml-auto">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label" id="my-label-id">Importo</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id">
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
										<span class="mdc-floating-label" id="my-label-id">Descrizione</span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<span class="mdc-text-field__resizer">
									<textarea class="mdc-text-field__input" rows="4" cols="80" aria-label="Label"></textarea>
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
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="discard">
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
			<div class="mdc-dialog__surface" role="alertdialog" style="width: auto; max-width: 710px;">
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
									<span class="mdc-floating-label" id="my-label-id">Titolo</span>
								</span>
								<span class="mdc-notched-outline__trailing"></span>
							</span>
							<input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id">
						</label>
					</div>
					<div style="margin-top: 16px;">
						<b class="section-label">Schema di colori</b>
						<div class="flex-wrap">
							<div class="schema-sample" data-schema="original">Original</div>
							<div class="schema-sample" data-schema="reverse">Reverse</div>
							<div class="schema-sample" data-schema="plain">Plain</div>
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
						<b class="section-label">Scegli un colore</b>
						<div class="flex-wrap">
							<div class="mdc-fab mdc-fab--mini grey-900">
								<span class="mdc-fab__icon material-icons">add</span>
							</div>
							<div class="mdc-fab mdc-fab--mini grey-900">
								<span class="mdc-fab__icon material-icons">add</span>
							</div>
							<div class="mdc-fab mdc-fab--mini grey-900">
								<span class="mdc-fab__icon material-icons">add</span>
							</div>
							<div class="mdc-fab mdc-fab--mini grey-900">
								<span class="mdc-fab__icon material-icons">add</span>
							</div>
							<div class="mdc-fab mdc-fab--mini grey-900">
								<span class="mdc-fab__icon material-icons">add</span>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="discard">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>


	<?= import_js('notaspese', 'big-switch') ?>
</body>
</html>
