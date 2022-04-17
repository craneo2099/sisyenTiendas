
<form action="<?=htmlspecialchars($_SERVER['REDIRECT_URL'], ENT_QUOTES, 'UTF-8') ?>" method="post">
	<input type="hidden" name="FormID" value="<?= $_SESSION['FormID'] ?>" />
	<input type="hidden" name="hidAction" value="Procesar" />

	<div class="scroller"><div class="holder">
		<div class="d-flex flex-row">
			<div class="p-2">
				<table class="table">
					<thead>
						<tr>
						<th scope="col"><?=_('Name')?></th>
						<th scope="col"><?=_('Caja')?></th>
						<th scope="col"><?=_('Sistema')?></th>
						<th scope="col"><?=_('Validación')?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<th scope="row"><?=_('Efectivo')?></th>
						<td><?=$efectivoIn?></td>
						<td><?=$efectivoSys?></td>
						<td><?=$efectivoOk?></td>
						</tr>
						<tr>
						<th scope="row"><?=_('Tarjeta')?></th>
						<td><?=$tarjetaIn?></td>
						<td><?=$tarjetaSys?></td>
						<td><?=$tarjetaOk?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
			<h3><?=_('Desglose de Efectivo')?></h3>

		<div class="d-flex flex-row">
			<div class="p-2">
			<h4><?=_('Billetes')?></h4>
			<?php

			foreach ($billetes as $key => $value) {
			?>
				<div class="form-group row">
	      			<label for="num<?=$key?>" class="col-sm-2 col-form-label">$<?=$key?></label>
	      			<div class="col-sm-10">
						<input type="text" class="form-control form-control-sm campo" id="num<?=$key?>" name="num<?=$key?>" autofocus="" placeholder="0" value="<?=$value?>">
					</div>
				</div>
			<?php
			}
			?>
			</div>
			<div class="p-2">
			<h4><?=_('Monedas')?></h4>
			<?php
			foreach ($monedas as $key => $value) {
			?>
				<div class="form-group row">
	      			<label for="num<?=$key?>" class="col-sm-2 col-form-label">$<?=$key?></label>
	      			<div class="col-sm-10">
						<input type="text" class="form-control form-control-sm campo" id="num<?=$key?>" name="num<?=$key?>" autofocus="" placeholder="0" value="<?=$value?>">
					</div>
				</div>
			<?php
			}

			?>
			</div>
		</div>

		<div class="d-flex flex-row">
		  <div class="p-2">
		  	<div class="form-group">
		  		<input class="btn btn-primary" type="submit" name="btnCerrar" value="<?=_('Procesar corte')?>">
			</div>
		  </div>
		</div>
	</div></div>
</form>