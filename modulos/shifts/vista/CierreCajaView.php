
<form action="<?=htmlspecialchars($_SERVER['REDIRECT_URL'], ENT_QUOTES, 'UTF-8') ?>" method="post">
	<input type="hidden" name="FormID" value="<?= $_SESSION['FormID'] ?>" />
	<input type="hidden" name="TerminalId" value="<?= $terminal ?>" />
	<input type="hidden" name="hidAction" value="Cotejar" />

	<div class="scroller"><div class="holder">
		<div class="d-flex flex-row">
		  <div class="p-2">
		  	<div class="form-group">
				<label for="txtEfe"><?=_('Efectivo')?></label>
				<input type="text" class="form-control form-control-sm campo requerido" id="txtEfe" name="txtEfe" autofocus placeholder="0.00">
			</div>
		  	<div class="form-group">
				<label for="txtTdc"><?=_('Tarjeta')?></label>
				<input type="text" class="form-control form-control-sm campo requerido" id="txtTdc" name="txtTdc" autofocus placeholder="0.00">
			</div>
		  </div>
		</div>

		<div class="d-flex flex-row">
		  <div class="p-2">
		  	<div class="form-group">
		  		<input class="btn btn-primary" type="submit" name="btnCerrar" value="<?=_('Cerrar Caja')?>">
			</div>
		  </div>
		</div>
	</div></div>
</form>