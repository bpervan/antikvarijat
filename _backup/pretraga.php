<?PHP 
	class pretraga
	{
		public $id;
		public $kljuc;
		public $searchString;
		
		function __construct($id,$kljuc,$searchString)
		{
			$this->id=$id;
			$this->kljuc=$kljuc;
			$this->searchString=$searchString;
		}
		
		public function getId(){
			return $this->id;
		}
		public function getKljuc(){
			return $this->kljuc;
		}
		public function getSearchString(){
			return $this->searchString;
		}
		
	}
?>
