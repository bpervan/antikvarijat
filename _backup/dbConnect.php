<?PHP
	class dbConnect
	{
		public $con;
		function __construct()
		{
			$this->connect();
		}
		
		function __destruct()
		{
			$this->close();
		}
		
		function connect()
		{
			require_once __DIR__ . '/dbConfig.php';
			$con=mysql_connect(dbServer,dbUser,dbPassword) or die(mysql_error());
			$db=mysql_select_db(dbDatabase,$con) or die(mysql_error());
			mysql_query("SET NAMES 'utf8'",$con);
			
			$this->con=$con;	
		}
		
		function close()
		{
			mysql_close();
		}
	}
?>