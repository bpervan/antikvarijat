<?PHP
	class korisnik
	{
		private $sessionid;
		
		function __construct($id)
		{
			$this->sessionid=$id;
		}
		
		public function getSessionid()
		{
			return $this->sessionid;
		}	
		
		public function setSessionid($id)
		{
			$this->sessionid=$id;
		}
	}
?>