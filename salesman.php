<?php include_once('include/comman_session.php');
$table = "salesman";
include("include/header.php");

if(isset($_REQUEST['aid'])){
	$id=$_REQUEST['aid'];
}else{
	$id=0;
}
$select = $db->getRow("SELECT * FROM ".$table." where `salesman_id`=?",array($id));
if($select){
	$type = "edit";
}else{
	$type = "add";
}
?>
<div class="main-container">
	<div class="navbar-content">
		<!-- start: SIDEBAR -->
		<div class="main-navigation navbar-collapse collapse">
			<!-- start: MAIN MENU TOGGLER BUTTON -->
			<div class="navigation-toggler">
				<i class="clip-chevron-left"></i>
				<i class="clip-chevron-right"></i>
			</div>
			<!-- end: MAIN MENU TOGGLER BUTTON -->
			<?php include("include/sidebar.php");?>
			
			
		</div>
		<!-- end: SIDEBAR -->
	</div>
	<!-- start: PAGE -->
	<div class="main-content">
		<!-- start: PANEL CONFIGURATION MODAL FORM -->
		<div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title">Panel Configuration</h4>
					</div>
					<div class="modal-body">
						Here will be a configuration form
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
						<button type="button" class="btn btn-primary">
							Save changes
						</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<!-- end: SPANEL CONFIGURATION MODAL FORM -->
		<div class="container">
			<!-- start: PAGE HEADER -->
			<div class="row">
				<div class="col-sm-12">
					<!-- start: PAGE TITLE & BREADCRUMB -->
					<ol class="breadcrumb">
						<li>
							<i class="clip-pencil"></i>
							<a href="#">
								Home
							</a>
						</li>
						<li class="active">
							Sales Man
						</li>
					</ol>
					<div class="page-header">
						<h1>Sales Man Manage </h1>
					</div>
					<div id="nestable-menu" class="pull-right">
						<a href="salesman_list.php" class="btn btn-primary btn-squared">Sales Man List</a>
					</div>
					<!-- end: PAGE TITLE & BREADCRUMB -->
				</div>
			</div>
			<!-- end: PAGE HEADER -->
			<!-- start: PAGE CONTENT -->
			<div class="row">
				<div class="col-sm-12">
					<!-- start: TEXT FIELDS PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-external-link-square"></i>
							Sales Man <?php echo $type;?>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link" href=""><i class="fa fa-refresh"></i></a>
								<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
							</div>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="model/salesmanManage.php" id="salesman-add-frm" name="salesman-add-frm" enctype="multipart/form-data">
								<?php if(isset($_SESSION['admin_success'])){?>
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert">
										X
									</button>
									<i class="fa fa-check-circle"></i>
									<strong>Well done!</strong> <?php echo $_SESSION['admin_success'];?>
								</div>
								<?php 
									unset($_SESSION['admin_success']);
									}
								?>
								<?php if(isset($_SESSION['admin_error'])){?>
									<div class="alert alert-danger">
										<button class="close" data-dismiss="alert">
											X
										</button>
										<i class="fa fa-times-circle"></i>
										<strong>Oh snap!</strong> <?php echo $_SESSION['admin_error'];?>
									</div>
								<?php 
									unset($_SESSION['admin_error']);
									}
								?>
								<input type="hidden" value="<?php echo $id;?>" name="id">
								<input type="hidden" value="<?php echo $type;?>" name="type">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="form-field-1">
										Sales Man Name
									</label>
									<div class="col-sm-7">
										<input type="text" placeholder="Sales Man Name..." id="name" class="form-control" name="name" value="<?php echo !empty($select['name']) ? $select['name'] : '' ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="form-field-1">
										Sales Man Mobile
									</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" placeholder="Sales Man Mobile..." id="mobile" class="form-control" name="mobile" value="<?php echo !empty($select['mobile']) ? $select['mobile'] : '' ?>">
											<span class="input-group-addon"> <i class="fa fa-mobile"></i> </span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="form-field-1">
										Address
									</label>
									<div class="col-sm-7">
										<textarea placeholder="Enter Address..." id="address" class="form-control" name="address"><?php echo !empty($select['address']) ? $select['address'] : '' ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-2"></div>
									<div class="col-sm-7">
										<input type="submit" id="salesman-submit" class="btn btn-success btn-squared" value="Save">
										<a href="salesman_list.php" class="btn btn-danger btn-squared">Cancel</a>
									</div>
								</div>
								
							</form>
						</div>
					</div>
					<!-- end: TEXT FIELDS PANEL -->
				</div>
			</div>
		</div>
	</div>
	<!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->

<?php include("include/footer.php");?>
<script type="text/javascript">
$(document).ready(function() {
	
});

function ajaxindicatorstart()
{
	if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
	jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="Loading.GIF"></div><div class="bg"></div></div>');
	}

	jQuery('#resultLoading').css({
		'width':'100%',
		'height':'100%',
		'position':'fixed',
		'z-index':'10000000',
		'top':'0',
		'left':'0',
		'right':'0',
		'bottom':'0',
		'margin':'auto'
	});

	jQuery('#resultLoading .bg').css({
		'background':'#000000',
		'opacity':'0.7',
		'width':'100%',
		'height':'100%',
		'position':'absolute',
		'top':'0'
	});

	jQuery('#resultLoading>div:first').css({
		'width': '250px',
		'height':'75px',
		'text-align': 'center',
		'position': 'fixed',
		'top':'0',
		'left':'0',
		'right':'0',
		'bottom':'0',
		'margin':'auto',
		'font-size':'16px',
		'z-index':'10',
		'color':'#ffffff'

	});

	jQuery('#resultLoading .bg').height('100%');
	   jQuery('#resultLoading').fadeIn(300);
	jQuery('body').css('cursor', 'wait');
}
function ajaxindicatorstop()
{
	jQuery('#resultLoading .bg').height('100%');
	   jQuery('#resultLoading').fadeOut(300);
	jQuery('body').css('cursor', 'default');
}
jQuery(document).ajaxStart(function () {
	//show ajax indicator
	//ajaxindicatorstart();
	}).ajaxStop(function () {
	//hide ajax indicator
	ajaxindicatorstop();
});
</script>