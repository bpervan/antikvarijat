<?PHP
	class Knjiga
	{
		private $title;
		private $author;
		private $description;
		private $publisher;
		private $date;
		private $language;
		private $type;
		private $sifraknjige;
		private $brprimjeraka;
		
		function __construct($title,$author,$description,$publisher,$date,$language,$type,$sifraknjige)
		{
			$this->title=$title;
			$this->author=$author;
			$this->description=$description;
			$this->publisher=$publisher;
			$this->date=$date;
			$this->language=$language;
			$this->type=$type;
			$this->sifraknjige=$sifraknjige;
		}
		public function getLanguage()
		{
			return $this->language;
		}
		public function getTitle()
		{
			return $this->title;	
		}
		public function setTitle($title)
		{
			$this->title=$title;
		}	
		public function getAuthor()
		{
			return $this->author;
		}
		public function setAuthor($author)
		{
			$this->author=$author;
		}
		public function getDescription()
		{
			return $this->description;	
		}
		public function setDescription($description)
		{
			$this->description=$description;	
		}
		public function getPublisher()
		{
			return $this->publisher;	
		}
		public function setPublisher($publisher)
		{
			$this->publisher=$publisher;	
		}
		public function getDate()
		{
			return $this->date;
		}
		public function setDate($date)
		{
			$this->date=$date;	
		}		
		public function getType()
		{
			return $this->type;	
		}
		public function setType($type)
		{
			$this->type=$type;
		}
		public function getSifraKnjige()
		{
			return $this->sifraknjige;	
		}
		public function setSifraKnjige($sifraknjige)
		{
			$this->sifraknjige=$sifraknjige;	
		}
		public function getBrPrimjeraka()
		{
			return $this->brprimjeraka;	
		}
		public function setBrPrimjeraka($brprimjeraka)
		{
			$this->brprimjeraka=$brprimjeraka;	
		}
		

	}
?>