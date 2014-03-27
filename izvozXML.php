<?php 
	
	require_once __DIR__ . '/dbConnect.php';
	
	class izvozXML
	{
		public static function izveziXML($f)
		{				
			//XML zaglavlje
			fwrite($f, '<?xml version="1.0" encoding="utf-8"?>');

			fwrite($f, '
			<!DOCTYPE rdf:RDF PUBLIC "-//DUBLIN CORE//DCMES DTD 2002/07/31//EN"
    		"http://dublincore.org/documents/2002/07/31/dcmes-xml/dcmes-xml-dtd.dtd">
			<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         	xmlns:dc="http://purl.org/dc/elements/1.1/">
			');

			//Primjerci knjiga
			$q = "select primjerakknjige.sifraPrimjerka,knjiga.* from primjerakknjige join knjiga on knjiga.sifraknjige=primjerakknjige.sifraknjige where prodajnaCijena=0";
			$rs = mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
			}
			while ($line = mysql_fetch_assoc($rs) ) {
	  			fwrite($f, '<rdf:Description rdf:about="http://dublincore.org/">');
	    			fwrite($f,  "<sifraPrimjerka>" . $line['sifraPrimjerka'] . "</sifraPrimjerka>");
	    			fwrite($f,  "<sifraKnjige>" . $line['sifraKnjige'] . "</sifraKnjige>");
					fwrite($f,  "<dc:title>".$line['title']. "</dc:title>");
					fwrite($f,  "<dc:type>".$line['type']. "</dc:type>");
					fwrite($f,  "<dc:publisher>".$line['publisher']. "</dc:publisher>");
					fwrite($f,  "<dc:language>".$line['language']. "</dc:language>");
					fwrite($f,  "<dc:description >".$line['description']. "</dc:description>");
					fwrite($f,  "<dc:date>".$line['date']. "</dc:date>");
					fwrite($f,  "<dc:author>".$line['author']. "</dc:author>");
					
	  			fwrite($f, '</rdf:Description>');
			}

			fwrite($f, '</rdf:RDF>');
		}
		
	}
?>