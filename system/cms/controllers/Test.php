<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        echo "<h2>Test Controller Loaded</h2>";
        echo "<p>✓ Controller constructor executed</p>";
    }

    public function index()
    {
        echo "<h2>Test Index Method</h2>";
        echo "<p>✓ Index method executed</p>";

        // Test database connection
        echo "<h3>Database Test:</h3>";
        try {
            $this->load->database();
            if ($this->db->conn_id) {
                echo "<p style='color: green;'>✓ Database loaded successfully</p>";

                $query = $this->db->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = 'db_office'");
                $row = $query->row();
                echo "<p>Tables found: " . $row->cnt . "</p>";
            } else {
                echo "<p style='color: red;'>✗ Database connection failed</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Database Exception: " . $e->getMessage() . "</p>";
        }

        echo "<h3>Environment Info:</h3>";
        echo "<p>PHP Version: " . phpversion() . "</p>";
        echo "<p>CodeIgniter Version: " . CI_VERSION . "</p>";
        echo "<p>Environment: " . ENVIRONMENT . "</p>";

        echo "<p><a href='/'>← Back to Home</a></p>";
    }

    public function simple()
    {
        echo "<h2>Simple Test</h2>";
        echo "<p>This is a simple test without database.</p>";
        echo "<p>Controller: Test</p>";
        echo "<p>Method: simple</p>";
        echo "<p><a href='/'>← Back to Home</a></p>";
    }
}
