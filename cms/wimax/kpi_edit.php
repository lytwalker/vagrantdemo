<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$id = 0;
$baseId = 0;
$Kpi_name = "";
$Frequency = "";
$MechanicDowntilt = "";
$ElectronicDowntilt = "";
$Totaldowntilt = "";
$Antennatype = "";
$AntennaHeight = "";
$Cellpower = "";
$Remarks = "";

$dbUtils = new DbUtils ();

if (! (empty ( $_POST ))) {
	$id = $_POST ['id'];
	$baseId = $_POST ['baseid'];
	$Kpi_name = $_POST ['kpi_name'];
	$Frequency = $_POST ['frequency'];
	$MechanicDowntilt = $_POST ['mechanicdowntilt'];
	$ElectronicDowntilt = $_POST ['electronicdowntilt'];
	$Totaldowntilt = $_POST ['totaldowntilt'];
	$Antennatype = $_POST ['antennatype'];
	$AntennaHeight = $_POST ['antennaheight'];
	$Cellpower = $_POST ['cellpower'];
	$Remarks = $_POST ['remarks'];
	
	if ($id > 0) {
		$res = $dbUtils->InsertUpdateQuery ( " UPDATE kpis SET
				`BaseStationId` = '$baseId' , 
				`Name` = '$Kpi_name' , 
				`Frequency` = '$Frequency' , 
				`MechanicDowntilt` = '$MechanicDowntilt' , 
				`ElectronicDowntilt` = '$ElectronicDowntilt' , 
				`TotalDowntilt` = '$Totaldowntilt' , 
				`AntennaType` = '$Antennatype' , 
				`AntennaHeight` = '$AntennaHeight' , 
				`CellPower` = '$Cellpower' , 
				`Remarks` = '$Remarks'", $id );
	} else {
		$res = $dbUtils->InsertUpdateQuery ( "INSERT INTO kpis (`BaseStationId`,`Name`,`Frequency`,`MechanicDowntilt`,`ElectronicDowntilt`,`TotalDowntilt`,`AntennaType`,`AntennaHeight`,`CellPower`,`Remarks`) 
	        VALUES ($baseId,'$Kpi_name','$Frequency','$MechanicDowntilt','$ElectronicDowntilt','$Totaldowntilt','$Antennatype','$AntennaHeight','$Cellpower','$Remarks')" );
	}
	
	//get base station name
	$res_temp = getBaseStationKpis($baseId);
	if ($res_temp !== false) {
		while ( $row = $res_temp->fetch_assoc () ) {
			$base_name = $row ['base_name'];
			break;
		}
	}
	
	//check if saved 
	if ($res > 0) {
		$id = $res;
		$msg = "<div class='alert alert-success alert-dismissible fade in'>Successfully saved</div>";
		if (!isset ( $_POST ['save'] )) {
			header ( "Location: /cms/wimax/kpis.php?baseid=$baseId" );
			exit ();
		}
	} else {
		$msg = "<div class='alert alert-danger alert-dismissible fade in'>Failed to save " . $res."</div>";
	}
} elseif (! (empty ( $_GET ))) {
	if (! (empty ( $_GET ["baseid"] )) || $baseId > 0) {
		$baseId = $_GET ['baseid'];
		
		$res = getBaseStationKpis ( $_GET ['baseid'] );
		if ($res !== false) {
			while ( $row = $res->fetch_assoc () ) {
				$base_name = $row ['base_name'];
				break;
			}
		}
	} else {
		header ( "Location:/cms/wimax" );
		exit ();
	}
	
	if (! (empty ( $_GET ["id"] ))) {
		$id = $_GET ["id"];
		$res = getKpi ( $id );
		
		while ( $row = $res->fetch_assoc () ) {
			$id = $row ['Id'];
			$baseId = $row ['BaseStationId'];
			$Kpi_name = $row ['Name'];
			$Frequency = $row ['Frequency'];
			$MechanicDowntilt = $row ['MechanicDowntilt'];
			$ElectronicDowntilt = $row ['ElectronicDowntilt'];
			$Totaldowntilt = $row ['TotalDowntilt'];
			$Antennatype = $row ['AntennaType'];
			$AntennaHeight = $row ['AntennaHeight'];
			$Cellpower = $row ['CellPower'];
			$Remarks = $row ['Remarks'];
		}
	}
}

?>
<div class="right_col" role="main">
	<h3><?= ($Kpi_name != "" ? "Edit KPI ".$Kpi_name : "Add KPI") ." ".$base_name." Base Station" ?></h3>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<?= isset($msg)? $msg : "" ?>
				<form method="post" class="form-horizontal form-label-left">
					<div class="kpiname_group">
						<input type="hidden" name="id" value="<?= $id; ?>"> <input
							type="hidden" name="baseid" value="<?= $baseId; ?>">
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input required="true" type="text" name="kpi_name"
								value="<?= $Kpi_name; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Frequency</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text" name="frequency"
								value="<?= $Frequency; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Mechanic Down Tilt</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="mechanicdowntilt" value="<?= $MechanicDowntilt; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Electronic Down Tilt</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="electronicdowntilt" value="<?= $ElectronicDowntilt; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Down Tilt</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="totaldowntilt" value="<?= $Totaldowntilt; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Antenna Type</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text" name="antennatype"
								value="<?= $Antennatype; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Antenna Height</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="antennaheight" value="<?= $AntennaHeight; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cell Power</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text" name="cellpower"
								value="<?= $Cellpower; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Remarks</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text" name="remarks"
									value="<?= $Remarks; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
					</div>
					
					<div class="ln_solid"></div>					
					
					<div class="btn_row col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<a href="kpis.php?baseid=<?= $baseId ?>"><i class="fa fa-arrow-circle-left"></i> Back</a> 
						<span class="pull-right"><input
							class="btn btn-primary" type="submit" name="save" value="Save" /> <input
							class="btn btn-primary" type="submit" name="save_exit"
							value="Save & Exit" />
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
<?php
include '../shared/footer.php';
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    