<?PHP
	class korisnik
	{
		private $sessionid;
		
		function __construct($id)
		{
			$this->setSessionid($id);	
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