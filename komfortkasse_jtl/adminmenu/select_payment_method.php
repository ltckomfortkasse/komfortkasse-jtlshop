<?php
return \JTL\Shop::Container()->getDB()->query("SELECT kZahlungsart AS cWert, cName FROM tzahlungsart where nActive=1 order by kZahlungsart", 2);
