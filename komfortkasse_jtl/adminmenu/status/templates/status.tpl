<style>
.for select {
	width: 200px;
}

.col {
	margin-left: 20px;
	float: left;
}
</style>

<div id="status">
	
	{if $unconfigured}
		<h3>Ihr JTL-Shop ist noch nicht mit Komfortkasse verbunden.</h3>
		<p>
		Wenn Sie die Einstellungen vorgenommen haben, melden Sie sich im <a target="_blank" href="https://komfortkasse.eu/os/sec/login">Komfortkasse H&auml;ndlerbereich</a> an und stellen Sie die Verbindung her.
		</p>
	{else}
		Die Verbindung zum Shop wurde hergestellt.	
	{/if}

</div>