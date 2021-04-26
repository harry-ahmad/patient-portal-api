<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
	.pageHeading{
		text-align:center
	}
	.innerMainHeadings{
		background-color: #112d43;
		border-color: #112d43;
		border-radius:3px;
		font-weight: 700;
		padding: 2px 8px;
		cursor: pointer;
		font-size: 14px;
		color: #ffffff;
		margin-top:5px;
	}
	.innerSubHeadings{
		font-size:12px;
		color: #11c4d8;
		margin:5px 0px;
	}
	.dataRows{
		border-bottom:1px dotted #888888;
		padding:2px 0px;
	}
	.dataDivsLabels{
		word-wrap:break-word;
		padding-right:3px;
		color: #337ab7;
		width:110px;
		float:left;
	}
	.dataDivs{
		word-wrap: break-word;
		padding-right:3px;
		width: 110px;
		float: left;
	}
	.dataDivslarge {
		word-wrap:break-word;
		text-align:justify;
		padding-right:2px;
		width:563px;
		float:left;
	}
	.medicalHXInnerDiv{
		margin-left:50px;
	}
	.medicalHXInnerDiv p{
		margin:3px 0px;
	}
	table {
		font-family:'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif;
		border-spacing:0px;	
		font-size:10px;
	}
	.messagePortions{
		border: 1px solid #d8e2e6;
		margin: 0px 0px 10px 0px;
		background-color: #fff;
		border-radius: 3px;
		padding:5px;
		width:100%;
	}	
	.reportDateTime{
		color: #adb6ba; 
		width:500px;
		float:left;
		margin:0px;
	}
	.reportmessageType{
		font-weight:bold;
		text-align:right;
		font-size:13px;
		color: #46b3b4;
		float: left;
		width:165px;
	}
	.reportMsgSubject{
		margin:5px 0px;
		font-size:13px;
		color: #337ab7;
		margin:0px;
		clear:both;
	}
	.reportMsgBody{
		text-align: justify;
		color: #8cabb8;
	}
</style>
<div style="width:680px;font-family:'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif;font-size:10px;">
    <div class="topBar" style="width:100%;text-align:center;font-size:13px;"><h2><?php echo $username; ?> Demographic Report</h2></div>
    <div class="innerMainHeadings"><h3 style="margin:0px;">BLOOD PRESSURE</h3></div>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:10px; border:1px solid #888888; width: 100%;">
        <thead>
            <tr>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:70px;">Date</th>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:250px;">Systolic</th>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:70px;">Diastolic</th>                        
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($measurements) && $measurements <> "")
                {
                    foreach($measurements as $row) 
                    {
                        //echo "<prte>";print_r($row);                    
            ?>
                        <tr>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo strtoupper($row['date']);  ?></td>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo $row['bps'];  ?> </td>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo $row['bpd'];  ?></td>                                                
                        </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
    <div class="innerMainHeadings"><h3 style="margin:0px;">PULSE</h3></div>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:10px; border:1px solid #888888; width: 100%;">
        <thead>
            <tr>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:70px;">Date</th>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:250px;">Pulse</th>                                       
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($measurements) && $measurements <> "")
                {
                    foreach($measurements as $row) 
                    {
                        //echo "<prte>";print_r($row);                    
            ?>
                        <tr>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo strtoupper($row['date']);  ?></td>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo $row['pulse'];  ?> </td>                                             
                        </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
    <div class="innerMainHeadings"><h3 style="margin:0px;">WEIGHT</h3></div>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:10px; border:1px solid #888888; width: 100%;">
        <thead>
            <tr>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:70px;">Date</th>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:250px;">Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($bmi) && $bmi <> "")
                {
                    foreach($bmi as $row) 
                    {
                        //echo "<prte>";print_r($row);                    
            ?>
                        <tr>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo strtoupper($row['date']);  ?></td>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo $row['bps'];  ?> </td>
                        </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
    <div class="innerMainHeadings"><h3 style="margin:0px;">MEDICAL</h3></div>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:10px; border:1px solid #888888; width: 100%;">
        <thead>
            <tr>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:250px;">Diagnose</th>                        
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($medical) && $medical <> "")
                {
                    foreach($medical as $row) 
                    {
                        //echo "<prte>";print_r($row);                    
            ?>
                        <tr>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo strtoupper($row['diagnose']);  ?></td>                                                
                        </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
    <div class="innerMainHeadings"><h3 style="margin:0px;">ALLERGY</h3></div>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:10px; border:1px solid #888888; width: 100%;">
        <thead>
            <tr>
                <th style="border-right:1px solid #888888; padding:2px 3px; font-size: 11px; color: #337ab7; width:250px;">Allergy</th>                        
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($allergy) && $allergy <> "")
                {
                    foreach($allergy as $row) 
                    {
                        //echo "<prte>";print_r($row);                    
            ?>
                        <tr>
                            <td style="border-right:1px solid #888888; border-top:1px solid #888888; padding:2px 3px; font-size: 10px;"><?php echo strtoupper($row['name']);  ?></td>                                                
                        </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
</div>
<?php
// if(isset($measurements) && $measurements <> "")
// {
//     foreach($measurements as $row) 
//     {
// print_r($row['date']);
//     }
// }
// print_r($medical);
// print_r($allergy);
// print_r($bmi); -->