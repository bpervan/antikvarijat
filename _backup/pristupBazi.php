<?PHP
	require_once __DIR__ . '/knjiga.php';
	require_once __DIR__ . '/zaposlenik.php';
	require_once __DIR__ . '/dbConnect.php';
	require_once __DIR__ . '/pretraga.php';
	require_once __DIR__ . '/izvozXML.php';
	
	class pristupBazi
	{
		public static function nadjiKnjiguAdvanced($title,$author,$publisher,$language,$type)
		{
			$temp=array();
			$q='select * from knjiga where ';
			if($title!=""){
				$q=$q.'and title="'.$title.'" ';
			}
			if($author!=""){
				$q=$q.'and author="'.$author.'" ';
			}
			if($publisher!=""){
				$q=$q.'and publisher="'.$publisher.'" ';
			}
			if($language!=""){
				$q=$q.'and language="'.$language.'" ';
			}
			if($type!=""){
				$q=$q.'and type="'.$type.'" ';
			}		
			$q=str_replace("where and", "where ", $q);
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return 0;
			}		
			while($row=mysql_fetch_array($rs)){
				$knjiga=new Knjiga($row['title'],$row['author'],$row['description'],$row['publisher'],$row['date'],$row['language'],$row['type'],$row['sifraKnjige']);
				array_push($temp,$knjiga);				
			}
			return $temp;		
		}
		public static function nadjiKnjigu($kljucPretrage,$kriterijPretrage) //Trazi entitet, ako entitet postoji vraca objekt tipa knjiga
		{
			$temp=array();
			$q='SELECT * FROM knjiga where '.$kljucPretrage.'="'.$kriterijPretrage.'"';
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return 0;
			}
			
			while($row=mysql_fetch_array($rs)){
				$knjiga=new Knjiga($row['title'],$row['author'],$row['description'],$row['publisher'],$row['date'],$row['language'],$row['type'],$row['sifraKnjige']);
				array_push($temp,$knjiga);				
			}
			return $temp;			
		}
		
		public static function nadjiKorisnika($username,$password)
		{
			$q='SELECT * FROM trgovac WHERE KorisnickoIme="'.$username.'" AND Zaporka="'.$password.'"';
			$rs=mysql_query($q);
			if($row=mysql_fetch_array($rs)){
				return $row;
			}			
			else {
				return 0;
			}
		}
		public static function brojKnjigaNaSkladistu($id)
		{
			$q='SELECT count(sifraKnjige) as broj FROM primjerakknjige where sifraKnjige="'.$id.'" and prodajnaCijena=0';
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return 0;
			}
			$row=mysql_fetch_array($rs);
			return $row['broj'];
		}
		public static function dohvatiKnjiguPoSifri($id)
		{
			$q='SELECT * FROM knjiga where sifraKnjige="'.$id.'"';
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return 0;
			}
			$row=mysql_fetch_array($rs);		
			$knjiga=new Knjiga($row['title'],$row['author'],$row['description'],$row['publisher'],$row['date'],$row['language'],$row['type'],$row['sifraKnjige']);
			return $knjiga;			
		}
		public static function generirajSifru($param) //vraca generiranu sifru za entitet, unos ili primjerak ovisno o parametru
		{
			switch($param)
			{
				case "ENTITET":
					$q='SELECT MAX(sifraKnjige) as maksimalno FROM knjiga';
					break;
				case "UNOS":
					$q='SELECT MAX(sifraUnosa) as maksimalno FROM unos';
					break;
				case "PRIMJERAK":
					$q='SELECT MAX(sifraPrimjerka) as maksimalno FROM primjerakknjige';
					break;
				default:
					return 0;
					break;			
			}	
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return 0;
			}
			
			if($row=mysql_fetch_array($rs)){
				$temp=$row['maksimalno'];
			}
			
			return $temp+1;
			
		}
		
		public static function nadjiEntitet(knjiga $knjiga)
		{
			$q='SELECT * FROM knjiga WHERE title="'.$knjiga->getTitle().'" and author="'.$knjiga->getAuthor().'" and type="'.$knjiga->getType().'" and publisher="'.$knjiga->getPublisher().'" and language="'.$knjiga->getLanguage().'"';
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return -1;
			}
			
			if($row=mysql_fetch_array($rs)){
				return $row['sifraKnjige'];
			}
			else{
				return -1;
			}
		}
		public static function upisiSearchUBazu($kljuc,$searchString)
		{
			$q='INSERT INTO pretraga values("null'.'","'.$kljuc.'","'.$searchString.'")';		
			mysql_query($q);		
		}
		
		public static function dohvatiSearchCloud()
		{
			$temp=array();
			$i=0;
			$q='SELECT * FROM pretraga ORDER BY `pretraga`.`id` desc LIMIT 25';
			$rs=mysql_query($q);
			while($row=mysql_fetch_array($rs))
			{
				$k=new pretraga($row['id'],$row['kljucPretrage'],$row['searchString']);
				array_push($temp,$k);
				
			}
			return $temp;
			
		}

		public static function dohvatiZadnjeUpise()
		{
			$temp=array();
			$i=0;
			$q='select knjiga.* from knjiga join primjerakknjige on primjerakknjige.sifraKnjige=knjiga.sifraKnjige join unos on primjerakknjige.sifraUnosa=unos.sifraUnosa order by unos.datumUnosa,unos.vrijemeUnosa desc LIMIT 25';
			$rs=mysql_query($q);
			while($row=mysql_fetch_array($rs))
			{
				$knjiga=new knjiga(
				$row['title'],
				$row['author'],
				$row['description'],
				$row['publisher'],
				$row['date'],
				$row['language'],
				$row['type'],
				$row['sifraKnjige']
				);
				array_push($temp,$knjiga);
				
			}
			return $temp;
		}
		public static function vratiSadrzajSkladista()
		{
			$f=fopen("izvoz.xml","w+");
			izvozXML::izveziXML($f);
			fclose($f);
		}
		
		public static function promijeniLozinku($userId,$newpassword)
		{
			$q='UPDATE users SET password="'.md5($newpassword).'" where id='.$userId;
			$rs=mysql_query($q);
			if($rs==FALSE){
				die(mysql_error());
			}
		}
		
		public static function provjeraStareLozinke($lozinka)
		{
			$q='select password from users where id='.$_SESSION['userid'];
			$rs=mysql_query($q);
			if($rs==FALSE){
				die(mysql_error());
			}
			
			if($row=mysql_fetch_array($rs)){
				if($row['password']==md5($lozinka))
					return TRUE;
				else {
					return FALSE;
				}
			}
			
		}
		public static function dodajKnjigu(knjiga $knjiga) //ovo ce bit oddaj entitet, fali mi dodaj Primjerak
		{
			$q='INSERT INTO knjiga values("'.$knjiga->getTitle().'","'.
			$knjiga->getType().'","'.
			$knjiga->getPublisher().'","'.
			$knjiga->getLanguage().'","'.
			$knjiga->getDescription().'","'.
			$knjiga->getDate().'","'.
			$knjiga->getSifraKnjige().'","'.
			$knjiga->getAuthor().'")';
			
			mysql_query($q);
		}
		public static function izmijeniKnjigu(knjiga $knjiga)
		{//UPIT NE VALJA
			$q='UPDATE knjiga SET title="'.$knjiga->getTitle().'", type="'.$knjiga->getType().
			'", publisher="'.$knjiga->getPublisher().'",language="'.$knjiga->getLanguage().'",description="'.$knjiga->getDescription().
			'",author="'.$knjiga->getAuthor().'" where sifraKnjige='.$knjiga->getSifraKnjige();
			$rs=mysql_query($q);
			if($rs===FALSE){
				echo $q."<br />";	
				die(mysql_error());
				return -2;
			}
			
			
		}		
		public static function dodajUnos($sifraunosa)
		{
			$datum=date("Y-m-d");
			$vrijeme=date("G:i:S");
			$q='INSERT INTO unos values("'.$sifraunosa.'","'.$_SESSION['username'].'","'.$datum.'","'.$vrijeme.'")';
			$rs=mysql_query($q);
			if($rs===FALSE){
				
				die(mysql_error());
				return -2;
			}
			
		}
		
		public static function dodajPrimjerak($sifraunosa,$sifraprimjerka,$sifraknjige,$nabavnacijena)
		{
			$q='INSERT INTO primjerakknjige (`sifraPrimjerka`,`sifraKnjige`,`sifraUnosa`,`nabavnaCijena`) values('.$sifraprimjerka.','.
			$sifraknjige.','.
			$sifraunosa.','.
			$nabavnacijena.')';
			$rs=mysql_query($q);
			if($rs===FALSE){
				die(mysql_error());
				return -2;
			}
		}
		
		public static function prodajKnjigu($sifraKnjige,$kolicina,$prodajnaCijena)
		{
			$datum=date("Y-m-d");
			$vrijeme=date("G:i:S");
			$prodajnaCijena=str_replace(',', '.',$prodajnaCijena);
			//Nadji konkretne sifre primjeraka koje ce biti prodane
			$q='select sifraPrimjerka from primjerakknjige where sifraKnjige='.$sifraKnjige.' and prodajnaCijena=0 limit '.$kolicina;
			$rs=mysql_query($q);
			if($rs===FALSE){			
				die(mysql_error());
			}
			$temp=array();
			while($row=mysql_fetch_array($rs)){
				array_push($temp,$row['sifraPrimjerka']);
			}
			
			//Prodaj konkretne sifre
			for($i=0;$i<count($temp);$i++){
				$q='update primjerakknjige set datumProdaje="'.$datum.'",vrijemeProdaje="'.$vrijeme.'", prodajnaCijena="'.$prodajnaCijena.'" where sifraPrimjerka='.$temp[$i];
				$rs=mysql_query($q);
				if($rs===FALSE){
					echo $q.'<br />';			
					die(mysql_error());
				}			
			}
			
		}
		public static function izbrisiKnjigu($sifraKnjige)
		{
			$q='delete from knjiga where sifraKnjige='.$sifraKnjige;
			$rs=mysql_query($q);
			if($rs===FALSE){
					echo $q.'<br />';			
					die(mysql_error());
				}
		}
	}
?>