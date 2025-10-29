<?php
// Full CodeIgniter/PyroCMS debugging script

echo "<h1>Full Framework Debug</h1>";

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Define all constants exactly like index.php
define('PYRO_DEVELOPMENT', 'development');
define('PYRO_STAGING', 'staging');
define('PYRO_PRODUCTION', 'production');
define('ENVIRONMENT', PYRO_DEVELOPMENT);

// System paths
$system_path = 'system/codeigniter';
$application_folder = 'system/cms';
$addon_folder = 'addons';

// Ensure proper paths
if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path).'/';
}
$system_path = rtrim($system_path, '/').'/';

// Define constants
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace("\\", "/", $system_path));
define('SITE_DOMAIN', $_SERVER['HTTP_HOST']);
define('ADDON_FOLDER', $addon_folder.'/');
define('SHARED_ADDONPATH', 'addons/shared_addons/');
define('FCPATH', str_replace(SELF, '', __FILE__));

$parts = explode('/', trim(BASEPATH, '/'));
define('SYSDIR', end($parts));
define('APPPATH', $application_folder.'/');
define('VIEWPATH', APPPATH.'views/');

echo "<h2>Environment Check</h2>";
echo "<pre>";
echo "BASEPATH: " . BASEPATH . "\n";
echo "APPPATH: " . APPPATH . "\n";
echo "ENVIRONMENT: " . ENVIRONMENT . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "</pre>";

// Check critical files
echo "<h2>Critical Files Check</h2>";
$critical_files = [
    BASEPATH.'core/CodeIgniter.php',
    BASEPATH.'database/DB.php',
    BASEPATH.'database/drivers/mysqli/mysqli_driver.php',
    APPPATH.'config/database.php',
    APPPATH.'config/config.php',
    APPPATH.'config/autoload.php',
    APPPATH.'config/constants.php',
    APPPATH.'libraries/MX_Controller.php',
    APPPATH.'libraries/MX_Loader.php'
];

foreach ($critical_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ " . basename($file) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ " . basename($file) . " - MISSING!</p>";
    }
}

// Test database configuration
echo "<h2>Database Configuration Test</h2>";
try {
    include APPPATH.'config/database.php';
    echo "<p style='color: green;'>✓ Database config loaded</p>";

    if (isset($db[ENVIRONMENT])) {
        $db_config = $db[ENVIRONMENT];
        echo "<p>✓ Config found for: " . ENVIRONMENT . "</p>";
        echo "<pre>";
        echo "Host: " . $db_config['hostname'] . "\n";
        echo "Database: " . $db_config['database'] . "\n";
        echo "User: " . $db_config['username'] . "\n";
        echo "Port: " . $db_config['port'] . "\n";
        echo "</pre>";

        // Test actual connection
        $mysqli = new mysqli($db_config['hostname'], $db_config['username'],
                           $db_config['password'], $db_config['database'], $db_config['port']);
        if ($mysqli->connect_error) {
            echo "<p style='color: red;'>✗ Connection failed: " . $mysqli->connect_error . "</p>";
        } else {
            echo "<p style='color: green;'>✓ Direct MySQL connection successful!</p>";

            // Check for PyroCMS tables
            $result = $mysqli->query("SHOW TABLES LIKE 'core_%'");
            if ($result && $result->num_rows > 0) {
                echo "<p style='color: green;'>✓ Found " . $result->num_rows . " core_ tables</p>";
                while ($row = $result->fetch_row()) {
                    echo "<small>" . $row[0] . "</small><br>";
                }
            } else {
                echo "<p style='color: orange;'>⚠ No core_ tables found - this might be the issue!</p>";
            }

            // Check for settings table
            $result = $mysqli->query("SHOW TABLES LIKE 'settings'");
            if ($result && $result->num_rows > 0) {
                echo "<p style='color: green;'>✓ Settings table found</p>";
            } else {
                echo "<p style='color: orange;'>⚠ No settings table found</p>";
            }

            $mysqli->close();
        }
    } else {
        echo "<p style='color: red;'>✗ No config for environment: " . ENVIRONMENT . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database config error: " . $e->getMessage() . "</p>";
}

// Try to load CodeIgniter core
echo "<h2>CodeIgniter Core Test</h2>";
try {
    include BASEPATH.'core/Common.php';
    echo "<p style='color: green;'>✓ Common.php loaded</p>";

    if (function_exists('load_class')) {
        $benchmark = load_class('Benchmark', 'core');
        echo "<p style='color: green;'>✓ Benchmark class loaded</p>";

        $hooks = load_class('Hooks', 'core');
        echo "<p style='color: green;'>✓ Hooks class loaded</p>";

        $config = load_class('Config', 'core');
        echo "<p style='color: green;'>✓ Config class loaded</p>";

        // Try database class
        $db = load_class('DB', 'database');
        echo "<p style='color: green;'>✓ Database class loaded</p>";

        // Test database connection through CI
        if (isset($db) && method_exists($db, 'initialize')) {
            echo "<p>✓ DB class has initialize method</p>";
        }

    } else {
        echo "<p style='color: red;'>✗ load_class function not available</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>✗ CI Core Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<p style='color: red;'>✗ CI Fatal Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Check PyroCMS specific files
echo "<h2>PyroCMS Specific Check</h2>";
$pyro_files = [
    APPPATH.'config/constants.php',
    APPPATH.'libraries/MX_Controller.php',
    APPPATH.'libraries/MX_Loader.php',
    APPPATH.'core/MX_Loader.php',
    APPPATH.'core/MX_Router.php'
];

foreach ($pyro_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ " . basename($file) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ " . basename($file) . " - MISSING!</p>";
    }
}

echo "<h2>PHP Extensions Check</h2>";
$required = ['mysqli', 'pdo', 'pdo_mysql', 'mbstring', 'json', 'curl'];
foreach ($required as $ext) {
    echo "<p style='color: " . (extension_loaded($ext) ? 'green' : 'red') . ";'>✓ " . $ext . "</p>";
}

echo "<h2>Server Info</h2>";
echo "<pre>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "</pre>";

echo "<p><a href='/'>← Back to SICANTIK</a></p>";
echo "<p><small>Debug script completed at " . date('Y-m-d H:i:s') . "</small></p>";
?>
