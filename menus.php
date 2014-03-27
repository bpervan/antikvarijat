<?PHP
class menus
{
	function zaposlenikMeni()
	{
		echo'
		<ul>
		<li><a href="index.php">Pocetna</a></li>
        <li><a href="index.php?action=search">Pretraga</a></li>
        <li><a href="index.php?action=advancedSearch">Napredna pretraga</a></li>
        <li><a href="index.php?action=upisKnjige">Unos knjige</a></li>
        <li><a href="index.php?action=dohvatiSadrzajBaze">Izvoz baze</a></li>
        <li><a href="index.php?action=promijeniLozinku">Promijena lozinke</a></li>
        <li><a href="index.php?action=odjaviSe">Odjava</a></li>
		</ul>
		';	
	}

	function obicniMeni()
	{
		echo'
		<ul>
		<li><a href="index.php">Pocetna</a></li>
		<li><a href="index.php?action=search">Pretraga</a></li>
		<li><a href="index.php?action=advancedSearch">Napredna pretraga</a></li>
		</ul>
		';
	}
}
?>