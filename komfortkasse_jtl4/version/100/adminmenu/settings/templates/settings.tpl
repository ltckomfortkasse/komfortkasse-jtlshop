<style>
.for select {
	width: 200px;
}

.col {
	margin-left: 20px;
	float: left;
}
</style>

<div id="settings">
    <form name="komfortkassen_settings" method="POST" enctype="multipart/form-data">
        <div class="item">
            <div class="name">
                <label>Export Bestellungen</label>
            </div>
            <div class="for">
                <input type="checkbox" name="set[export]" value="1" {$export_checked}/>&nbsp;<label>Export aktiv</label>
                <div title="Export von Bestellungen zu Komfortkasse aktiv" ref="1" class="help"></div>
            </div>
        </div>
        <div class="item">
            <div class="name">
                <label>Update von Bestellungen</label>
            </div>
            <div class="for">
                <input type="checkbox" name="set[update]" value="1" {$update_checked}/>&nbsp;<label>Update aktiv</label>
                <div title="Änderung der Bestellungen aktivieren" ref="1" class="help"></div>
            </div>
        </div>
       
        <hr>
        <div class="row">
	        <div class="col">
		        <h3>Vorkasse</h3>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status offen</label>
		            </div>
		            <div class="for">
		                <select name="set[status_open][]" size=5 multiple="multiple">
		                	{$status_open_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status bezahlt</label>
		            </div>
		            <div class="for">
		                <select name="set[status_paid][]" size=5 >
		                	{$status_paid_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
		        <div class="item">
		            <div class="name">
		                <label>Status Storno</label>
		            </div>
		            <div class="for">
		                <select name="set[status_cancelled][]" size=5 >
		                	{$status_cancelled_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
				<div class="item">
		            <div class="name">
		                <label>Zahlungsmethoden (Vorkasse)</label>
		            </div>
		            <div class="for">
		                <select name="set[payment_methods][]" size=5 multiple="multiple">
		                	{$payment_methods_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
	       </div>
	       
        	<div class="col">
		        <h3>Rechnung</h3>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status offen</label>
		            </div>
		            <div class="for">
		                <select name="set[status_open_invoice][]" size=5 multiple="multiple">
		                	{$status_open_invoice_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status bezahlt</label>
		            </div>
		            <div class="for">
		                <select name="set[status_paid_invoice][]" size=5 >
		                	{$status_paid_invoice_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
		        <div class="item">
		            <div class="name">
		                <label>Status Storno</label>
		            </div>
		            <div class="for">
		                <select name="set[status_cancelled_invoice][]" size=5 >
		                	{$status_cancelled_invoice_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
				<div class="item">
		            <div class="name">
		                <label>Zahlungsmethoden (Rechnung)</label>
		            </div>
		            <div class="for">
		                <select name="set[payment_methods_invoice][]" size=5 multiple="multiple">
		                	{$payment_methods_invoice_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
	       </div>
	       
	       <div class="col">
		        <h3>Nachnahme</h3>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status offen</label>
		            </div>
		            <div class="for">
		                <select name="set[status_open_cod][]" size=5 multiple="multiple">
		                	{$status_open_cod_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		        
		        <div class="item">
		            <div class="name">
		                <label>Status bezahlt</label>
		            </div>
		            <div class="for">
		                <select name="set[status_paid_cod][]" size=5 multiple="multiple">
		                	{$status_paid_cod_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
		        <div class="item">
		            <div class="name">
		                <label>Status Storno</label>
		            </div>
		            <div class="for">
		                <select name="set[status_cancelled_cod][]" size=5 multiple="multiple">
		                	{$status_cancelled_cod_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
		
				<div class="item">
		            <div class="name">
		                <label>Zahlungsmethoden (Nachnahme)</label>
		            </div>
		            <div class="for">
		                <select name="set[payment_methods_cod][]" size=5 multiple="multiple">
		                	{$payment_methods_cod_options}
		                </select>
		                <div title="" ref="2" class="help"></div>
		            </div>
		        </div>
	       </div>
		</div>
		
		{if $unconfigured}
		<br/>
		<h3>Ihr JTL-Shop ist noch nicht mit Komfortkasse verbunden.</h3>
		<p>
		Wenn Sie die Konfiguration gespeichert haben, melden Sie sich im <a target="_blank" href="https://komfortkasse.eu/os/sec/login">Komfortkasse H&auml;ndlerbereich</a> and und stellen Sie die Verbindung her.
		</p>
		<br/>
		{/if}
		
		<div class="save_wrapper">
            <input type="submit" class="button orange" value="Speichern">
        </div>
    </form>
</div>