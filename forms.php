<?PHP
class forms
{
	function sellForm()
	{
		echo'
		<form name="sellForm" id="sellForm" action="index.php" method="get">
		<div class="subtitle">*Prodajna kolicina:</div><br />
		 <select name="kolicina" id="prodajnaKolicina">
    		<option value="1">1</option>
    		<option value="2">2</option>
    		<option value="3">3</option>
    		<option value="4">4</option>
    		<option value="5">5</option>
    		<option value="6">6</option>
    		<option value="7">7</option>
  		</select> <br />
		<div class="subtitle">*Prodajna cijena:</div><br />
		<input type="text" name="prodajnaCijena" class="required number"/><br />
		<input type="submit" value="Prodaj" /><br />
		<input type="hidden" name="action" value="sellBook" />
		<input type="hidden" name="subaction" value="sell" />
		<input type="hidden" name="sifraKnjige" value="'.$_GET['sifraKnjige'].'" />
		</form>
		* - Obavezno polje
		';
		
	}
	
	function loginForm()
	{
		echo'
		<form name="loginForm" id="loginForm"action="login.php" method="post">
		<div class="login_form_row">
        <label class="login_label">Username:</label><input type="text" name="username" class="login_input" />
        </div>                           
        <div class="login_form_row">
        <label class="login_label">Password:</label><input type="password" name="password" class="login_input" />
        </div>                                     
        <input type="image" src="images/login.gif" class="login" />
		<input type="hidden" name="phpsessid" value="'.$_SESSION['id'].'" />
		<input type="hidden" name="returnurl" value="'."http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" />
		</form>
		';
	}

	function searchForm()
	{
		echo'
		<form name="searchForm" id="searchForm"method="get" action="index.php">
  		<select name="kljuc" id="kljuc">
    		<option value="author">Autor</option>
    		<option value="title">Naslov</option>
    		<option value="type">Vrsta</option>
    		<option value="publisher">Izdava&#269;</option>
    		<option value="language">Jezik</option>
    		<option value="description">Opis</option>
    		<option value="sifraKnjige">&#352;ifra</option>
  		</select>
  		<input type="hidden" name="action" value="searchPerformed">
  		<input type="text" name="searchString" class="required" /><br />
  		<input type="submit" value="Pretra&#382;i" class="submit"/>
		</form>

		';	
	}

	function advancedSearchForm()
	{
		echo '
		<form name="advancedSearchForm" id="advancedSearchForm" method="get" action="index.php?action=advancedSearch&subaction=search">
		<table width="416" height="291" border="0">
  			<tr>
    			<td width="122">Naslov</td>
    			<td width="284"><input type="text" name="title" id="title" /></td>
 			</tr>
  			<tr>
   			 <td>Autor</td>
   			 <td><input type="text" name="author" id="author" /></td>
  			</tr>
  			<tr>
    			<td>Izdava&#269;</td>
    			<td><input type="text" name="publisher" id="publisher" /></td>
  			</tr>
  			<tr>
    			<td>Jezik</td>
    			<td><input type="text" name="language" id="language" /></td>
  			</tr>
  			<tr>
    			<td>Vrsta</td>
    			<td><input type="text" name="type" id="type" /></td>
  			</tr>
  			<tr>
 			   	<td>&nbsp;</td>
   			 	<td>&nbsp;</td>
 			 </tr>
		</table>
		<input type="submit" value="Pretra&#382;i" />
		<input type="hidden" name="action" value="advancedSearch" />
		<input type="hidden" name="subaction" value="search" />
		</form>
		';
		
	}
	function changePassword()
	{
		echo'
		<form name="changePasswordForm" id="changePasswordForm" action="index.php?action=promijeniLozinku" method="post">
		<p>Stara lozinka:</p><br />
		<input type="password" name="oldpass" class="required"/><br />
		<p>Nova lozinka:</p><br />
		<input type="password" name="newpass" class="required"/><br />
		<p>Ponovite novu lozinku:</p><br />
		<input type="password" name="newpass2" class="required"/><br />
		<input type="submit" value="Promijeni" /><br />
		<input type="hidden" name="phpsessid" value="'.$_SESSION['id'].'" />
		<input type="hidden" name="subaction" value="passwordChanged" />
		</form>
		';	
	}
	public static function editBook(knjiga $knjiga)
	{
		echo'
		<form name="uredjivanjeDetalja" id="uredjivanjeDetalja" method="get" action="index.php">
		<table width="416" height="291" border="0">
  			<tr>
    			<td width="122">Naslov</td>
    			<td width="284"><input type="text" name="title" id="title" value="'.$knjiga->getTitle().'" class="required"/></td>
 			</tr>
  			<tr>
   			 <td>Autor</td>
   			 <td><input type="text" name="author" id="author" value="'.$knjiga->getAuthor().'" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Izdava&#269;</td>
    			<td><input type="text" name="publisher" id="publisher" value="'.$knjiga->getPublisher().'" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Jezik</td>
    			<td><input type="text" name="language" id="language" value="'.$knjiga->getLanguage().'" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Vrsta</td>
    			<td><input type="text" name="type" id="type" value="'.$knjiga->getType().'" class="required"/></td>
  			</tr>
 			 <tr>
   			 <td>Opis</td>
   			 <td><textarea name="description" id="description" class="required" cols="45" rows="5">'.$knjiga->getDescription().'</textarea></td>
 			 </tr>
  			<tr>
 			   <td>&nbsp;</td>
   			 <td>&nbsp;</td>
 			 </tr>
  			<tr>
   			 <td>&nbsp;</td>
   			 <td>&nbsp;</td>
  			</tr>
		</table>
		<input type="hidden" name="sifraKnjige" value="'.$knjiga->getSifraKnjige().'" />
		<input type="hidden" name="action" value="editBookDetails" />
		<input type="hidden" name="subaction" value="writedb" />
		<input type="submit" value="Upiši" />
		</form>
		';
		
	}
	function newEditBook()
	{
		echo'
		<form name="upisKnjige" id="upisKnjige" method="get" action="index.php">
		<table width="416" height="291" border="0">
  			<tr>
    			<td width="122">Naslov</td>
    			<td width="284"><input type="text" name="title" id="title" class="required"/></td>
 			</tr>
  			<tr>
   			 <td>Autor</td>
   			 <td><input type="text" name="author" id="author" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Izdava&#269;</td>
    			<td><input type="text" name="publisher" id="publisher" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Jezik</td>
    			<td><input type="text" name="language" id="language" class="required"/></td>
  			</tr>
  			<tr>
    			<td>Vrsta</td>
    			<td><input type="text" name="type" id="type" class="required"/></td>
  			</tr>
  			<tr>
   			 <td>Broj primjeraka</td>
   			 <td><input type="text" name="brprimjeraka" id="brprimjeraka" class="required digits"/></td>
 			 </tr>
 			 <tr>
 			 	<td>Nabavna cijena</td>
 			 	<td><input type="text" name="nabavnacijena" id="nabavnacijena" class="required number"/></td>
 			 </tr>
 			 <tr>
   			 <td>Opis</td>
   			 <td><textarea name="description" id="description" class="required" cols="45" rows="5"></textarea></td>
 			 </tr>
  			<tr>
 			   <td>&nbsp;</td>
   			 <td>&nbsp;</td>
 			 </tr>
  			<tr>
   			 <td>&nbsp;</td>
   			 <td>&nbsp;</td>
  			</tr>
		</table>
		<input type="hidden" name="action" value="upisKnjige" />
		<input type="hidden" name="subaction" value="writedb" />
		<input type="submit" value="Upiši" />
		</form>
		';
		
	}
}
?>
