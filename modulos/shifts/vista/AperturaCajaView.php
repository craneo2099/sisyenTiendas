
<form action="<?=htmlspecialchars($_SERVER['REDIRECT_URL'], ENT_QUOTES, 'UTF-8') ?>" method="post">
	<input type="hidden" name="FormID" value="<?= $_SESSION['FormID'] ?>" />
	<input type="hidden" name="TerminalId" value="<?= $terminal ?>" />
	<input type="hidden" name="hidAction" value="Abrir" />

	<div class="scroller"><div class="holder">
		<div class="d-flex flex-row-reverse">
		  <div class="p-2">
		  	<div class="form-group">
    			<label for="txtMonto"><?=_('Monto de apertura')?></label>
				<input type="text" class="form-control form-control-sm campo requerido" id="txtMonto" name="txtMonto" autofocus placeholder="0.00">
			</div>
			<div class="form-group">
				<label for="txtNota"><?=_('Nota')?>:</label>
			    <textarea class="form-control" id="txtNota" rows="3"></textarea>
			</div>
		  	<input class="btn btn-primary" type="submit" name="btnAbrir" value="<?=_('Abrir turno')?>">
		  </div>
		</div>
	</div></div>
</form>