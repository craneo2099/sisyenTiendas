
<form action="<?=htmlspecialchars($_SERVER['REDIRECT_URL'], ENT_QUOTES, 'UTF-8') ?>" method="post">
	<input type="hidden" name="FormID" value="<?= $_SESSION['FormID'] ?>" />

	<div class="scroller"><div class="holder">
		<div class="d-flex flex-row-reverse">
		  <div class="p-2">
		  	<?php
		  	if($Abierto){
		  	?>
		  	<input type="hidden" name="hidAction" value="Cierre">
		  	<input class="btn btn-primary" type="submit" value="<?=_('Cierre')?>">
		  	<?php
		  	}else{
		  	?>
		  	<input type="hidden" name="hidAction" value="Apertura">
		  	<input class="btn btn-primary" type="submit" value="<?=_('Apertura')?>">
		  	<?php
		  	}
		  	?>
		  </div>
		</div>
	</div></div>
</form>