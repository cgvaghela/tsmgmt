<?php include_once('include/comman_session.php');
include("include/header.php");?>
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
							Product
						</li>
						
					</ol>
					<div class="page-header">
						<h1>Cashier Veihicle List Today </h1>
					</div>
					<div id="nestable-menu" class="pull-right">
						<a href="cashier.php" class="btn btn-primary btn-squared">Casheir Search</a>
						<a href="cashier_list.php" class="btn btn-primary btn-squared">Casheir Veihical List</a>
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
							Cashier Veihicle List All
							<div class="panel-tools">
								<a class="btn btn-xs btn-link" href=""><i class="fa fa-refresh"></i></a>
								<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
							</div>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<div class="no-display loading-div">
									<img src="loading.gif" height="45" width="45">
								</div>
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
								<table id="loadmaincat" cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" width="100%">
									<thead>
										<tr>
											<th>Re No.</th>
											<th>Sub Re No.</th>
											<th>Branch</th>
											<th>Chassis No</th>
											<th>Model</th>
											<th>Sales Man</th>
											<th>Name</th>
											<th>Mobile</th>
											<th>City</th>
											<th>Total</th>
											<th>Remark</th>
											<th>Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Re No.</th>
											<th>Sub Re No.</th>
											<th>Branch</th>
											<th>Chassis No</th>
											<th>Model</th>
											<th>Sales Man</th>
											<th>Name</th>
											<th>Mobile</th>
											<th>City</th>
											<th>Total</th>
											<th>Remark</th>
											<th>Date</th>
											<th>Action</th>
										</tr>
									</tfoot>
								</table>
							</div>
							
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
// Search To And From Date
$("#date_to, #date_from").datepicker({
	format: "dd-mm-yyyy",
	autoclose: true
});

// Setup - add a text input to each footer cell
$('#loadmaincat tfoot th').each( function () {
	var title = $(this).text();
	$(this).html( '<input style="width:100%;" type="text" placeholder="'+title+'" />' );
});

// List
var table;
$(document).ready(function() {
	// All Data
	table = $('#loadmaincat').DataTable({  
		"ajax" : {
			"url" : "model/cashierList.php",
			"dataSrc": "data",
			"type" : "POST",
			"data" : {
				"list" : "list",
				"type" : "today"
			}
		},
		dom: 'lBfrtip',
		buttons: [
			'csv','print'
		],
		"columns" : [{
			"data" : "id"
		},{
			"data" : "sub_id"
		},{
			"data" : "branch"
		},{
			"data" : "chassis_no"
		},{
			"data" : "model"
		},{
			"data" : "sales_man"
		},{
			"data" : "name"
		},{
			"data" : "mobile"
		},{
			"data" : "city"
		},{
			"data" : "total"
		},{
			"data" : "remark"
		},{
			"data" : "date"
		},{
			"data" : "action"
		}],
		"scrollX" : true,
		"order": [[11, 'desc']],
	});
	
	// Delete Data
	$(document).on("click", ".to_delete", function(e) {
		var id = $(this).attr("id");
		var table = $('#loadmaincat').DataTable();
		var select = $(this).closest('tr');
		var a_delete = "cashier_delete";
		select.addClass("selected");
		
		if (confirm('Are you sure you want to delete full chassis no payment ?')) {
			$.ajax({
				type : "POST",
				url : "model/cashierManage.php",
				cache : false,
				data : {
					type : a_delete, mid : id
				},
				beforeSend: function() {
					$(".loading-div").removeClass('no-display');
					$(".loading-div").addClass('display');
				},
				success : function(result) {
					$(".loading-div").removeClass('display');
					$(".loading-div").addClass('no-display');
					if (result==0) {
						alert("Delete Row Successfully");
						table.row('.selected').remove().draw(false);
						table.ajax.reload();
					} else if(result==1) {
						alert("Does not delete : \n" + result);
						select.removeClass('selected');
						table.ajax.reload();
					}
				}
			});
		} else {
			select.removeClass('selected');
			return false;
		}
		return false;
	});

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
<style>
.product-loading {
    margin: 0 auto;
    text-align: center;
    width: 30px;
}
</style>