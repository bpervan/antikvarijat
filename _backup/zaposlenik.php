<?PHP
	require_once __DIR__ . '/korisnik.php';
	
	class zaposlenik extends korisnik
	{
		private $ime;
		private $prezime;
		private $id;
		
		function __construct($ime,$prezime,$id)
		{
			$this->id=$id;
			$this->ime=$ime;
			$this->prezime=$prezime;
		}
		
		function __destruct()
		{
			$this->ime="";
			$this->prezime="";
			$this->id=0;	
		}
		public function setIme($ime)
		{
			$this->ime=$ime;	
		}
		
		public function setPrezime($prezime)
		{
			$this->prezime=$prezime;	
		}
		
		public function setId($id)
		{
			$this->id=$id;	
		}
		
		public function getIme()
		{
			return $this->ime;
		}
		
		public function getPrezime()
		{
			return $this->prezime;
		}	
		
		public function getId()
		{
			return $this->id;
		}
	}
?>