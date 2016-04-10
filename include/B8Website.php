<?php
require 'B8.php';
require 'B8DataBase.php';
require 'B8Payments.php';

class B8Website extends B8 {
	
	public $form_errorstyle = 'font-weight: normal !important; padding: 15px 15px 15px 15px; font-size: 16px;';
	
	/*
	 * Website Variables
	 */

	public function __construct() {
        if ($this->session_use) {
		    $session = $this->session_validate_login();
		    if ($session) {
		        $this->p_id = $session;
		        $this->session_activate_cookie($session);
		    }
        }
        
        if ($this->db_use) {
            $B8db = new B8DataBase();
            $this->db_host = $B8db->db_host;
            $this->db_login = $B8db->db_login;
            $this->db_pass = $B8db->db_pass;
            $this->db_database = $B8db->db_database;
            $this->db_connection = $this->connect_db();
            if (!$this->db_connection) {
                $this->error_redirect($this->error_page, 'er=db-fail');
            }
        }
        
        if ($this->payment_use) {
            $this->payment = new B8Payments();
        }

		$this->data_hj = time();
		
		$this->query = (isset($_REQUEST[$this->query_call])) ? $_REQUEST[$this->query_call] : "";
		$this->order = (isset($_REQUEST[$this->order_call])) ? $_REQUEST[$this->order_call] : 0;
		$this->page = (isset($_REQUEST[$this->page_call]) && $_REQUEST[$this->page_call] != '' && is_numeric($_REQUEST[$this->page_call])) ? $_REQUEST[$this->page_call] : 1;
    } 

	public function call_header () {
?>

<?php		
	}

	public function call_footer () {
?>

<?php	
	}
	
	public function call_metas () {
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="SHORTCUT ICON" href="favicon.png">
<?php
	}
    
    public function call_head () {
?>

<?php
	}
	
	public function call_scripts () {
?>

<?php
	}
	
	/*
	 * Website Functions
	 */
	
	
    
}
?>
