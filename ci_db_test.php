<?php
// CodeIgniter Database Connection Test
echo "<h2>CodeIgniter Database Test</h2>";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('PYRO_DEVELOPMENT', 'development');
define('PYRO_STAGING', 'staging');
define('PYRO_PRODUCTION', 'production');
define('ENVIRONMENT', PYRO_DEVELOPMENT);
define('BASEPATH', 'system/codeigniter/');
define('APPPATH', 'system/cms/');
define('EXT', '.php');

// Load CodeIgniter core
require_once BASEPATH.'core/Common.php';

// Load database configuration
require_once APPPATH.'config/database.php';

echo "<h3>Database Config:</h3>";
echo "<pre>";
echo "Environment: " . ENVIRONMENT . "\n";
echo "Driver: " . $db[ENVIRONMENT]['dbdriver'] . "\n";
echo "Host: " . $db[ENVIRONMENT]['hostname'] . "\n";
echo "Database: " . $db[ENVIRONMENT]['database'] . "\n";
echo "</pre>";

// Test database connection using CodeIgniter
echo "<h3>CodeIgniter Database Test:</h3>";
try {
    // Load the DB function
    require_once BASEPATH.'database/DB.php';

    // Initialize database
    $CI_DB =& DB();

    if ($CI_DB) {
        echo "<p style='color: green;'>✓ CodeIgniter database connection successful!</p>";

        // Test query
        $query = $CI_DB->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = 'db_office'");
        if ($query) {
            $row = $query->row();
            echo "<p>Tables found: " . $row->table_count . "</p>";
        }

        // Test simple query
        $query = $CI_DB->query("SELECT NOW() as current_time");
        if ($query) {
            $row = $query->row();
            echo "<p>Database time: " . $row->current_time . "</p>";
        }

    } else {
        echo "<p style='color: red;'>✗ CodeIgniter database connection failed</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<p style='color: red;'>✗ Fatal Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><a href='/'>← Back to SICANTIK</a></p>";
?>
