<?PHP
	//Ovo je fensi
	$start=microtime(true);
	//Includes
	require_once __DIR__ . '/knjiga.php';
	require_once __DIR__ . '/zaposlenik.php';
	require_once __DIR__ . '/korisnik.php';
	require_once __DIR__ . '/dbConnect.php';
	require_once __DIR__ . '/pristupBazi.php';
	require_once __DIR__ . '/forms.php';
	require_once __DIR__ . '/menus.php';
	//Stvori session i inicijaliziraj najvaznije varijable
	session_start();
	if(!isset($_SESSION['id'])){
		$_SESSION['id']=session_id();
	}	
	
	if(!isset($_SESSION['logged'])){
		$k=new korisnik($_SESSION['id']);
	}
	else{
		if($_SESSION['logged']==TRUE){
			$z=$_SESSION['zaposlenik'];
		}
	}
	
	
	//Instanciraj forme, izbornike i stvori poveznicu na bazu podataka
	$form=new forms();
	$menu=new menus();
	$db=new dbConnect();
	
	
	
	date_default_timezone_set('Europe/Paris'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Antikvarijat - Svijet knjige</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="jquery-latest.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<script>
	function potvrdiBrisanje(sifra)
	{
		var url="index.php?action=deleteBook&sifraKnjige="+sifra;
		var r=confirm("Jeste li sigurni da zelite obrisati odabranu knjigu iz baze?")
		if (r==true){
  			window.location.href = url;
  		}
	}
	function redirectToHomepage()
	{
		var url="index.php";
		window.location.href=url;
	}
	function redirectToXML()
	{	
		var url="preuzmiXML.php";
		window.location.href=url;
	}
	
</script>
<script>
  	$(document).ready(function(){
    	$("#searchForm").validate();
  	});
  	$(document).ready(function(){
    	$("#sellForm").validate();
  	});
  	$(document).ready(function(){
    	$("#loginForm").validate();
  	});
  	$(document).ready(function(){
    	$("#advancedSearchForm").validate();
  	});
  	$(document).ready(function(){
    	$("#changePasswordForm").validate();
  	});
  	$(document).ready(function(){
    	$("#uredjivanjeDetalja").validate();
  	});
  	$(document).ready(function(){
    	$("#upisKnjige").validate();
  	});
 </script>
</head>
<body>
<div id="main_container">
	<div id="header">
    	<div class="logo"><img src="images/banner_up.png" width="429" height="102" /></div>       
    </div>
        <div class="menu">
        	<?PHP
			if($_SESSION['logged']==TRUE)
			{
				$menu->zaposlenikMeni();
			}
			else
			{
				$menu->obicniMeni();
			}	
		?>
        </div>        
    <div class="center_content">    
     	<div class="center_left">
					<?PHP 
	if(isset($_GET['action']))
		switch($_GET['action'])
		{
			case 'promijeniLozinku':
				if($_SESSION['logged']==true)
				{
					if(!isset($_POST['subaction']))
						$form->changePassword();
					else{
						if($_POST['subaction']=="passwordChanged"){
							if(!pristupBazi::provjeraStareLozinke($_POST['oldpass'])){
								echo 'Ne valja stara lozinka.<br /><a href="javascript:history.go(-1)">Pokusajte ponovo</a>';
							}
							else{
								if($_POST['newpass']!=$_POST['newpass2']){
								echo 'Nova lozinka se ne podudara.<br /><a href="javascript:history.go(-1)">Pokusajte ponovo</a>';
								}
								else {
									pristupBazi::promijeniLozinku($_SESSION['userid'],$_POST['newpass']);
									echo 'Lozinka uspješno promijenjena :)';
								}
							
							}
						}						
					}	
				}
				else
				{
					echo "Niste ulogirani ili nedovoljna razina prava";
				}
				break;
			case 'upisKnjige':
				if($_SESSION['logged']==TRUE){
					if(!isset($_GET['subaction']))
						$form->newEditBook();
					else {
						
						$datum=date("Y-m-d");//ubaci dio koji ce upisivati više primjeraka odjenom
						$nabavnacijena=str_replace(',', '.', $_GET['nabavnacijena']);
						$sifraunosa=pristupBazi::generirajSifru("UNOS");
						/////NIJE JOŠ TESTIRANO; OVO TESTIRATI ASAP OBAVEZNO!!!!!!!!
								
						$temp=new Knjiga($_GET['title'],$_GET['author'],$_GET['description'],$_GET['publisher'],$datum,$_GET['language'],$_GET['type'],0);					
						$sifraknjige=pristupBazi::nadjiEntitet($temp);					
						if($sifraknjige!=-1){ //entitet postoji, dodaj primjerak entiteta i dodaj zapis o upisu
							for($i=1;$i<=$_GET['brprimjeraka'];$i++){
								$sifraprimjerka=pristupBazi::generirajSifru("PRIMJERAK");
								pristupBazi::dodajPrimjerak($sifraunosa, $sifraprimjerka, $sifraknjige, $nabavnacijena);
							}		

						}
						else{ //entitet ne postoji, stvori novi entitet, dodaj primjerak entiteta i dodaj zapis o upisu
							$sifraknjige=pristupBazi::generirajSifru("ENTITET");
							$temp=new Knjiga($_GET['title'],$_GET['author'],$_GET['description'],$_GET['publisher'],$datum,$_GET['language'],$_GET['type'],$sifraknjige);										
							pristupBazi::dodajKnjigu($temp);
							for($i=1;$i<=$_GET['brprimjeraka'];$i++){
								$sifraprimjerka=pristupBazi::generirajSifru("PRIMJERAK");
								pristupBazi::dodajPrimjerak($sifraunosa, $sifraprimjerka, $sifraknjige, $nabavnacijena);
							}		
						
														
						}
						pristupBazi::dodajUnos($sifraunosa);
						echo "Podatci uspješno uneseni u bazu :)";
					}
				}
				else{
					echo 'Nedovoljna razina prava.';
				}
				break;
			
			case 'odjaviSe':
				session_destroy();
				$_SESSION['logged']=false;
				//header('Location: http://localhost/antikvarijat/');
				echo "<script>redirectToHomepage();</script>";
				break;
			case 'search':
				$form->searchForm();
				break;
			case 'searchPerformed':
				$temp=array();
				if(($_GET['kljuc']!="")&&($_GET['searchString']!="")){
					$key=mysql_real_escape_string($_GET['kljuc']);
					$string=mysql_real_escape_string($_GET['searchString']);
					$temp=pristupBazi::nadjiKnjigu($key, $string);
					if(!isset($_GET['subaction'])){
						pristupBazi::upisiSearchUBazu($key, $string);
					}			
					echo '<div class="title">Rezultati pretrage:</div>';
					echo '<ul class="list">';
					for($i=0;$i<count($temp);$i++){
						echo '<li><span>'.($i+1).'</span>&nbsp;';
						echo '<a href="index.php?action=details&sifraKnjige='.$temp[$i]->getSifraKnjige().'">'.$temp[$i]->getAuthor().'&nbsp;'.$temp[$i]->getTitle().'&nbsp;'.$temp[$i]->getPublisher().'</a><br />';
					}
					echo '</ul>';
					}
				else{
					echo "Nedostaje ključ ili parametar pretrage";
				}
				break;
			case 'dohvatiSadrzajBaze':
				pristupBazi::vratiSadrzajSkladista();
				//header('Location: http://localhost/antikvarijat/preuzmiXML.php');
				echo "<script>redirectToXML();</script>";
				echo "Generiram XML datoteku...<br />";
				echo "<script>redirectToHomepage();</script>";
				break;
			case 'details':
				echo '<div class="title">Detalji knjige:</div>'; //detalji knjige. TODO: dodati broj knjiga na skladištu
				$sifraKnjige=$_GET['sifraKnjige'];
				$broj=pristupBazi::brojKnjigaNaSkladistu($sifraKnjige);  //TODO: broj nekaj ne valja
				$knjiga=pristupBazi::dohvatiKnjiguPoSifri($sifraKnjige);
				$knjiga->setBrPrimjeraka($broj);
				echo '<ul class="list">';
				echo '<li><span></span>Autor:&nbsp;'.$knjiga->getAuthor().'</li><br />';
				echo '<li><span></span>Naslov:&nbsp;'.$knjiga->getTitle().'</li><br />';
				echo '<li><span></span>Izdava&#269;:&nbsp;'.$knjiga->getPublisher().'</li><br />';
				echo '<li><span></span>Vrsta:&nbsp;'.$knjiga->getType().'</li><br />';
				echo '<li><span></span>Jezik:&nbsp;'.$knjiga->getLanguage().'</li><br />';
				echo '<li><span></span>Opis:&nbsp;'.$knjiga->getDescription().'</li><br />';
				echo '<li><span></span>Broj primjeraka:&nbsp;'.$knjiga->getBrPrimjeraka().'</li><br />';
				echo '</ul>';
				break;
			case 'sellBook':
				if($_SESSION['logged']==TRUE){
					$kolicina=pristupBazi::brojKnjigaNaSkladistu($_GET['sifraKnjige']);
					if($kolicina>=1){
						if(!isset($_GET['subaction'])){
							echo '<div class="title">Popunite obrazac za prodaju:</div><br />';
							$form->sellForm();
							echo '<a href="javascript:history.go(-1)"><img src="/antikvarijat/images/arrow.png" width="75" height="80"></a>';
						}
						else{
							if($_GET['subaction']=="sell"){
								if($kolicina<$_GET['kolicina']){
									echo "Nema dovoljno knjiga na skladistu";
								}
								else{
									pristupBazi::prodajKnjigu($_GET['sifraKnjige'],$_GET['kolicina'],$_GET['prodajnaCijena']);
									echo "Knjiga je uspjesno prodana";
								}
								
							}
						}
					}
					else {
						echo "Nema dovoljno knjiga na skladistu";
					}
				}
				else{
					echo "Ccccccccc, nedovoljna razina prava ;-)";
				}
				break;
			case 'deleteBook':
				if($_SESSION['logged']==TRUE){
					pristupBazi::izbrisiKnjigu($_GET['sifraKnjige']);
					echo "Uspjesno obrisan entitet i svi primjerci knjige";
				}
				else{
					echo "Ccccccccc, nedovoljna razina prava ;-)";		
				}				
				break;
			case 'editBookDetails':
				if(isset($_GET['subaction'])){
					if($_GET['subaction']=="writedb"){
						$temp=new Knjiga($_GET['title'],$_GET['author'],$_GET['description'],$_GET['publisher'],$_GET['date'],$_GET['language'],$_GET['type'],$_GET['sifraKnjige']);
						pristupBazi::izmijeniKnjigu($temp);
						echo "Podatci uspješno izmjenjeni :)";								
					}
				}
				else{
					$temp=pristupBazi::dohvatiKnjiguPoSifri($_GET['sifraKnjige']);
					forms::editBook($temp);
					echo '<a href="javascript:history.go(-1)"><img src="/antikvarijat/images/arrow.png" width="75" height="80"></a>';				
				}
				break;
			case 'advancedSearch':
				if(!isset($_GET['subaction'])){
					$form->advancedSearchForm();
				}
				else{
					if($_GET['subaction']=="search"){
						$temp=array();
						$title=mysql_real_escape_string($_GET['title']);
						$author=mysql_real_escape_string($_GET['author']);
						$publisher=mysql_real_escape_string($_GET['publisher']);
						$language=mysql_real_escape_string($_GET['language']);
						$type=mysql_real_escape_string($_GET['type']);
						if(pristupBazi::nadjiKnjiguAdvanced($title, $author, $publisher, $language, $type)==-1){
							echo "Najmanje jedan parametar mora biti upisan";
						}
						else{
							$temp=pristupBazi::nadjiKnjiguAdvanced($title, $author, $publisher, $language, $type);
						}
						echo '<div class="title">Rezultati pretrage:</div>';
						echo '<ul class="list">';
						for($i=0;$i<count($temp);$i++){
							echo '<li><span>'.($i+1).'</span>&nbsp;';
							echo '<a href="index.php?action=details&sifraKnjige='.$temp[$i]->getSifraKnjige().'">'.$temp[$i]->getAuthor().'&nbsp;'.$temp[$i]->getTitle().'&nbsp;'.$temp[$i]->getPublisher().'</a><br />';
						}
						echo '</ul>';
						$kljuc="";
						$searchString="";
						if($_GET['title']!=""){
							$kljuc=$kljuc.',title';
							$searchString=$searchString.','.$_GET['title'];
						}
						if($_GET['author']!=""){
							$kljuc=$kljuc.',author';
							$searchString=$searchString.','.$_GET['author'];
						}
						if($_GET['publisher']!=""){
							$kljuc=$kljuc.',publisher';
							$searchString=$searchString.','.$_GET['publisher'];
						}
						if($_GET['language']!=""){
							$kljuc=$kljuc.',language';
							$searchString=$searchString.','.$_GET['language'];
						}
						if($_GET['type']!=""){
							$kljuc=$kljuc.',type';
							$searchString=$searchString.','.$_GET['type'];
						}
						$kljuc=substr($kljuc, 1);
						$searchString=substr($searchString, 1);
						if(!isset($_GET['source'])){
							pristupBazi::upisiSearchUBazu($kljuc, $searchString);
						}
					}
				}
				break;

		}
		else{
				echo'<div class="title">Zadnjih 25 pretraga</div>';
				echo'<ul class="list">';
				$temp=array();
				$temp=pristupBazi::dohvatiSearchCloud();
				
				for($i=0;$i<count($temp);$i++)
				{
					$kljuc=$temp[$i]->getKljuc();
					$searchString=$temp[$i]->getSearchString();
					if(strpos($kljuc,",")===FALSE){
						echo '<li><span>'.(count($temp)-$i).'</span>&nbsp;<a href="index.php?action=searchPerformed&subaction=frontPageLink&kljuc='.$kljuc.'&searchString='.$searchString.'">Ključ pretrage:'.$temp[$i]->getKljuc().'&nbsp;||&nbsp;Upit pretrage:'.$temp[$i]->getSearchString().'</a></li>';
					}
					else {
						$href="";
						$kljucArray=array();
						$searchStringArray=array();
						$kljucArray=explode(",",$kljuc);
						$searchStringArray=explode(",",$searchString);
						for($j=0;$j<count($kljucArray);$j++){
							$href=$href.$kljucArray[$j]."=".$searchStringArray[$j]."&";
						}
						$href=rtrim($href,"&");
						//TU BI MOZDA TREBALO VIDJETI STA SA GETOVIMA KOJIH NEMA???? E^NOTICE se baca?
						echo '<li><span>'.(count($temp)-$i).'</span>&nbsp;<a href="index.php?source=frontPage&action=advancedSearch&subaction=search&'.$href.'">Ključ pretrage:'.$temp[$i]->getKljuc().'&nbsp;||&nbsp;Upit pretrage:'.$temp[$i]->getSearchString().'</a></li>';
					}
				}
				echo'</ul>';
				echo '<div class="title">Zadnjih 25 upisa</div>';
				echo'<ul class="list">';
				$temp=array();
				$temp=pristupBazi::dohvatiZadnjeUpise();
				for($i=0;$i<count($temp);$i++)
				{				
					echo '<li><span>'.(count($temp)-$i).'</span>&nbsp;<a href="index.php?action=details&sifraKnjige='.$temp[$i]->getSifraKnjige().'">'.$temp[$i]->getAuthor().'&nbsp;||&nbsp;'.$temp[$i]->getTitle().'&nbsp;||&nbsp;'.$temp[$i]->getSifraKnjige().'</a>'.'</li>';
					
				}
				echo'</ul>';
		}
	?>
                                                   
        </div>         
        <div class="center_right">        
             
                        <div class="text_box">
                        <div class="title">Zaposlenici</div>
                        <?PHP 
							if($_SESSION['logged']==FALSE){
								$form->loginForm();
							}
							if($_SESSION['logged']==TRUE){
								echo 'Dobrodošao '.$z->getIme().' '.$z->getPrezime().'</br>';
								echo '<a href="index.php?action=odjaviSe">Odjavi se</a>';
								if(isset($_GET['action'])){
									if($_GET['action']=='details'){
										echo'<ul class="list">';
										echo '<li><span></span><a href="index.php?action=sellBook&sifraKnjige='.$_GET['sifraKnjige'].'">Prodaj knjigu</a></li>';
										echo '<li><span></span><a href="index.php?action=editBookDetails&sifraKnjige='.$_GET['sifraKnjige'].'">Uredi knjigu</a></li>';
										//echo '<li><span></span><a href="index.php?action=deleteBook&sifraKnjige='.$_GET['sifraKnjige'].'">Izbrisi knjigu</a></li>';
										echo '<li><span></span><a href="#" onclick="potvrdiBrisanje('.$_GET['sifraKnjige'].')">Izbriši knjigu</a></li>';
										echo'</ul>';											
									}
								}	
														
							}
						?>                                                   
                        </div>                        
                       
        </div>          
        <div class="clear"></div>     
    </div>       
    <div id="footer">                                              
        
        <div class="left_footer">
        	<?PHP 
        	$end=microtime(true);
			echo 'HTML kod izgeneriran za&nbsp;'.($end-$start).'&nbsp;sekundi.';
			?>
        	
        </div><!-- 
        <div class="right_footer"><a href="http://csstemplatesmarket.com"  target="_blank"><img src="images/csstemplatesmarket.gif" border="0" alt="" title="" /></a>
        </div>-->      
    </div>   
</div>
<!-- end of main_container -->
</body>
</html>