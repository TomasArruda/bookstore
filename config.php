<?PHP
	define('DB_USERNAME', 'tada2'); 
	define('DB_PASSWORD', 'llephl0'); 
	define('DB_HOST', 'dragon.kent.ac.uk'); 
	define('DB_DATABASE', 'tada2'); 
	define('DATA_FOLDER', '/proj/co639/assessment2_data/tada2/'); 
	define('SERVICE_URL','http://raptor.kent.ac.uk/proj/co639/assessment2/tada2/'); 
	//define('AUDIT_LOG_START_KEY', ...); 
	define('PAYPAL_PHP_SDK', '/courses/co639/'); 
	define('PP_CONFIG_PATH', __DIR__); 

	function connect() {
        return new PDO('mysql:host=' . DB_HOST . ';'. // Connecting to database
                       'dbname=' . DB_DATABASE,
                       DB_USERNAME, DB_PASSWORD);
    }
?>