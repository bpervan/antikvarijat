<?php 
	
	require_once __DIR__ . '/dbConnect.php';
	
	class izvozXML
	{
		public static function izveziXML($f)
		{

			
			
			//XML zaglavlje
			fwrite($f, '<?xml version="1.0" encoding="utf-8"?>');
			fwrite($f, '<antikvarijat>');
	  
			// Entiteti knjiga
			$q = "select * from knjiga";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			fwrite($f, '<knjiga>');
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<entitet>');
	    			fwrite($f,  "<title>" . $line['title'] . "</title>");
	    			fwrite($f,  "<author>" . $line['author'] . "</author>");
    				fwrite($f,  "<publisher>" . $line['publisher'] . "</publisher>");
					fwrite($f,  "<type>" . $line['type'] . "</type>");
					fwrite($f,  "<language>" . $line['language'] . "</language>");
					fwrite($f,  "<date>" . $line['date'] . "</date>");
					fwrite($f,  "<sifraKnjige>" . $line['sifraKnjige'] . "</sifraKnjige>");
					fwrite($f,  "<description>" . $line['description'] . "</description>");
	  			fwrite($f, '</entitet>');
			}			
	  		fwrite($f, '</knjiga>');
			
			//Podatci o pretragama
			$q = "select * from pretraga";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			fwrite($f, '<pretrage>');
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<pretraga>');
	    			fwrite($f,  "<id>" . $line['id'] . "</id>");
	    			fwrite($f,  "<kljucPretrage>" . $line['kljucPretrage'] . "</kljucPretrage>");
    				fwrite($f,  "<searchString>" . $line['searchString'] . "</searchString>");
	  			fwrite($f, '</pretraga>');
			}
			fwrite($f, '</pretrage>');
			
			//Primjerci knjiga
			$q = "select * from primjerakknjige";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			fwrite($f, '<primjerakknjige>');
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<primjerak>');
	    			fwrite($f,  "<sifraPrimjerka>" . $line['sifraPrimjerka'] . "</sifraPrimjerka>");
	    			fwrite($f,  "<sifraKnjige>" . $line['sifraKnjige'] . "</sifraKnjige>");
    				fwrite($f,  "<sifraUnosa>" . $line['sifraUnosa'] . "</sifraUnosa>");
					fwrite($f,  "<nabavnaCijena>" . $line['nabavnaCijena'] . "</nabavnaCijena>");
					fwrite($f,  "<datumProdaje>" . $line['datumProdaje'] . "</datumProdaje>");
					fwrite($f,  "<vrijemeProdaje>" . $line['vrijemeProdaje'] . "</vrijemeProdaje>");
					fwrite($f,  "<prodajnaCijena>" . $line['prodajnaCijena'] . "</prodajnaCijena>");
	  			fwrite($f, '</primjerak>');
			}
			fwrite($f, '</primjerakknjige>');
			
			//Podaci o unosima u bazu
			$q = "select * from unos";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			fwrite($f, '<unosi>');
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<unos>');
	    			fwrite($f,  "<sifraUnosa>" . $line['sifraUnosa'] . "</sifraUnosa>");
	    			fwrite($f,  "<creator>" . $line['creator'] . "</creator>");
    				fwrite($f,  "<datumUnosa>" . $line['datumUnosa'] . "</datumUnosa>");
					fwrite($f,  "<vrijemeUnosa>" . $line['vrijemeUnosa'] . "</vrijemeUnosa>");
	  			fwrite($f, '</unos>');
			}
			fwrite($f, '</unosi>');
			
			//Korisnici
			$q = "select * from users";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			fwrite($f, '<users>');
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<user>');
	    			fwrite($f,  "<id>" . $line['id'] . "</id>");
	    			fwrite($f,  "<username>" . $line['username'] . "</username>");
    				fwrite($f,  "<password>" . $line['password'] . "</password>");
	  			fwrite($f, '</user>');
			}
			fwrite($f, '</users>');
			
			//XML podnozje
			fwrite($f, '</antikvarijat>');
		}
		
	}
?>