<?

/*
 * Resultaat pagina van de gecreeerde test
 *
 *
 * Option fields:
 *
 * Scan
 *
 * User
 *
 * This will generate the results
 *
 */

/*
 * first we generate the scan and user options.
 * Get them from the results table
 *
 */
// start the engine
$data = '';
$sqlEngine = new ssa_scan_SqlScanResults($data);

// get the scan list
$scanList = $sqlEngine -> getScanList();
?>
<style>
#wrap {
    width:95%;
}
#ScanResultHeader{
    
    padding: 20px;
    
}    
.ScanResultMain {
    float:left;
    width:45%;
    margin:10px;
}

</style>


<div id="wrap">
	<div id="load"></div>
	<div style="background:#f9f9f9;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
		<div id="icon-users" class="icon32">
			<br/>
		</div>
		<h2> Resultaat overzicht van scans</h2>
	</div>
	
	
	<div id='ScanResultHeader'>
	     <form action="" method="POST">
	         
		<select name='selectScanId' id='selectScanId' >
		    <option value=''>Selecteer Scan</option>
			<?
            foreach ($scanList as $scan) {
            
            $x = "<option value='{$scan->id}'>{$scan->naam}</option>";
            echo $x;
            }
			?>
		</select>
		
		<select name='selectUser1' id='1' class='selectUserId' style='display:none'>
            <option value=''>Selecteer de gebruiker</option>
            <option value='all'>Alle gebruikers</option>
        </select>

        <select name='selectUser2' id='2' class='selectUserId' style='display:none'>
            <option value=''>Selecteer de gebruiker</option>
            <option value='all'>Alle gebruikers</option>
        </select>
        
       
            <button type='submit' name='export' class='button' value='p'>PDF</button>
            <button type='submit' name='export' class='button' value='e'>EXCEL</button>
            <button type='submit' name='export' class='button' value='c'>MAILLIJST</button>
        </form>
        
	</div>
	
	
	<div class="ScanResultWrap" id="">
	    <div class='ScanResultMain' id='ScanResultMain_1'></div>
        <div class='ScanResultMain' id='ScanResultMain_2'></div>
	</div>
	
	<div id="ScanResultFooter">
        
    </div>
	
</div>
