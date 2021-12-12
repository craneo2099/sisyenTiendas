<?php
// Display demo user name and password within login form if $AllowDemoMode is true

if ((isset($AllowDemoMode)) AND ($AllowDemoMode == True) AND (!isset($demo_text))) {
	$demo_text = _('Login as user') .': <i>' . _('admin') . '</i><br />' ._('with password') . ': <i>' . _('weberp') . '</i>' .
		'<br /><a href="../">' . _('Return') . '</a>';// This line is to add a return link.
} elseif (!isset($demo_text)) {
	$demo_text = '';
}
require_once $PathPrefix."includes/devstar/novatech.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>deno erp</title><?php /*<?=$brand." ".$Labels['login_title']?> */?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?=$RootPath?>/css/<?php echo $Theme;?>/login.css" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<?=addcssList(
	"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"
)?>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?=addScriptList(
	"https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
)?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<div id="container" class="container">
	<div id="login_logo"></div>
	<div id="login_box">
	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');?>" method="post">
	<input type="hidden" name="FormID" value="<?=$_SESSION['FormID']; ?>" />
	<script type="text/javascript">
	$( document ).ready(function() {
   	$('.box-carousel').slick({
		dots: false, 
		arrows: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		prevArrow: "<button type='button' class='mission-prev-arrow'></button>",
		nextArrow: "<button type='button' class='mission-next-arrow'></button>",
		variableWidth:true
	});

});
</script>
<?php

	    if (isset($CompanyList) AND is_array($CompanyList)) {
            foreach ($CompanyList as $key => $CompanyEntry){
                if ($DefaultDatabase == $CompanyEntry['database']) {
                    $CompanyNameField = "$key";
                    $DefaultCompany = $CompanyEntry['company'];
                }
            }
	        if ($AllowCompanySelectionBox === 'Hide'){
			    // do not show input or selection box
			    echo '<input type="hidden" name="CompanyNameField"  value="' .  $CompanyNameField . '" />';
		    } elseif ($AllowCompanySelectionBox === 'ShowInputBox'){
			    // show input box
			    echo _('Company') .': <br />' .  '<input type="text" name="DefaultCompany"  autofocus="autofocus" required="required" value="' .  htmlspecialchars($DefaultCompany ,ENT_QUOTES,'UTF-8') . '" disabled="disabled"/>';//use disabled input for display consistency
		        echo '<input type="hidden" name="CompanyNameField"  value="' .  $CompanyNameField . '" />';
		    } elseif ($AllowCompanySelectionBox === 'ShowSelectionBox') {
                // Show selection box ($AllowCompanySelectionBox == 'ShowSelectionBox')
                echo _('Company') . ':<br />';
                echo '<select name="CompanyNameField">';
                foreach ($CompanyList as $key => $CompanyEntry){
                    if (is_dir('companies/' . $CompanyEntry['database']) ){
                        if ($CompanyEntry['database'] == $DefaultDatabase) {
                            echo '<option selected="selected" label="'.htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8').'" value="'.$key.'">' . htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8') . '</option>';
                        } else {
                            echo '<option label="'.htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8').'" value="'.$key.'">' . htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8') . '</option>';
                        }
                    }
                }
                echo '</select>';
            } else {
                // Show carousel ($AllowCompanySelectionBox == 'ShowCarousel')
                
?>

				<div class="row">
					<div class="col-sm-12">
						<p class="font-weight-bold text-uppercase text-left my-3"><?=_('Company')?></p>
					</div>
				</div>
				
				
				<div class="row">
					<input type="hidden" name="CompanyNameField" id="CompanyNameField" value="<?=$CompanyNameField?>" />
					<div class="col-sm-12">
						<div class="carousel box-carousel d-none d-sm-block">
				<?php
				foreach ($CompanyList as $key => $CompanyEntry){
					$companyLabel=htmlspecialchars($CompanyEntry['company'],ENT_QUOTES,'UTF-8');
					?>
							<div class="box">
								<div class="company" onClick="$('#CompanyNameField').value=<?=$key?>">
								<a href="#"><i class="fa fa-3x fa-cloud" aria-hidden="true"></i><br><?=$companyLabel?></a>
								</div>
							</div>
<?php
                
				}
				?>
				
						</div><!-- carousel-->
					</div><!--col-->
				</div><!--row-->
				<?php
			}
	    }
	
?>
				<div class="row">
					<div class="col-sm-12">
						<p class="font-weight-bold text-uppercase text-left my-3"><?=_('User name')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<input type="text" name="UserNameEntryField" class="form-control" required="required" autofocus="autofocus" maxlength="20" placeholder="<?php echo _('User name'); ?>" />
						
					</div><!--col-->
				</div><!--row-->
				<div class="row">
					<div class="col-sm-12">
						<p class="font-weight-bold text-uppercase text-left my-3"><?=_('Password')?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<input type="password" class="form-control" required="required" name="Password" placeholder="<?php echo _('Password'); ?>" /><br />
						
					</div><!--col-->
				</div><!--row-->
	
				<div class="row">
					<div class="col-sm-12">
					<input class="btn btn-primary" type="submit" value="<?= _('Login'); ?>" name="SubmitUser" />
					
					</div><!--col-->
				</div><!--row-->
				<?php if(isset($UsrMessage)){?>
				<div class="row mt-2">
					<div class="col-sm-12 alert alert-danger" role="alert">
						<?=$UsrMessage?>
					</div><!--col-->
				</div><!--row-->
				<?php }?>
	</form>
	</div>
	<br />
</div>
</body>
</html>