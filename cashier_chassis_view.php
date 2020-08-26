<?php include_once('include/comman_session.php');
if(isset($_REQUEST['aid'])){
	$id = $_REQUEST['aid'];
}else{
	header('Location:cashier.php');
	exit();
}

$table = "product";
$table1 = "veihicle";
$table2 = "customer_detail";
$table3 = "product_price";
$table4 = "customer_payment";

$product = $db->getRow("SELECT * FROM ".$table." where `chassis_no`=?",array($id));
$salesmans = $db->getRows("SELECT * FROM `salesman` where `status`=?",array(1));
// all advance receipt code
$advance = $db->getRows("SELECT * FROM `advance` where `status`=? AND `refund`=?",array(0,0));
if(!empty($product)){
	if(!empty($product['veihicle_id']) && ($product['veihicle_id']!=0)){
		if(!file_exists(BARCODE_PATH_DISPLAY.$product['barcode_imgPath'])){
			$_SESSION['admin_error'] = "Barcode Not Created. Please Contact Sub-Admin Or Admin...";
			header('Location:cashier.php');
			exit();
		}
		$veihicle = $db->getRow("SELECT * FROM ".$table1." where `veihicle_id`=?",array($product['veihicle_id']));
	}else{
		$_SESSION['admin_error'] = "Please Select Model Then Casier add Detail Contact Sub-Admin Or Admin...";
		header('Location:cashier.php');
		exit();
	}
	// Product Price
	$productPrice = $db->getRow("SELECT * FROM ".$table3." where `product_id`=?",array($product['product_id']));
	$customer = $db->getRow("SELECT * FROM ".$table2." where `product_id`=?",array($product['product_id']));
	if(!empty($customer))
	{
		header('Location:cashier_detail_edit.php?aid='.$customer['customer_detail_id']);
		exit();
		
		// Customer paymnet
		$customerPayment = $db->getRows("SELECT * FROM ".$table4." where `customer_detail_id`=?",array($customer['customer_detail_id']));
		// Exchange
		$exchange = $db->getRow("SELECT * FROM `exchange` where `customer_detail_id`=?",array($customer['customer_detail_id']));
		if(!empty($exchange)){
			$exchange_open = "display:block";
		}else{
			$exchange_open = "display:none";
		}
		// Finance
		$finance = $db->getRow("SELECT * FROM `finance` where `product_id`=?",array($product['product_id']));
		if(!empty($finance)){
			$finance_open = "display:block";
		}else{
			$finance_open = "display:none";
		}
	}
}else{
	$_SESSION['admin_error'] = "Chassis No. Not Found Enter Properly...";
	header('Location:cashier.php');
	exit();
}
include("include/header.php");
if($customer){
	$type = "edit";
	$admin_id = $customer['admin_id'];
}else{
	$type = "add";
	$admin_id = $_SESSION['admin_id'];
	$exchange_open = "display:none";
	$finance_open = "display:none";
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
							Vehicle
						</li>
					</ol>
					<div class="page-header">
						<h3>Vehicle Detail </h3>
					</div>
					<!-- end: PAGE TITLE & BREADCRUMB -->
				</div>
			</div>
			<!-- end: PAGE HEADER -->
			<!-- start: PAGE CONTENT -->
			<div class="row">
				<div class="col-sm-12 product-view-main">
					<div class="product-view-left col-sm-6">
						<div class="col-sm-4 product-view-title"><h4> Model : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['model']) ? $product['model'] : '-';?> </h4></div>
						<div class="col-sm-4 product-view-title"><h4> Color : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['color']) ? $product['color'] : '-';?> </h4></div>
						<div class="col-sm-4 product-view-title"><h4> Variant : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['variant']) ? $product['variant'] : '-';?> </h4></div>
					</div>
					<div class="product-view-right col-sm-6">
						<div class="col-sm-4 product-view-title"><h4> Engine No. : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['eng_no']) ? $product['eng_no'] : '-';?> </h4></div>
						<div class="col-sm-4 product-view-title"><h4> Chassis No. : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['chassis_no']) ? $product['chassis_no'] : '-';?> </h4></div>
						<div class="col-sm-4 product-view-title"><h4> Key No. : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['key_no']) ? $product['key_no'] : '-';?> </h4></div>
						<div class="col-sm-4 product-view-title"><h4> Entered Date. : </h4></div>
						<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($product['created_at']) ? date("d-m-Y h:i A",strtotime($product['created_at'])) : '-';?> </h4></div>
					</div>
				</div>
				<div class="clearfix"></div>
				<br>
				
				<form class="form-horizontal" method="post" action="model/cashierManage.php" id="cashier_add_frm" name="cashier_add_frm" enctype="multipart/form-data" target="_blank">
					<input type="hidden" value="<?php echo $type;?>" name="type">
					<input type="hidden" value="<?php echo $admin_id;?>" name="admin_id">
					<input type="hidden" value="<?php echo !empty($customer['customer_detail_id']) ? $customer['customer_detail_id'] : '0';?>" name="id">
					<input type="hidden" value="<?php echo !empty($product['product_id']) ? $product['product_id'] : '0';?>" name="product_id">
					<?php if(!empty($veihicle)){ ?>
					<div class="col-sm-12 product-view-main" >
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
							<?php print_r($customer);?>
						<div class="col-sm-12" style="border:1px solid #c8c7cc;">
							<div class="page-header">
								<h3>Vehicle Price Detail <span class="pull-right">Date: <input type="text" name="currentDate" class="currentDate date-picker" value="<?php echo !empty($customer['created_at']) ? date("d-m-Y",strtotime($customer['created_at'])) : date("d-m-Y"); ?>"></span></h3>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Advance Booing Recept No.
								</label>
								<div class="col-sm-7">
									<select class="form-control" name="advance_id" id="advance_id">
										<option value="" data-advance="0">Select Receipt No.</option>
									<?php
										foreach ($advance as $value)
										{
										$advancePrice = 0;
										// Check Advance Booking is finance or not
										if($value['finance']=="Yes" || $value['finance']=="yes"){
											//$advancePrice += 200;
										}
										// Find Advance Payment
										$advancePayment = $db->getRows("SELECT * FROM `advance_payment` where `advance_id`=?",array($value['advance_id']));
										foreach($advancePayment as $advancePaymentValue){
											$advancePrice += $advancePaymentValue['price'];
										}
									?>
										<option value="<?php echo $value['advance_id']; ?>" <?php if($value['advance_id']==$customer['advance_id']){echo "selected";}?> data-advance="<?php echo $advancePrice;?>"><?php echo str_pad($value['advance_id'], 3, "0", STR_PAD_LEFT);?></option>
									<?php } ?>
									</select>
									<input type="hidden" class="advance_book" value="0" name="advance_book">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Price : 
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" readonly placeholder="Price" id="vehical_price" class="form-control" name="vehical_price" value="<?php echo !empty($productPrice['price']) ? $productPrice['price'] : $veihicle['price']; ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									RTO Regestration:
								</label>
								<div class="col-sm-7">
								<?php $rtoArray = explode("/",$productPrice['rto']); 
									$rtoType = !empty($rtoArray[0]) ? $rtoArray[0] : 'single';
									$rtoPrice = !empty($rtoArray[1]) ? $rtoArray[1] : $veihicle['rto_single'];
								?>
									<select id="rto" class="form-control" name="rto">
										<option value="no" data-rtoprice="550" <?php echo $rtoType=='no' ? 'selected' : '';?>>No - 0</option>
										<option value="single" <?php echo $rtoType=='single' ? 'selected' : '';?> data-rtoprice="<?php echo $veihicle['rto_single'] ? $veihicle['rto_single'] : '0';?>">Single - <?php echo !empty($veihicle['rto_single']) ? $veihicle['rto_single'] : '0';?></option>
										<option value="double" <?php echo $rtoType=='double' ? 'selected' : '';?> data-rtoprice="<?php echo !empty($veihicle['rto_double']) ? $veihicle['rto_double'] : '0';?>">Double - <?php echo !empty($veihicle['rto_double']) ? $veihicle['rto_double'] : '0';?></option>
									</select>
									<input type="hidden" class="rtoPrice" value="<?php echo $rtoPrice; ?>" name="rtoPrice">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Accessories :
								</label>
								<div class="col-sm-7">
									<input type="checkbox" name="access_no" id="access_no" value="<?php echo $productPrice['access_no']=="no" ? 'no' : 'yes'?>" <?php echo $productPrice['access_no']=="no" ? 'checked' : '';?>> 
										<label class="control-label" for="access_no"> Accessories No </label><br>
									<input type="checkbox" name="side_stand" id="side_stand" value="<?php echo !empty($productPrice['side_stand']) ? $productPrice['side_stand'] : $veihicle['side_stand']?>" <?php echo !empty($productPrice['side_stand']) ? 'checked' : '';?>> 
										<label class="control-label" for="side_stand">Side Stand </label><br>
									<input type="checkbox" name="foot_rest" id="foot_rest" value="<?php echo !empty($productPrice['foot_rest']) ? $productPrice['foot_rest'] : $veihicle['foot_rest'] ?>" <?php echo !empty($productPrice['foot_rest']) ? 'checked' : '';?>> 
										<label class="control-label" for="foot_rest"> Foot Rest </label><br>
									<input type="checkbox" name="chrome_set" id="chrome_set" value="<?php echo !empty($productPrice['chrome_set']) ? $productPrice['chrome_set'] : $veihicle['chrome_set'] ?>" <?php echo !empty($productPrice['chrome_set']) ? 'checked' : '';?>> 
										<label class="control-label" for="chrome_set"> Chrome Set </label><br>
									<input type="checkbox" name="leg_guard" id="leg_guard" value="<?php echo !empty($productPrice['leg_guard']) ? $productPrice['leg_guard'] : $veihicle['leg_guard'] ?>" <?php echo !empty($productPrice['leg_guard']) ? 'checked' : '';?>> 
										<label class="control-label" for="leg_guard"> Leg Guard </label><br>
									
									<!-- Pending Accessories -->
									<input type="checkbox" name="access" id="access" value="<?php echo !empty($productPrice['access']) ? 'Yes' : 'No';?>" <?php echo (!empty($productPrice['access'])=="Yes") ? 'checked' : '';?>> <label class="control-label" for="access"> Accessories Pending </label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Smart No Plate Fitting Charge : 
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Smart No Plate Fitting Charge" id="no_plate_fitting" class="form-control" name="no_plate_fitting" value="<?php echo !empty($productPrice['no_plate_fitting']) ? $productPrice['no_plate_fitting'] : $veihicle['no_plate_fitting'] ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									AMC :
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="AMC" id="amc" class="form-control" name="amc" value="<?php echo !empty($productPrice['amc']) ? $productPrice['amc'] : $veihicle['amc']; ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									RMC Veheicle Tax. : 
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="RMC Veheicle Tax." id="rmc_tax" class="form-control" name="rmc_tax" value="<?php echo !empty($productPrice['rmc_tax']) ? $productPrice['rmc_tax'] : $veihicle['rmc_tax'] ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Ex. Warranty. :
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Ex. Warranty" id="ex_warranty" class="form-control" name="ex_warranty" value="<?php echo !empty($productPrice['ex_warranty']) ? $productPrice['ex_warranty'] : $veihicle['ex_warranty'] ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Insurance. : 
								</label>
								<div class="col-sm-7">
								<?php $insuArray = explode("/",$productPrice['insurance']); 
									$insuType = !empty($insuArray[0]) ? $insuArray[0] : '0';
									$insuPrice = !empty($insuArray[1]) ? $insuArray[1] : $veihicle['insurance'];
								?>
									<select id="insurance" class="form-control" name="insurance">
										<option <?php echo $insuType=='1' ? 'selected' : '';?> value="1" data-price="<?php echo !empty($veihicle['insurance']) ? $veihicle['insurance'] : '0';?>">1 Year - <?php echo !empty($veihicle['insurance']) ? $veihicle['insurance'] : '0';?></option>
										<option <?php echo $insuType=='2' ? 'selected' : '';?> value="2" data-price="<?php echo !empty($veihicle['2_year_insurance']) ? $veihicle['2_year_insurance'] : '0';?>">2 Year - <?php echo !empty($veihicle['2_year_insurance']) ? $veihicle['2_year_insurance'] : '0';?></option>
										<option <?php echo $insuType=='3' ? 'selected' : '';?> value="3" data-price="<?php echo !empty($veihicle['3_year_insurance']) ? $veihicle['3_year_insurance'] : '0';?>">3 Year - <?php echo !empty($veihicle['3_year_insurance']) ? $veihicle['3_year_insurance'] : '0';?></option>
									</select>
									<input type="hidden" class="insurancePrice" name="insurancePrice" value="<?php echo $insuPrice;?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Discount : 
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Discount" id="discount" class="form-control" name="discount" value="<?php echo !empty($productPrice['discount']) ? $productPrice['discount'] : '0' ?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Total. : 
								</label>
								<div class="col-sm-7">
									<h4 class="main-total"> <?php echo !empty($productPrice['total']) ? $productPrice['total'] : $veihicle['price'];?>  <i class="fa fa-rupee pull-right"></i></h4>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					
					<?php if(!empty($customerPayment)){
						$paid_amount = 0;
					?>
					<div class="col-sm-12">
						<div class="page-header">
							<h3>Customer Payeble Amount</h3>
						</div>
					<?php foreach($customerPayment as $value){?>
						<a target="_blank" href="customer_payment_detail_edit.php?aid=<?php echo $value['customer_payment_id'];?>" class="text-right"><i class="fa fa-edit"></i></a>
						<div class="product-view-main">
							<div class="product-view-left col-sm-6">
								<div class="col-sm-4 product-view-title"><h4> Cash Type : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['case_type']) ? $value['case_type'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Amount : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['price']) ? $value['price'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Amount In Word : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['amount_in_word']) ? $value['amount_in_word'] : '-';?> </h4></div>
							</div>
							<div class="product-view-right col-sm-6">
							<?php if($value['case_type']=="Cheque"){?>
								<div class="col-sm-4 product-view-title"><h4> Bank Name : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['bank_name']) ? $value['bank_name'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Cheque No. : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['cheque_no']) ? $value['cheque_no'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Cheque Date. : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['cheque_date']) ? date("d-m-Y",strtotime($value['cheque_date'])) : '-';?> </h4></div>
							<?php }else if($value['case_type']=="DD"){ ?>
								<div class="col-sm-4 product-view-title"><h4> Bank Name : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['dd_bank_name']) ? $value['dd_bank_name'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> DD No. : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['dd_no']) ? $value['dd_no'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> DD Date. : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['dd_date']) ? date("d-m-Y",strtotime($value['dd_date'])) : '-';?> </h4></div>
							<?php }else if($value['case_type']=="NEFT"){ ?>
								<div class="col-sm-4 product-view-title"><h4> Account No. : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['neft_ac_no']) ? $value['neft_ac_no'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Bank Name : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['neft_bank_name']) ? $value['neft_bank_name'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> IFSC Code : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['neft_ifsc_code']) ? $value['neft_ifsc_code'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Holder Name : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['neft_holder_name']) ? $value['neft_holder_name'] : '-';?> </h4></div>
							<?php } ?>
								<div class="col-sm-4 product-view-title"><h4> Date : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($value['created_at']) ? date("d-m-Y h:i A",strtotime($value['created_at'])) : '-';?> </h4></div>
							</div>
						</div>
						<div class="clearfix"></div>
							<?php
								$paid_amount += $value['price'];
							?>
					<?php } ?>
					</div>
					<?php } ?>
					
					<?php if(!empty($exchange)){
						// Add Exchange amount in paid
						$paid_amount += $exchange['amount'];
					?>
						<div class="col-sm-12">
							<div class="page-header">
								<h3>Exchange</h3>
							</div>
							<a target="_blank" href="customer_payment_exchange_edit.php?aid=<?php echo $exchange['exchange_id'];?>" class="text-right"><i class="fa fa-edit"></i></a>
							<div class="product-view-main">
								<div class="product-view-left col-sm-6">
									<div class="col-sm-4 product-view-title"><h4> Amount : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($exchange['amount']) ? $exchange['amount'] : '-';?> </h4></div>
								</div>
								<div class="product-view-right col-sm-6">
									<div class="col-sm-4 product-view-title"><h4> Vehicle No. : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($exchange['veihicle_no']) ? $exchange['veihicle_no'] : '-';?> </h4></div>
									<div class="col-sm-4 product-view-title"><h4> Date : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($exchange['created_at']) ? date("d-m-Y h:i A",strtotime($exchange['created_at'])) : '-';?> </h4></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } ?>
					
					<?php if(!empty($finance)){
						// Add Finance amount in paid
						$paid_amount += $finance['finance_amount'];
					?>
						<div class="col-sm-12">
							<div class="page-header">
								<h3> Finance </h3>
							</div>
							<a target="_blank" href="customer_payment_finance_edit.php?aid=<?php echo $finance['finance_id'];?>" class="text-right"><i class="fa fa-edit"></i></a>
							<div class="product-view-main">
								<div class="product-view-left col-sm-6">
									<div class="col-sm-4 product-view-title"><h4> Finance Amount : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($finance['finance_amount']) ? $finance['finance_amount'] : '-';?> </h4></div>
									<div class="col-sm-4 product-view-title"><h4> DP Amount : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($finance['dp_amount']) ? $finance['dp_amount'] : '-';?> </h4></div>
								</div>
								<div class="product-view-right col-sm-6">
									<div class="col-sm-4 product-view-title"><h4> Bank : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($finance['bank']) ? $finance['bank'] : '-';?> </h4></div>
									<div class="col-sm-4 product-view-title"><h4> Date : </h4></div>
									<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($finance['created_at']) ? date("d-m-Y h:i A",strtotime($finance['created_at'])) : '-';?> </h4></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } ?>
					
					<!-- Branch Start-->
					<?php if($product['branch_id']!=0){
						$branch = $db->getRow("SELECT * FROM `branch` where `branch_id`=?",array($product['branch_id']));
					?>
					<div class="col-sm-12">
						<div class="page-header">
							<h3>Branch ->  <?php echo $branch['name']?> </h3>
						</div>
					</div>
					<?php } ?>
					
					<!-- Customer Detail Edit-->
					<?php if(!empty($customer)){
						
					?>
					<div class="col-sm-12">
						<div class="page-header">
							<h3> Customer Detail Edit </h3>
						</div>
						<a target="_blank" href="customer_detail_edit.php?aid=<?php echo $customer['customer_detail_id'];?>" class="text-right"><i class="fa fa-edit"></i></a>
						<div class="product-view-main">
							<div class="product-view-left col-sm-6">
								<div class="col-sm-4 product-view-title"><h4> Name : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['name']) ? $customer['name'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Mobile No : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['mobile']) ? $customer['mobile'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4>Street Address 1 </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['street_add1']) ? $customer['street_add1'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4>Street Address 2 </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['street_add2']) ? $customer['street_add2'] : '-';?> </h4></div>
							</div>
							<div class="product-view-right col-sm-6">
								<div class="col-sm-4 product-view-title"><h4> District : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['city']) ? $customer['city'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Country : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['country']) ? $customer['country'] : '-';?> </h4></div>
								<div class="col-sm-4 product-view-title"><h4> Date : </h4></div>
								<div class="col-sm-8 product-view-data"><h4> <?php echo !empty($customer['created_at']) ? date("d-m-Y h:i A",strtotime($customer['created_at'])) : '-';?> </h4></div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php } ?>
					
					<div class="col-sm-12">
						<div class="page-header">
							<h3>Customer Detail <?php echo $type;?> - <small class="required-field">* asterisk mark will be compulsory</small></h3>
						</div>
						<?php if($type!="edit"){?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Sales Man Name <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<select name="salesman_id" id="salesman_id" class="form-control">
									<option value="">Select Sales Man</option>
								<?php !empty($customer['salesman_id']) ? $salesmanId=$customer['salesman_id'] : $salesmanId="";
								foreach($salesmans as $salesman){?>
									<option value="<?php echo $salesman['salesman_id'];?>" <?php echo $salesmanId==$salesman['salesman_id'] ? 'selected' : '';?>><?php echo $salesman['name'];?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Customer Name <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<input type="text" placeholder="Customer Name" id="name" class="form-control" name="name" value="<?php echo !empty($customer['name']) ? $customer['name'] : '' ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Customer Mobile No <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="text" placeholder="Customer Mobile No" id="mobile" class="form-control" name="mobile" value="<?php echo !empty($customer['mobile']) ? $customer['mobile'] : '' ?>">
									<span class="input-group-addon"> <i class="fa fa-mobile"></i> </span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Street Address 1
							</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="text" placeholder="Street Address 1" id="street_add1" class="form-control" name="street_add1" value="<?php echo !empty($customer['street_add1']) ? $customer['street_add1'] : '' ?>">
									<span class="input-group-addon"> <i class="fa fa-map-marker"></i> </span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Street Address 2
							</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="text" placeholder="Street Address 2" id="street_add2" class="form-control" name="street_add2" value="<?php echo !empty($customer['street_add2']) ? $customer['street_add2'] : '' ?>">
									<span class="input-group-addon"> <i class="fa fa-map-marker"></i> </span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								District
							</label>
							<div class="col-sm-7">
								<?php !empty($customer['city']) ? $district=$customer['city'] : $district='Rajkot'?>
								<select id="city" class="form-control" name="city">
									<option value="Ahmedabad" <?php echo $district=="Ahmedabad" ? 'selected' : '' ?>>Ahmedabad</option>
									<option value="Amreli" <?php echo $district=="Amreli" ? 'selected' : '' ?>>Amreli</option>
									<option value="Anand" <?php echo $district=="Anand" ? 'selected' : '' ?>>Anand</option>
									<option value="Aravalli" <?php echo $district=="Aravalli" ? 'selected' : '' ?>>Aravalli</option>
									<option value="Banaskantha" <?php echo $district=="Banaskantha" ? 'selected' : '' ?>>Banaskantha</option>
									<option value="Botad" <?php echo $district=="Botad" ? 'selected' : '' ?>>Botad</option>
									<option value="Bharuch" <?php echo $district=="Bharuch" ? 'selected' : '' ?>>Bharuch</option>
									<option value="Bhavnagar" <?php echo $district=="Bhavnagar" ? 'selected' : '' ?>>Bhavnagar</option>
									<option value="Chhota Udaipur" <?php echo $district=="Chhota Udaipur" ? 'selected' : '' ?>>Chhota Udaipur</option>
									<option value="Dahod" <?php echo $district=="Dahod" ? 'selected' : '' ?>>Dahod</option>
									<option value="Devbhoomi Dwarka" <?php echo $district=="Devbhoomi Dwarka" ? 'selected' : '' ?>>Devbhoomi Dwarka</option>
									<option value="Gandhinagar" <?php echo $district=="Gandhinagar" ? 'selected' : '' ?>>Gandhinagar</option>
									<option value="Gir Somnath" <?php echo $district=="Gir Somnath" ? 'selected' : '' ?>>Gir Somnath</option>
									<option value="Jamnagar" <?php echo $district=="Jamnagar" ? 'selected' : '' ?>>Jamnagar</option>
									<option value="Junagadh" <?php echo $district=="Junagadh" ? 'selected' : '' ?>>Junagadh</option>
									<option value="Kheda" <?php echo $district=="Kheda" ? 'selected' : '' ?>>Kheda</option>
									<option value="Kutch" <?php echo $district=="Kutch" ? 'selected' : '' ?>>Kutch</option>
									<option value="Mahisagar" <?php echo $district=="Mahisagar" ? 'selected' : '' ?>>Mahisagar</option>
									<option value="Mahesana" <?php echo $district=="Mahesana" ? 'selected' : '' ?>>Mahesana</option>
									<option value="Mahesana" <?php echo $district=="Mahesana" ? 'selected' : '' ?>>Mahesana</option>
									<option value="Morbi" <?php echo $district=="Morbi" ? 'selected' : '' ?>>Morbi</option>
									<option value="Narmada" <?php echo $district=="Narmada" ? 'selected' : '' ?>>Narmada</option>
									<option value="Navsari" <?php echo $district=="Navsari" ? 'selected' : '' ?>>Navsari</option>
									<option value="Panchmahal" <?php echo $district=="Panchmahal" ? 'selected' : '' ?>>Panchmahal</option>
									<option value="Patan" <?php echo $district=="Patan" ? 'selected' : '' ?>>Patan</option>
									<option value="Porbandar" <?php echo $district=="Porbandar" ? 'selected' : '' ?>>Porbandar</option>
									<option value="Rajkot" <?php echo $district=="Rajkot" ? 'selected' : '' ?>>Rajkot</option>
									<option value="Sabarkantha" <?php echo $district=="Sabarkantha" ? 'selected' : '' ?>>Sabarkantha</option>
									<option value="Surat" <?php echo $district=="Surat" ? 'selected' : '' ?>>Surat</option>
									<option value="Surendranagar" <?php echo $district=="Surendranagar" ? 'selected' : '' ?>>Surendranagar</option>
									<option value="Tapi" <?php echo $district=="Tapi" ? 'selected' : '' ?>>Tapi</option>
									<option value="The Dangs" <?php echo $district=="The Dangs" ? 'selected' : '' ?>>The Dangs</option>
									<option value="Vadodara" <?php echo $district=="Vadodara" ? 'selected' : '' ?>>Vadodara</option>
									<option value="Valsad" <?php echo $district=="Valsad" ? 'selected' : '' ?>>Valsad</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Country
							</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input readonly type="text" placeholder="Country" id="country" class="form-control" name="country" value="<?php echo !empty($customer['country']) ? $customer['country'] : 'India' ?>">
									<span class="input-group-addon"> <i class="clip-world"></i> </span>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Payment Type <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<input type="radio" class="case_by_case" name="case_type" value="Cash" checked> By Cash &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type="radio" class="case_by_cheque" name="case_type" value="Cheque"> By Cheque &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type="radio" class="case_by_dd" name="case_type" value="DD"> Demand Draft &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type="radio" class="case_by_neft" name="case_type" value="NEFT"> NEFT / RTGS
							</div>
						</div>
						<div class="by_case_cheque" style="display:none;">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Bank Name <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Bank Name" id="bank_name" class="form-control" name="bank_name" value="">
										<!--<select id="bank_name" name="bank_name" class="form-control">
											<option selected="selected" value="">--Select Bank Name --</option>
											<option value="ABHYUDAYA COOPERATIVE BANK LTD">ABHYUDAYA COOPERATIVE BANK LTD</option>
											<option value="ABU DHABI COMMERCIAL BANK">ABU DHABI COMMERCIAL BANK</option>
											<option value="ALLAHABAD BANK">ALLAHABAD BANK</option>
											<option value="ALMORA URBAN CO.OPERATIVE BANK LTD.">ALMORA URBAN CO.OPERATIVE BANK LTD.</option>
											<option value="ANDHRA BANK">ANDHRA BANK</option>
											<option value="APNA SAHAKARI BANK LTD">APNA SAHAKARI BANK LTD</option>
											<option value="AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED.">AUSTRALIA AND NEW ZEALAND BANKING GROUP LIMITED.</option>
											<option value="AXIS BANK">AXIS BANK</option>
											<option value="BANK OF AMERICA">BANK OF AMERICA</option>
											<option value="BANK OF BAHRAIN AND KUWAIT">BANK OF BAHRAIN AND KUWAIT</option>
											<option value="BANK OF BARODA">BANK OF BARODA</option>
											<option value="BANK OF CEYLON">BANK OF CEYLON</option>
											<option value="BANK OF INDIA">BANK OF INDIA</option>
											<option value="BANK OF MAHARASHTRA">BANK OF MAHARASHTRA</option>
											<option value="BANK OF TOKYO MITSUBISHI UFJ LTD.">BANK OF TOKYO MITSUBISHI UFJ LTD.</option>
											<option value="BARCLAYS BANK PLC">BARCLAYS BANK PLC</option>
											<option value="BASSEIN CATHOLIC CO OP BANK LTD">BASSEIN CATHOLIC CO OP BANK LTD</option>
											<option value="BNP PARIBAS">BNP PARIBAS</option>
											<option value="CANARA BANK">CANARA BANK</option>
											<option value="CAPITAL LOCAL AREA BANK LTD.">CAPITAL LOCAL AREA BANK LTD.</option>
											<option value="CATHOLIC SYRIAN BANK LTD">CATHOLIC SYRIAN BANK LTD</option>
											<option value="CENTRAL BANK OF INDIA">CENTRAL BANK OF INDIA</option>
											<option value="CHINATRUST COMMERCIAL BANK">CHINATRUST COMMERCIAL BANK</option>
											<option value="CITIBANK NA">CITIBANK NA</option>
											<option value="CITIZENCREDIT CO.OPERATIVE BANK LTD">CITIZENCREDIT CO.OPERATIVE BANK LTD</option>
											<option value="CITY UNION BANK LTD">CITY UNION BANK LTD</option>
											<option value="COMMONWEALTH BANK OF AUSTRALIA">COMMONWEALTH BANK OF AUSTRALIA</option>
											<option value="CORPORATION BANK">CORPORATION BANK</option>
											<option value="CREDIT AGRICOLE CORPORATE AND INVESTEMENT BANK">CREDIT AGRICOLE CORPORATE AND INVESTEMENT BANK</option>
											<option value="CREDIT SUISSE AG">CREDIT SUISSE AG</option>
											<option value="DBS BANK LTD">DBS BANK LTD</option>
											<option value="DENA BANK">DENA BANK</option>
											<option value="DEUTSCHE BANK">DEUTSCHE BANK</option>
											<option value="DEVELOPMENT CREDIT BANK LIMITED">DEVELOPMENT CREDIT BANK LIMITED</option>
											<option value="DHANLAXMI BANK LTD">DHANLAXMI BANK LTD</option>
											<option value="DICGC">DICGC</option>
											<option value="DOMBIVLI NAGARI SAHAKARI BANK LIMITED">DOMBIVLI NAGARI SAHAKARI BANK LIMITED</option>
											<option value="FIRSTRAND BANK LIMITED">FIRSTRAND BANK LIMITED</option>
											<option value="GURGAON GRAMIN BANK">GURGAON GRAMIN BANK</option>
											<option value="HDFC BANK LTD">HDFC BANK LTD</option>
											<option value="HSBC">HSBC</option>
											<option value="ICICI BANK LTD">ICICI BANK LTD</option>
											<option value="IDBI BANK LTD">IDBI BANK LTD</option>
											<option value="INDIAN BANK">INDIAN BANK</option>
											<option value="INDIAN OVERSEAS BANK">INDIAN OVERSEAS BANK</option>
											<option value="INDUSIND BANK LTD">INDUSIND BANK LTD</option>
											<option value="ING VYSYA BANK LTD">ING VYSYA BANK LTD</option>
											<option value="JALGAON JANATA SAHKARI BANK LTD">JALGAON JANATA SAHKARI BANK LTD</option>
											<option value="JANAKALYAN SAHAKARI BANK LTD">JANAKALYAN SAHAKARI BANK LTD</option>
											<option value="JANATA SAHAKARI BANK LTD (PUNE )">JANATA SAHAKARI BANK LTD (PUNE )</option>
											<option value="JP MORGAN CHASE BANK">JP MORGAN CHASE BANK</option>
											<option value="KALLAPPANNA AWADE ICH JANATA S BANK">KALLAPPANNA AWADE ICH JANATA S BANK</option>
											<option value="KAPOLE CO OP BANK">KAPOLE CO OP BANK</option>
											<option value="KARNATAKA BANK LTD">KARNATAKA BANK LTD</option>
											<option value="KARUR VYSYA BANK">KARUR VYSYA BANK</option>
											<option value="KOTAK MAHINDRA BANK">KOTAK MAHINDRA BANK</option>
											<option value="MAHANAGAR CO.OP BANK LTD">MAHANAGAR CO.OP BANK LTD</option>
											<option value="MAHARASHTRA STATE CO OPERATIVE BANK">MAHARASHTRA STATE CO OPERATIVE BANK</option>
											<option value="MASHREQ BANK PSC">MASHREQ BANK PSC</option>
											<option value="MIZUHO CORPORATE BANK LTD">MIZUHO CORPORATE BANK LTD</option>
											<option value="MUMBAI DISTRICT CENTRAL CO.OP. BANK LTD.">MUMBAI DISTRICT CENTRAL CO.OP. BANK LTD.</option>
											<option value="NAGPUR NAGRIK SAHAKARI BANK LTD">NAGPUR NAGRIK SAHAKARI BANK LTD</option>
											<option value="NEW INDIA CO.OPERATIVE BANK LTD">NEW INDIA CO.OPERATIVE BANK LTD</option>
											<option value="NKGSB CO.OP BANK LTD">NKGSB CO.OP BANK LTD</option>
											<option value="NORTH MALABAR GRAMIN BANK">NORTH MALABAR GRAMIN BANK</option>
											<option value="NUTAN NAGARIK SAHAKARI BANK LTD">NUTAN NAGARIK SAHAKARI BANK LTD</option>
											<option value="OMAN INTERNATIONAL BANK SAOG">OMAN INTERNATIONAL BANK SAOG</option>
											<option value="ORIENTAL BANK OF COMMERCE">ORIENTAL BANK OF COMMERCE</option>
											<option value="PARSIK JANATA SAHAKARI BANK LTD">PARSIK JANATA SAHAKARI BANK LTD</option>
											<option value="PRATHAMA BANK">PRATHAMA BANK</option>
											<option value="PRIME CO OPERATIVE BANK LTD">PRIME CO OPERATIVE BANK LTD</option>
											<option value="PUNJAB AND MAHARASHTRA CO OP BANK LTD">PUNJAB AND MAHARASHTRA CO OP BANK LTD</option>
											<option value="PUNJAB AND SIND BANK">PUNJAB AND SIND BANK</option>
											<option value="PUNJAB NATIONAL BANK">PUNJAB NATIONAL BANK</option>
											<option value="RABOBANK INTERNATIONAL (CCRB)">RABOBANK INTERNATIONAL (CCRB)</option>
											<option value="RAJKOT NAGARIK SAHAKARI BANK LTD">RAJKOT NAGARIK SAHAKARI BANK LTD</option>
											<option value="RESERVE BANK OF INDIA">RESERVE BANK OF INDIA</option>
											<option value="SBERBANK">SBERBANK</option>
											<option value="SHINHAN BANK">SHINHAN BANK</option>
											<option value="SHRI CHHATRAPATI RAJARSHI SHAHU URBAN CO.OP BANK LTD">SHRI CHHATRAPATI RAJARSHI SHAHU URBAN CO.OP BANK LTD</option>
											<option value="SOCIETE GENERALE">SOCIETE GENERALE</option>
											<option value="SOUTH INDIAN BANK">SOUTH INDIAN BANK</option>
											<option value="STANDARD CHARTERED BANK">STANDARD CHARTERED BANK</option>
											<option value="STATE BANK OF BIKANER AND JAIPUR">STATE BANK OF BIKANER AND JAIPUR</option>
											<option value="STATE BANK OF HYDERABAD">STATE BANK OF HYDERABAD</option>
											<option value="STATE BANK OF INDIA">STATE BANK OF INDIA</option>
											<option value="STATE BANK OF MAURITIUS LTD">STATE BANK OF MAURITIUS LTD</option>
											<option value="STATE BANK OF MYSORE">STATE BANK OF MYSORE</option>
											<option value="STATE BANK OF PATIALA">STATE BANK OF PATIALA</option>
											<option value="STATE BANK OF TRAVANCORE">STATE BANK OF TRAVANCORE</option>
											<option value="SYNDICATE BANK">SYNDICATE BANK</option>
											<option value="TAMILNAD MERCANTILE BANK LTD">TAMILNAD MERCANTILE BANK LTD</option>
											<option value="THANE BHARAT SAHAKARI BANK LTD">THANE BHARAT SAHAKARI BANK LTD</option>
											<option value="THE A.P. MAHESH CO.OP URBAN BANK LTD.">THE A.P. MAHESH CO.OP URBAN BANK LTD.</option>
											<option value="THE AHMEDABAD MERCANTILE CO.OPERATIVE BANK LTD">THE AHMEDABAD MERCANTILE CO.OPERATIVE BANK LTD</option>
											<option value="THE BANK OF NOVA SCOTIA">THE BANK OF NOVA SCOTIA</option>
											<option value="THE BHARAT CO.OPERATIVE BANK (MUMBAI) LTD">THE BHARAT CO.OPERATIVE BANK (MUMBAI) LTD</option>
											<option value="THE COSMOS CO.OPERATIVE BANK LTD">THE COSMOS CO.OPERATIVE BANK LTD</option>
											<option value="THE FEDERAL BANK LTD">THE FEDERAL BANK LTD</option>
											<option value="THE GREATER BOMBAY CO.OP BANK LTD">THE GREATER BOMBAY CO.OP BANK LTD</option>
											<option value="THE GUJARAT STATE CO.OPERATIVE BANK LTD">THE GUJARAT STATE CO.OPERATIVE BANK LTD</option>
											<option value="THE JAMMU AND KASHMIR BANK LTD">THE JAMMU AND KASHMIR BANK LTD</option>
											<option value="THE KALUPUR COMMERCIAL CO OP BANK LTD">THE KALUPUR COMMERCIAL CO OP BANK LTD</option>
											<option value="THE KALYAN JANATA SAHAKARI BANK LTD">THE KALYAN JANATA SAHAKARI BANK LTD</option>
											<option value="THE KANGRA CENTRAL CO.OP BANK LIMITED">THE KANGRA CENTRAL CO.OP BANK LIMITED</option>
											<option value="THE KARAD URBAN CO.OP BANK LTD">THE KARAD URBAN CO.OP BANK LTD</option>
											<option value="THE KARNATAKA STATE APEX COOP. BANK LTD">THE KARNATAKA STATE APEX COOP. BANK LTD</option>
											<option value="THE LAKSHMI VILAS BANK LTD">THE LAKSHMI VILAS BANK LTD</option>
											<option value="THE MEHSANA URBAN COOPERATIVE BANK LTD">THE MEHSANA URBAN COOPERATIVE BANK LTD</option>
											<option value="THE MUNICIPAL CO OPERATIVE BANK LTD MUMBAI">THE MUNICIPAL CO OPERATIVE BANK LTD MUMBAI</option>
											<option value="THE NAINITAL BANK LIMITED">THE NAINITAL BANK LIMITED</option>
											<option value="THE NASHIK MERCHANTS CO.OP BANK LTD., NASHIK">THE NASHIK MERCHANTS CO.OP BANK LTD., NASHIK</option>
											<option value="THE RAJASTHAN STATE COOPERATIVE BANK LTD.">THE RAJASTHAN STATE COOPERATIVE BANK LTD.</option>
											<option value="THE RATNAKAR BANK LTD">THE RATNAKAR BANK LTD</option>
											<option value="THE ROYAL BANK OF SCOTLAND NV">THE ROYAL BANK OF SCOTLAND NV</option>
											<option value="THE SARASWAT CO.OPERATIVE BANK LTD">THE SARASWAT CO.OPERATIVE BANK LTD</option>
											<option value="THE SHAMRAO VITHAL CO.OPERATIVE BANK LTD">THE SHAMRAO VITHAL CO.OPERATIVE BANK LTD</option>
											<option value="THE SURAT DISTRICT CO OPERATIVE BANK LTD.">THE SURAT DISTRICT CO OPERATIVE BANK LTD.</option>
											<option value="THE SURAT PEOPLES CO.OP BANK LTD">THE SURAT PEOPLES CO.OP BANK LTD</option>
											<option value="THE SUTEX CO.OP. BANK LTD.">THE SUTEX CO.OP. BANK LTD.</option>
											<option value="THE TAMILNADU STATE APEX COOPERATIVE BANK LIMITED">THE TAMILNADU STATE APEX COOPERATIVE BANK LIMITED</option>
											<option value="THE THANE JANATA SAHAKARI BANK LTD">THE THANE JANATA SAHAKARI BANK LTD</option>
											<option value="THE VARACHHA CO.OP. BANK LTD.">THE VARACHHA CO.OP. BANK LTD.</option>
											<option value="THE WEST BENGAL STATE COOPERATIVE BANK LTD">THE WEST BENGAL STATE COOPERATIVE BANK LTD</option>
											<option value="TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LTD.,">TUMKUR GRAIN MERCHANTS COOPERATIVE BANK LTD.,</option>
											<option value="UBS AG">UBS AG</option>
											<option value="UCO BANK">UCO BANK</option>
											<option value="UNION BANK OF INDIA">UNION BANK OF INDIA</option>
											<option value="UNITED BANK OF INDIA">UNITED BANK OF INDIA</option>
											<option value="VIJAYA BANK">VIJAYA BANK</option>
											<option value="WOORI BANK">WOORI BANK</option>
											<option value="YES BANK LTD">YES BANK LTD</option>
										</select>-->
										<span class="input-group-addon"> <i class="fa fa-university"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Cheque No <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<input type="text" placeholder="Cheque No" id="cheque_no" class="form-control" name="cheque_no" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Cheque Date <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Cheque Date" id="cheque_date" class="form-control date-picker" name="cheque_date" value="">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="by_case_dd" style="display:none;">
							<!-- Exchange Finance -->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									DD Finance
								</label>
								<div class="col-sm-7">
									<input type="checkbox" id="dd_finance" class="dd_finance" name="dd_finance" value="Yes"> Yes &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								</div>
							</div>
							<div class="dd_finance_open" style="display:none;">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="form-field-1">
										DD Finance Amount <span class="required-field">*</span>
									</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" placeholder="DD Finance Amount" id="dd_finance_amount" class="form-control" name="dd_finance_amount" value="">
											<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Bank Name <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="DD Bank Name" id="dd_bank_name" class="form-control" name="dd_bank_name" value="">
										<span class="input-group-addon"> <i class="fa fa-university"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									DD No <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<input type="text" placeholder="DD No" id="dd_no" class="form-control" name="dd_no" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									DD Date <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="DD Date" id="dd_date" class="form-control date-picker" name="dd_date" value="">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="by_case_neft" style="display:none;">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Bank Name <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="NEFT Bank Name" id="neft_bank_name" class="form-control" name="neft_bank_name" value="">
										<span class="input-group-addon"> <i class="fa fa-university"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									NEFT/RTGS Account No <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<input type="text" placeholder="NEFT/RTGS Account No" id="neft_ac_no" class="form-control" name="neft_ac_no" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									NEFT/RTGS IFSC Code <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<input type="text" placeholder="NEFT/RTGS IFSC Code" id="neft_ifsc_code" class="form-control" name="neft_ifsc_code" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									NEFT/RTGS Holder Name <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="NEFT/RTGS Holder Name" id="neft_holder_name" class="form-control" name="neft_holder_name" value="">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div>
						</div>
						<!-- Exchange Finance -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Exchange & Case=DP
							</label>
							<div class="col-sm-7">
								<input type="checkbox" id="exchange_finance" class="exchange_finance" name="exchange_finance" value="Yes"> Yes &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
							</div>
						</div>
						<!-- Advance Booking in DP -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Advance Booking is DP
							</label>
							<div class="col-sm-7">
								<input type="checkbox" id="advance_dp" class="advance_dp" name="advance_dp" value="Yes"> Yes &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
							</div>
						</div>
						
						<?php if(empty($exchange)){?>
						<!--Exchange-->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Exchange 
							</label>
							<div class="col-sm-7">
								<input type="radio" class="exchange" name="exchange" value="No" <?php echo empty($exchange) ? 'checked' : '';?>> No &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type="radio" class="exchange" name="exchange" value="Yes" <?php echo !empty($exchange) ? 'checked' : '';?>> Yes 
							</div>
						</div>
						<div class="exchange_open" style="<?php echo $exchange_open;?>">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Exchange Amount <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Exchange Amount" id="exchange_amount" class="form-control" name="exchange_amount" value="0">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Vehicle No. <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<input type="text" placeholder="Vehicle No." id="exchange_veihicle_no" class="form-control" name="exchange_veihicle_no" value="">
								</div>
							</div>
						</div>
						<?php } /*else{ ?>
							<input type="hidden" id="exchange_amount" name="exchange_amount" value="<?php echo !empty($exchange['amount']) ? 0 : 0;?>">
						<?php }*/ ?>
						
						<!-- Finance -->
						<?php if(empty($finance)){?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Finance 
							</label>
							<div class="col-sm-7">
								<input type="radio" class="finance" name="finance" value="No" <?php echo empty($finance) ? 'checked' : '';?>> No &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type="radio" class="finance" name="finance" value="Yes" <?php echo !empty($finance) ? 'checked' : '';?>> Yes <small><!--(HPA 200 Already Add Case Receipt)--></small>
							</div>
						</div>
						<div class="finance_open" style="<?php echo $finance_open;?>">
							<?php if(!empty($finance)){?>
								<input type="hidden" value="<?php echo $finance['finance_id'];?>" name="finance_id">
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									HPA
								</label>
								<div class="col-sm-7">
									<input type="checkbox" id="hpa_add" class="hpa_add" name="hpa_add" value="Yes"> (Check to add HPA on Main Price)
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Bank Name <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Finance Bank" id="finance_bank" class="form-control" name="finance_bank" value="">
										<span class="input-group-addon"> <i class="fa fa-university"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Down Payment <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Down Payment" id="dp_amount" class="form-control" name="dp_amount" value="<?php echo !empty($finance['dp_amount']) ? $finance['dp_amount'] : '';?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="form-field-1">
									Finance Amount <span class="required-field">*</span>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" placeholder="Finance Amount" id="finance_amount" class="form-control" name="finance_amount" value="<?php echo !empty($finance['finance_amount']) ? $finance['finance_amount'] : '';?>">
										<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
									</div>
								</div>
							</div>
						</div>
						<?php }/*else{?>
							<input type="hidden" id="dp_amount" name="dp_amount" value="<?php echo !empty($finance['dp_amount']) ? 0 : 0;?>">
							<input type="hidden" id="finance_amount" name="finance_amount" value="<?php echo !empty($finance['finance_amount']) ? 0 : 0;?>">
						<?php } */?>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Amount <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="text" placeholder="Price" id="price" class="form-control" name="price" value="">
									<span class="input-group-addon"> <i class="fa fa-rupee"></i> </span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Pending Amount
							</label>
							<div class="col-sm-7">
								<input type="text" placeholder="Amount In Word" id="pending" class="form-control" name="pending" value="<?php echo !empty($customer['pending']) ? $customer['pending'] : '0' ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Amount In Word <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<input type="text" placeholder="Amount In Word" id="amount_in_word" class="form-control" name="amount_in_word" value="<?php echo !empty($customer['amount_in_word']) ? $customer['amount_in_word'] : '' ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Print Type <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<select class="form-control" name="print_type" id="print_type">
									<option value="Original" <?php echo $type=="add" ? 'selected' : '';?>>Original</option>
									<option value="Duplicate" <?php echo $type=="edit" ? 'selected' : '';?>>Duplicate</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Key No <span class="required-field">*</span>
							</label>
							<div class="col-sm-7">
								<input type="text" placeholder="Key No" id="key_no" class="form-control" name="key_no" value="<?php echo !empty($product['key_no']) ? $product['key_no'] : '' ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="form-field-1">
								Remark
							</label>
							<div class="col-sm-7">
								<textarea placeholder="Remark" id="remark" class="form-control" name="remark"><?php echo !empty($customer['remark']) ? $customer['remark'] : '' ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-2"></div>
							<div class="col-sm-9">
								<?php if($type=="add"){?>
								<input type="submit" class="btn btn-success btn-squared" value="Save">
								<?php }else{ ?>
								<input type="submit" class="btn btn-info btn-squared" value="Update">
								<?php }?>
								<a href="cashier_list_today.php" class="btn btn-danger btn-squared">Cancel</a>
							</div>
						</div>
						<input type="hidden" id="paid_amount" name="paid_amount" value="<?php echo !empty($paid_amount) ? $paid_amount : 0;?>">
						<input type="hidden" id="main-total" class="main-total" name="main_total" value="<?php echo !empty($productPrice['total']) ? $productPrice['total']-$paid_amount : $veihicle['price'];?>">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->

<?php include("include/footer.php");?>
<script type="text/javascript">
// Main Date
$(".currentDate").datepicker({
	format: "dd-mm-yyyy",
	//startDate: '1d',
	autoclose: true
});
// Exchange radio Button
$(".exchange").click(function(){
	var exchange = $(this).val();
	$("#exchange_amount").val(0);
	$("#exchange_veihicle_no").val();
	if(exchange=="Yes" || exchange=="yes"){
		$(".exchange_open").show();
	}else{
		$(".exchange_open").hide();
	}
});
// Finance 
$(".finance").click(function(){
	var finance = $(this).val();
	$("#dp_amount").val(0);
	$("#finance_amount").val(0);
	if(finance=="Yes" || finance=="yes"){
		$(".finance_open").show();
	}else{
		$(".finance_open").hide();
	}
});
// DD Finance 
$("#dd_finance").click(function(){
	if($(this).is(":checked")==true){
		$(".dd_finance_open").show();
	}else{
		$(".dd_finance_open").hide();
	}
});
// Advance Booking
$("#advance_id").change(function(){
	// Find paid amount
	var advance = $('option:selected', this).attr('data-advance');
	//var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
// RTO
$("#rto").change(function(){
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var rtoPrice = $('option:selected', this).attr('data-rtoprice');
	$(".rtoPrice").val(rtoPrice);
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total)-parseInt(paidAmount);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
// all checkbox
$("input[name=access_no]").click(function(){
	if($(this).is(":checked")==true){
		$("#side_stand").removeAttr("checked");
		$("#foot_rest").removeAttr("checked");
		$("#leg_guard").removeAttr("checked");
		$("#chrome_set").removeAttr("checked");
		$("#access").removeAttr("checked");
	}
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total)-parseInt(paidAmount);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});

// all checkbox
$("input[name=side_stand], input[name=foot_rest], input[name=leg_guard], input[name=chrome_set]").click(function(){
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	// only one check box checked then no access in uncheck otherwise check
	if($("input[name=side_stand]").is(":checked")==true || $("input[name=foot_rest]").is(":checked")==true || $("input[name=leg_guard]").is(":checked")==true || $("input[name=chrome_set]").is(":checked")==true){
		//$("#access_no").removeAttr("checked");
		$("#access_no").prop('checked', false);
	}else if($("input[name=side_stand]").is(":checked")==false && $("input[name=foot_rest]").is(":checked")==false && $("input[name=leg_guard]").is(":checked")==false && $("input[name=chrome_set]").is(":checked")==false){
		$("#access_no").prop('checked', true);
	}
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total)-parseInt(paidAmount);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
// select insurance
$("#insurance").change(function(){
	// Find paid amount
	//var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var insurancePrice = $('option:selected', this).attr('data-price');
	$(".insurancePrice").val(insurancePrice);
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
$(".product-view-main input").keyup(function(){
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var total = priceTotal();
	// minus paid amount
	var finalTotal = parseInt(total)-parseInt(paidAmount);
	$(".main-total").val(finalTotal);
	$(".main-total").text(total);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});

// DD Finance Amount
$("#dd_finance_amount").keyup(function(){
	var dd_finance_amount = $(this).val().length!=0 ? $(this).val() : 0;
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	
	var total = parseInt(main_total)-parseInt(paidAmount)-parseInt(dd_finance_amount);
	$("#price").val(total);
	$("#pending").val(0);
	$("#amount_in_word").val(test_skill(total));
});

// Exchange Total
$("#exchange_amount").keyup(function(){
	var exchange_amount = $(this).val().length!=0 ? $(this).val() : 0;
	// Find DD Finance amount
	var dd_finance_amount = $("#dd_finance_amount").val().length!=0 ? $("#dd_finance_amount").val() : 0;
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	
	if($(".main-container").find("#dp_amount").val()){
		var dp_amount = $("#dp_amount").val().length!=0 ? $("#dp_amount").val() : 0;
	}else{
		var dp_amount = 0;
	}
	var total = parseInt(main_total)-parseInt(exchange_amount)-parseInt(dd_finance_amount);
	// minus paid amount
	//var finalTotal = parseInt(total)-parseInt(paidAmount);
	var finalTotal = parseInt(total);
	if($(".main-container").find("#finance_amount").val()){
		$("#finance_amount").val(0);
	}
	$("#price").val(finalTotal);
	if($(".main-container").find("#dp_amount").val()){
		$("#dp_amount").val(finalTotal);
	}
	$("#pending").val(0);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
// Add HPA on main price 
$("#hpa_add").click(function(){
	var total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	if($(this).is(":checked")==true){
		var finalTotal = parseInt(total)+parseInt(200);
	}else{
		var finalTotal = parseInt(total)-parseInt(200);
	}
	$(".main-total").val(finalTotal);
	$(".main-total").text(finalTotal);
	$("#price").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});
// Deposit Amount Total
$("#dp_amount").keyup(function(){
	var dp_amount = $(this).val().length!=0 ? $(this).val() : 0;
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	if($(".main-container").find("#exchange_amount").val()){
		var exchange_amount = $("#exchange_amount").val().length!=0 ? $("#exchange_amount").val() : 0;
	}else{
		var exchange_amount = 0;
	}
	// Exchange Finance And Cash
	var exchange_finance = $("#exchange_finance").is(":checked")==true ? $("#exchange_finance").val() : 0;
	if(exchange_finance=="Yes"){
		if($(".main-container").find("#finance_amount").val()){
			$("#finance_amount").val(parseInt(main_total)-parseInt(paidAmount)-parseInt(dp_amount));
		}
		$("#price").val(parseInt(main_total)-parseInt(paidAmount)-parseInt(dp_amount)-parseInt(exchange_amount));
	}else{
		if($(".main-container").find("#finance_amount").val()){
			$("#finance_amount").val(parseInt(main_total)-parseInt(paidAmount)-parseInt(dp_amount)-parseInt(exchange_amount));
		}
		$("#price").val(dp_amount);
	}
	$("#pending").val(0);
	// word in amount
	$("#amount_in_word").val(test_skill(dp_amount));
});

// Finance DP Amount Price Total
$("#finance_amount").keyup(function(){
	var finance_amount = $(this).val().length!=0 ? $(this).val() : 0;
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	if($(".main-container").find("#exchange_amount").val()){
		var exchange_amount = $("#exchange_amount").val().length!=0 ? $("#exchange_amount").val() : 0;
	}else{
		var exchange_amount = 0;
	}
	var total = parseInt(main_total)-parseInt(exchange_amount)-parseInt(finance_amount);
	// Exchange Finance And Cash
	var exchange_finance = $("#exchange_finance").is(":checked")==true ? $("#exchange_finance").val() : 0;
	if(exchange_finance=="Yes"){
		$("#dp_amount").val(parseInt(main_total)-parseInt(paidAmount)-parseInt(finance_amount));
	}else{
		$("#dp_amount").val(total);
	}
	$("#price").val(total);
	$("#pending").val(0);
	// word in amount
	$("#amount_in_word").val(test_skill(total));
});
// Amount Total to pending Amount
$("#price").keyup(function(){
	var price = $(this).val().length!=0 ? $(this).val() : 0;
	// Find DD Finance amount
	var dd_finance_amount = $("#dd_finance_amount").val().length!=0 ? $("#dd_finance_amount").val() : 0;
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	if($(".main-container").find("#exchange_amount").val()){
		var exchange_amount = $("#exchange_amount").val().length!=0 ? $("#exchange_amount").val() : 0;
	}else{
		var exchange_amount = 0;
	}
	if($(".main-container").find("#finance_amount").val()){
		var finance_amount = $("#finance_amount").val().length!=0 ? $("#finance_amount").val() : 0;
	}else{
		var finance_amount = 0;
	}
	// // Exchange Finance And Cash
	var exchange_finance = $("#exchange_finance").is(":checked")==true ? $("#exchange_finance").val() : 0;
	if(exchange_finance=="Yes"){
		if($(".main-container").find("#dp_amount").val()){
			$("#dp_amount").val(parseInt(price)+parseInt(exchange_amount));
		}
	}else{
		if($(".main-container").find("#dp_amount").val()){
			$("#dp_amount").val(price);
		}
	}
	var total = parseInt(main_total)-parseInt(price)-parseInt(exchange_amount)-parseInt(finance_amount)-parseInt(dd_finance_amount);
	// minus paid amount
	//var finalTotal = parseInt(price)-parseInt(total)-parseInt(paidAmount);
	var finalTotal = parseInt(total);
	$("#pending").val(finalTotal);
	// word in amount
	$("#amount_in_word").val(test_skill(price));
});
// Pending Amount to Price Amount
$("#pending").keyup(function(){
	// Find paid amount
	var paidAmount = $("#paid_amount").val().length!=0 ? $("#paid_amount").val() : 0;
	var pending = $(this).val().length!=0 ? $(this).val() : 0;
	var main_total = $("#main-total").val().length!=0 ? $("#main-total").val() : 0;
	if($(".main-container").find("#exchange_amount").val()){
		var exchange_amount = $("#exchange_amount").val().length!=0 ? $("#exchange_amount").val() : 0;
	}else{
		var exchange_amount = 0;
	}
	if($(".main-container").find("#finance_amount").val()){
		var finance_amount = $("#finance_amount").val().length!=0 ? $("#finance_amount").val() : 0;
	}else{
		var finance_amount = 0;
	}
	var total = parseInt(main_total)-parseInt(pending)-parseInt(exchange_amount)-parseInt(finance_amount);
	// minus paid amount
	//var finalTotal = parseInt(total)-parseInt(paidAmount);
	var finalTotal = parseInt(total);
	$("#price").val(finalTotal);
	if($(".main-container").find("#dp_amount").val()){
		$("#dp_amount").val(finalTotal);
	}
	// word in amount
	$("#amount_in_word").val(test_skill(finalTotal));
});

function priceTotal()
{
	//Advance Booking
	var advanceBook = $('option:selected', "#advance_id").attr('data-advance');
	var price = $("#vehical_price").val();
        var priceVat = parseInt(price)*15/100;
	var priceAdd = 0;
	// Add Vat And Tax in Price
	price = parseInt(price)+parseInt(priceVat)+parseInt(priceAdd);
        var rtoPrice = $(".rtoPrice").val().length!=0 ? $(".rtoPrice").val() : 0;
	var side_stand = $("#side_stand").is(":checked")==true ? $("#side_stand").val() : 0;
	var foot_rest = $("#foot_rest").is(":checked")==true ? $("#foot_rest").val() : 0;
	var leg_guard = $("#leg_guard").is(":checked")==true? $("#leg_guard").val() : 0;
	var chrome_set = $("#chrome_set").is(":checked")==true ? $("#chrome_set").val() : 0;
	var noPlateFitting = $("#no_plate_fitting").val();
	var amc = $("#amc").val().length!=0 ? $("#amc").val() : 0;
	var rmc_tax = $("#rmc_tax").val();
	var ex_warranty = $("#ex_warranty").val().length!=0 ? $("#ex_warranty").val() : 0;
	var insurancePrice = $(".insurancePrice").val().length!=0 ? $(".insurancePrice").val() : 0;
	var discount = $("#discount").val().length!=0 ? $("#discount").val() : 0;
	// Add Vat And Tax in Accessioer
	var accTotalVat =  (parseInt(side_stand)+parseInt(foot_rest)+parseInt(leg_guard)+parseInt(chrome_set))*15/100;
	// check HPA check box check or not
	var hpa_add = $("#hpa_add").is(":checked")==true ? 200 : 0;
	
	var total = parseInt(price)+parseInt(accTotalVat)+parseInt(rtoPrice)+parseInt(side_stand)+parseInt(foot_rest)+parseInt(leg_guard)+parseInt(chrome_set)+parseInt(noPlateFitting)+parseInt(amc)+parseInt(rmc_tax)+parseInt(ex_warranty)+parseInt(insurancePrice)+parseInt(hpa_add)-parseInt(advanceBook)-parseInt(discount);
	
	// Finance Amount/ DP Amount/ Exchange Amount to be '0'
	$("#finance_amount").val(0);
	$("#exchange_amount").val(0);
	$("#dp_amount").val(0);
	
	return total;
}

// Payment Type Radio Button Click
$("input[name~='case_type']").change(function(){
	var case_type = $(this).val();
	if(case_type=="Cheque"){
		$(".by_case_cheque").show();
		$(".by_case_dd").hide();
		$(".by_case_neft").hide();
	}else if(case_type=="DD"){
		$(".by_case_dd").show();
		$(".by_case_cheque").hide();
		$(".by_case_neft").hide();
	}else if(case_type=="NEFT"){
		$(".by_case_neft").show();
		$(".by_case_dd").hide();
		$(".by_case_cheque").hide();
	}else{
		$(".by_case_dd, .by_case_cheque, .by_case_neft").hide();
	}
});

// Cheque Date
$("#cheque_date, #dd_date").datepicker({
	format: "dd-mm-yyyy",
	autoclose: true
});

// Amount in word
function test_skill(junkVal) {
   // var junkVal=document.getElementById('rupees').value;
    var junkVal = junkVal;
    junkVal = Math.floor(junkVal);
    var obStr = new String(junkVal);
    numReversed = obStr.split("");
    actnumber=numReversed.reverse();

    if(Number(junkVal) >=0){
        //do nothing
    }
    else{
        alert('wrong Number cannot be converted');
        return false;
    }
    if(Number(junkVal)==0){
        //return obStr+''+'Rupees Zero Only';
        return obStr+''+'Rupees Zero Only';
        return false;
    }
    if(actnumber.length>9){
        alert('Oops!!!! the Number is too big to covertes');
        return false;
    }

    var iWords=["Zero", " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
    var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];

    var iWordsLength=numReversed.length;
    var totalWords="";
    var inWords=new Array();
    var finalWord="";
    j=0;
    for(i=0; i<iWordsLength; i++){
        switch(i)
        {
        case 0:
            if(actnumber[i]==0 || actnumber[i+1]==1 ) {
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            inWords[j]=inWords[j]+' Only';
            break;
        case 1:
            tens_complication();
            break;
        case 2:
            if(actnumber[i]==0) {
                inWords[j]='';
            }
            else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
                inWords[j]=iWords[actnumber[i]]+' Hundred and';
            }
            else {
                inWords[j]=iWords[actnumber[i]]+' Hundred';
            }
            break;
        case 3:
            if(actnumber[i]==0 || actnumber[i+1]==1) {
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            if(actnumber[i+1] != 0 || actnumber[i] > 0){
                inWords[j]=inWords[j]+" Thousand";
            }
            break;
        case 4:
            tens_complication();
            break;
        case 5:
            if(actnumber[i]==0 || actnumber[i+1]==1) {
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            if(actnumber[i+1] != 0 || actnumber[i] > 0){
                inWords[j]=inWords[j]+" Lakh";
            }
            break;
        case 6:
            tens_complication();
            break;
        case 7:
            if(actnumber[i]==0 || actnumber[i+1]==1 ){
                inWords[j]='';
            }
            else {
                inWords[j]=iWords[actnumber[i]];
            }
            inWords[j]=inWords[j]+" Crore";
            break;
        case 8:
            tens_complication();
            break;
        default:
            break;
        }
        j++;
    }

    function tens_complication() {
        if(actnumber[i]==0) {
            inWords[j]='';
        }
        else if(actnumber[i]==1) {
            inWords[j]=ePlace[actnumber[i-1]];
        }
        else {
            inWords[j]=tensPlace[actnumber[i]];
        }
    }
    inWords.reverse();
    for(i=0; i<inWords.length; i++) {
        finalWord+=inWords[i];
    }
    //return obStr+'  '+finalWord;
    return finalWord;
}
</script>