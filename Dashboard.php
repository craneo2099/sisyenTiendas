<?php
/* Display outstanding debtors, creditors, etc */

include('includes/session.php');
include('includes/devstar/dashboard/dashboard.inc');
include_once('api/api_salesman.php');
$Title = _('Dashboard');
$ViewTopic = 'GeneralLedger';// RChacon: You should be in this topic ?
$BookMark = 'Dashboard';
include('includes/header.php');



?>
<p class="page_title_text"><img alt="" src="<?=$RootPath?>/css/<?=$Theme?>/images/gl.png"
	title="<?=$Title?>" />
	<?=$Title?></p>

<?php
if(in_array($_SESSION['PageSecurityArray']['InventoryQuantities.php'], $_SESSION['AllowedPageSecurityTokens'])) {
	$preview=true;
	$escasos=getEscasos($preview);
	?>
	<div id="blkEscasos">
		<table>
			<caption><?=_('Items to sell out')?></caption>
			<thead><tr>
					<th><?=_('Item Code')?></th>
					<th><?=_('Name')?></th>
					<th><?=_('Category')?></th>
					<th><?=_('Supplier')?></th>
					<th><?=_('Quantity')?></th>
					<th><?=_('Date')?></th>
			</tr></thead>
			<tbody>
				<?php
				foreach($escasos as $escaso){
					?>
					<tr>
						<td><?=$escaso['id']?></td>
						<td><?=$escaso['nombre']?></td>
						<td><?=$escaso['categoria']?></td>
						<td><?=$escaso['proveedor']?></td>
						<td><?=$escaso['cantidad']?></td>
						<td><?=$escaso['fecha']?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"><a href="<?= $RootPath?>/InventoryQuantities.php"><?=_('See More')?> >></a></td>
				</tr>
			</tfoot>
		</table>
		
	</div>
	<?php 
}


if(in_array($_SESSION['PageSecurityArray']['SalesTopItemsInquiry.php'], $_SESSION['AllowedPageSecurityTokens'])) {

	$preview=true;
	$populares=getPopulares($preview);
	?>
	<div id="blkEscasos">
		<table>
			<caption><?=_('Top selling items')?></caption>
			<thead><tr>
					<th><?=_('Item Code')?></th>
					<th><?=_('Name')?></th>
					<th><?=_('Category')?></th>
					<th><?=_('Supplier')?></th>
					<th><?=_('Quantity')?></th>
					<th><?=_('Date')?></th>
			</tr></thead>
			<tbody>
				<?php
				foreach($populares as $popular){
				?>
				<tr>
					<td><?=$popular['id']?></td>
					<td><?=$popular['nombre']?></td>
					<td><?=$popular['categoria']?></td>
					<td><?=$popular['proveedor']??'--'?></td>
					<td><?=$popular['cantidad']?></td>
					<td><?=$popular['fecha']?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"><a href="<?= $RootPath?>/SalesTopItemsInquiry.php"><?=_('See More')?> >></a></td>
				</tr>
			</tfoot>
		</table>
		
	</div>

	<?php 
}
include('includes/footer.php');
?>
