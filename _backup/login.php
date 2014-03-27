<?PHP
require_once __DIR__ . '/dbConnect.php';
session_start($_POST['phpsessid']);
$_SESSION['logged']=false;
$db=new dbConnect();
?>
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
<?PHP 
$username=$_POST['username'];
$password=$_POST['password'];

$q='SELECT * FROM users WHERE username="'.$username.'" AND password="'.md5($password).'"';
$rs=mysql_query($q);
if($rs==FALSE)
	die("Error: ".mysql_error());

if($row=mysql_fetch_array($rs))
{
	echo "Dobrodosao ".$row['username']."sa id-jem ".$row['id']."<br />";
	$_SESSION['logged']=true;
	$_SESSION['userid']=$row['id'];
	$_SESSION['username']=$row['username'];
	echo "Preusmjeravam...";
	//header('Location: http://localhost/antikvarijat/index.php');
	echo "<script>redirectToHomepage();</script>";
}
else
{
	$_SESSION['logged']=false;
	echo "Neispravna kombinacija korisnickog imena i lozinke";
}

?>