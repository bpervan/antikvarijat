<?PHP

	class search
	{
		function __construct($state){
			
			if(isset($state)){
				if($state=="searchPerformed"){
					pretrazi();
				}
					
				else {
					ispisiFormu();
				}
			}
			else {
				ispisiFormu();
			}
				
		}
		
		public function ispisiFormu(){
			
		}
		
		public function pretrazi(){
			
		}
		
	}

?>