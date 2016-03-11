<?php
use \Defuse\Crypto\Crypto;
require 'php-encryption/autoload.php';

class B8 {
    public $p_id = 0;
    protected $session_use = TRUE;
    protected $session_id = 'SessionID';
    protected $session_time = 14400;
    
    protected $crypt_hashlog = 8;
    protected $crypt_hashportable = FALSE;
    protected $crypt_key = '603dd491b6943029b90942354fdd95e4';

    public $screen = 0;
    public $ret_back = '';
    public $str_error = '';
    public $str_error_type = 1;	
    public $error_page = 'atencao.php';
    public $data_hj = 0;
    
    public $item_id = 0;
    public $item = array();
    public $items = array();
    public $item_call = 'i';  
    public $query = '';
    public $query_call = 'q';
    public $order = 0;
    public $order_call = 'o';
    public $order_direction = 'desc';
    public $order_dcall = 'order';
    public $page = 1;
    public $page_call = 'p';
    public $page_arr = array();
    
    protected $db_use = TRUE;
    protected $db_error = FALSE;
    protected $db_connection;
    protected $db_persistence = FALSE;
    protected $db_host = '';
    protected $db_login = '';
    protected $db_pass = '';
    protected $db_database = '';
    public $db_id = 'dt_id';
    public $db_hash = 'dt_hash';

    public $form_trigger = '';
	public $form_populate = array();
	public $form_values = array();
    public $form_status = 'ALLOW';
    public $form_wrong = array();
	public $form_pref = 'f_';
	public $form_errorstyle = '';
    
    /*
     * Encryption
     */
    public function encrypt_create_key () {
        echo bin2hex(Crypto::createNewRandomKey());
    }
    
    public function encrypt ($str) {
        $key = hex2bin($this->crypt_key);
        
        $ciphertext = Crypto::encrypt($str, $key);
        $base64text = base64_encode($ciphertext);
        
        return $base64text;
    }
    
    public function decrypt ($str) {
        $key = hex2bin($this->crypt_key);
        
        $base64text = base64_decode($str);
        $plaintext = Crypto::decrypt($base64text, $key);
        
        return $plaintext;
    }
    
    public function hash_url () {
        return strtr(base64_encode(openssl_random_pseudo_bytes(9)), '+/', '-_');
    }
    
    public function pass_crypt ($pass) {
        require 'PasswordHash.php';
        
        $hasher = new PasswordHash($this->crypt_hashlog, $this->crypt_hashportable);
        $hash = $hasher->HashPassword($pass);
        if (strlen($hash) < 20) $this->error_redirect($this->error_page, 'er=pass-hash');
        unset($hasher);
        
        return $hash;
    }
    
    public function pass_check ($pass, $hashpass) {
        require 'PasswordHash.php';
        
        $hasher = new PasswordHash($this->crypt_hashlog, $this->crypt_hashportable);
        
        $isvalid = FALSE;
        if ($hasher->CheckPassword($pass, $hashpass)) $isvalid = TRUE;
        unset($hasher);
        
        return $isvalid;
    }
    
    /*
     * Session
     */
    public function session_activate_cookie ($r_id) {
        $c_time = time();
        
        $enc_str = $r_id.'::'.$c_time;

        $str_cookie = $this->encrypt($enc_str);

        setcookie($this->session_id, $str_cookie, $c_time + $this->session_time, "/");
    }

    public function session_deactivate_cookie () {
        setcookie($this->session_id, "", -1 * abs($this->session_time), "/");
    }

    protected function session_validate_login () {
        if (!isset($_COOKIE[$this->session_id])) return FALSE;
        $cookie_crypt = $_COOKIE[$this->session_id];
        if ($cookie_crypt == '') return FALSE;
        
        $str_cookie = $this->decrypt($cookie_crypt);
        
        $cook_opts = explode("::", $str_cookie);

        if (!isset($cook_opts[0]) || !isset($cook_opts[1]) || $cook_opts[0] == "" || $cook_opts[1]  == "") return FALSE;

        if (time() > ($cook_opts[1] + $this->session_time)) return FALSE;
        if (!is_int((int)$cook_opts[0])) return FALSE;
        
        if ($cook_opts[0]) return $cook_opts[0];
        return FALSE;
    }
    
    /*
     * Security
     */
    public function char_escape ($value) {
        $return = $value;
        
        if (is_array($value)) {
            $ret = array();
            foreach ($value as $val) $ret[] = htmlspecialchars($val, ENT_QUOTES);
            $return = $ret;
        } else {
            $return = htmlspecialchars($value, ENT_QUOTES);
        } 
        
        return $return;
    }
    
    public function char_unescape ($value) {
        $return = $value;
        
        if (is_array($value)) {
            $ret = array();
            foreach ($value as $val) $ret[] = htmlspecialchars_decode($value, ENT_QUOTES);
            $return = $ret;
        } else {
            $return = htmlspecialchars_decode($value, ENT_QUOTES);
        } 
        
        return $return;
    }
    
    /*
     * DataBase
     */
    protected function connect_db () {
        $dbhost = ($this->db_persistence) ? "p:".$this->db_host : $this->db_host;
        $mysqli = new mysqli($dbhost, $this->db_login, $this->db_pass, $this->db_database);    
        if (mysqli_connect_errno()) return FALSE;
        return $mysqli;
        
        return FALSE;
    }

    public function close_db () { 
        mysqli_close($this->db_connection);
    }

    public function sql_db ($query) {
        $result = $this->db_connection->query($query);
        
        if (!$result) {
        	if ($this->db_error) {
        		die('Query failed: '.mysqli_error($this->db_connection).' - SQL: '.$query);
        	} else {
        		$this->error_redirect($this->error_page, 'er=db-sql');            
            }
        }
        
        return $result;
    }
    
    public function fetch_array_db ($result) {
        return mysqli_fetch_assoc($result);
    }

    public function remove_db_id ($tb_name, $id) {
        $sql_r = 'DELETE FROM '.$tb_name.' WHERE '.$this->db_id.'='.$id;

        if (!$this->sql_db($sql_r)) {
            return FALSE;
        }

        return TRUE;
    }
    
    public function last_insert_id () {
    	return mysqli_insert_id($this->db_connection);
    }
    
    public function db_validate ($table, $trigger = NULL) {
    	if (is_null($trigger)) $trigger = $this->item_call;
    	$error_item = 'er=item';
    	if (!isset($_REQUEST[$trigger])) $this->error_redirect($this->error_page, $error_item);
    	
    	$db_id = $this->char_escape($_REQUEST[$trigger]);
        $hash_id = $this->validate_hash($table, $db_id);
        if (!$hash_id) $this->error_redirect($this->error_page, $error_item);
    	
    	return $hash_id;
    }
    
    public function db_hashfield ($table) {
        $hash_valid = FALSE;
        $hash = '';
        
        while (!$hash_valid) {
            $hash = $this->hash_url();
            
            $query = 'SELECT * FROM '.$table.' WHERE '.$this->db_hash."='".$hash."'";
            $result = $this->sql_db($query);
            $line = $this->fetch_array_db($result);
            
            if (!$line) $hash_valid = TRUE;
        }
        
        return $hash;
    }

    public function validate_id ($tb_name, $d_id) {
        $d_id = $this->char_escape($d_id);
        
        $query = 'SELECT * FROM '.$tb_name;

        $result = $this->sql_db($query);

        while ($line = $this->fetch_array_db($result)) {
            if ($line[$this->db_id] == $d_id) {	
                return TRUE;
            }
        }

        return FALSE;
    }
    
    public function validate_hash ($tb_name, $d_id) {
        $d_id = $this->char_escape($d_id);
        
        $query = 'SELECT * FROM '.$tb_name.' WHERE '.$this->db_hash."='".$d_id."'";
        $result = $this->sql_db($query);
        $line = $this->fetch_array_db($result);
        
        if ($line) return $line[$this->db_id];
        
        return FALSE;
    }

    public function validate_bdfield ($tb_name, $tb_col, $tb_data) {
        $query = 'SELECT * FROM '.$tb_name;

        $result = $this->sql_db($query);

        while ($line = $this->fetch_array_db($result)) {
            if ($line[$tb_col] == $tb_data) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function validate_idbdfield ($tb_name, $tb_col, $tb_data) {
        $query = 'SELECT * FROM '.$tb_name;

        $result = $this->sql_db($query);

        while ($line = $this->fetch_array_db($result)) {
            if ($line[$tb_col] == $tb_data) {
                return $line[$this->db_id];
            }
        }

        return FALSE;
    }

    public function validate_regexfield ($tb_name, $tb_col, $tb_data) {
        $query = 'SELECT * FROM '.$tb_name;

        $result = $this->sql_db($query);

        while ($line = $this->fetch_array_db($result)) {
            if (preg_match('/'.$tb_data.'/i', $line[$tb_col])) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    /*
     * Redirect 
     */
    public function page_redirect ($pg) {
        header('Location: '.$pg);
        exit;
    }
    
    /*
     * Error Redirect 
     */
    public function error_redirect ($pg, $args) {
        header('Location: '.$pg.'?'.$args);
        exit;
    }
    

    /*
     * Date Formating
     */
    public function write_date($dt) {
        $dia = date('d', $dt);
        $mes = date('m', $dt);
        $ano = date('Y', $dt);
        $semana = date('w', $dt);

        switch ($mes){
		    case 1: $mes = "Janeiro"; break;
		    case 2: $mes = "Fevereiro"; break;
		    case 3: $mes = "Março"; break;
		    case 4: $mes = "Abril"; break;
		    case 5: $mes = "Maio"; break;
		    case 6: $mes = "Junho"; break;
		    case 7: $mes = "Julho"; break;
		    case 8: $mes = "Agosto"; break;
		    case 9: $mes = "Setembro"; break;
		    case 10: $mes = "Outubro"; break;
		    case 11: $mes = "Novembro"; break;
		    case 12: $mes = "Dezembro"; break;
        }

        switch ($semana) {
		    case 0: $semana = "Domingo"; break;
		    case 1: $semana = "Segunda Feira"; break;
		    case 2: $semana = "Terça Feira"; break;
		    case 3: $semana = "Quarta Feira"; break;
		    case 4: $semana = "Quinta Feira"; break;
		    case 5: $semana = "Sexta Feira"; break;
		    case 6: $semana = "Sábado"; break;
        }
        
        return "$semana, $dia de $mes de $ano";
    }
    
    public function data_converter ($val) {
		if (preg_match("/(\d+)\/(\d+)\/(\d+)\s(\d+):(\d+)/", $val, $matches))
			return mktime($matches[4], $matches[5], 0, $matches[2], $matches[1], $matches[3]);
	
		return 0;
	}

    /*
     * PESQUISA, ORDENAR, PAGINAÇÃO
     */
    public function search_results ($items, $fields) {
    	if ($this->query == '') return $items;
    	
        $q_arr = explode(" ", $this->query);

        $by_finds = array();
        foreach ($items as $item) {
                $cnt_fields = 0;
                foreach ($fields as $field) {
                        $fl = strip_tags($item[$field]);
                        $cnt_qr = 0;
                        foreach ($q_arr as $qr) {
                                if ($qr == "") continue;

                                $qr_ed = preg_replace("/[^a-zA-Z0-9\s\p{P}]/", '.', html_entity_decode($qr));
                                $fl_ed = preg_replace("/[^a-zA-Z0-9\s\p{P}]/", '.', html_entity_decode($fl));

                                if (preg_match("/".$qr_ed."/i", $fl_ed)) $cnt_qr++;
                        }
                        $cnt_fields += $cnt_qr;
                }
                if ($cnt_fields > 0) {
                        $item['sort_results'] = $cnt_fields;
                        $by_finds[] = $item;
                }
        }

        function sortByFinds($a, $b) {
                return $b['sort_results'] - $a['sort_results'];
        }
        usort($by_finds, 'sortByFinds');

        return $by_finds;	
    }

    public function order_results ($items, $ord_arr) {
    	if ($this->order == 0) return $items;
    
        $tmp = array();
        foreach ($items as $key => $row) {
                $tmp[$key] = $row[$ord_arr[$this->order]];
        }

        if ($this->order_direction == 'asc') array_multisort($tmp, SORT_ASC, $items);
        if ($this->order_direction == 'desc') array_multisort($tmp, SORT_DESC, $items);

        return $items;
    }

    public function page_results ($items, $pg_results, $pg_bl_results) {	
    	$page = $this->page;
    
        $error_return = array(
                'pagina_inicio' => 0,
                'pagina_final' => -1,
                'pagina_total' => 1,
                'pagina_bloco_inicio' => 1,
                'pagina_bloco_final' => 1,
                'pagina_bloco_total' => 1);

        $pg_init = 0;
        $pg_finl = $pg_results;
        $pg_bl_init = 0;
        $pg_bl_finl = $pg_bl_results;
        $total_items = sizeof($items);

        if ($total_items == 0) return $error_return;

        $pg_total_pg = ceil($total_items / $pg_results);
        $pg_total_bl = ceil($pg_total_pg / $pg_bl_results) * $pg_bl_results;

        if ($page == 0) $page++;

        if ($page > $pg_total_pg) return $error_return;

        $pg_finl = ($page * $pg_results > $total_items) ? $total_items - 1 : $page * $pg_results - 1;
        $pg_init = ($page * $pg_results > $total_items) ? ($page * $pg_results) - $pg_results : $pg_finl - $pg_results + 1;

        $pg_bl_finl_holder = ceil($page / $pg_bl_results) * $pg_bl_results;
        $pg_bl_finl = ($pg_bl_finl_holder > $pg_total_pg) ? $pg_total_pg : $pg_bl_finl_holder;
        $pg_bl_init = ($pg_bl_finl_holder > $pg_total_pg) ? $pg_total_bl - $pg_bl_results + 1 : $pg_bl_finl - $pg_bl_results + 1;

        return array(
                'pagina_inicio' => $pg_init,
                'pagina_final' => $pg_finl,
                'pagina_total' => $pg_total_pg,
                'pagina_bloco_inicio' => $pg_bl_init,
                'pagina_bloco_final' => $pg_bl_finl,
                'pagina_bloco_total' => $pg_total_bl);
    }

	/*
	 * Forms
	 */
    public function form_checktrigger ($trigger = NULL) {
    	if (is_null($trigger)) $trigger = $this->form_trigger;
    	if ($trigger != '' && isset($_REQUEST[$trigger])) return true;
    	return false;
    }
    
    public function form_getvalues ($formvalues = NULL) {
    	if (is_null($formvalues)) $formvalues = $this->form_populate;
    
    	$return_values = array();
    	
    	if (count($formvalues) > 0) {
			foreach ($formvalues as $key => $value) {
				$fdata = (isset($_REQUEST[$this->form_pref.$key])) ? $_REQUEST[$this->form_pref.$key] : '';
                $fdata = $this->char_escape($fdata);
			
		        if(!$this->form_validate($fdata, $value[0], $value[1])) {
		        	$this->form_status = 'WRONG';
		        	$this->form_wrong[] = $key;
		        }
		        
		        $return_values[$key] = $fdata;
		    }
		}
        
        return $return_values;
    }
    
    public function form_validate ($field_data, $field_type, $field_required) {
		$types_conf = array('mintext', 'smltext', 'medtext', 'bigtext', 'hugtext',
		 'password', 'email', 'number', 'date', 'dateday', 'datemon', 'dateyr', 'array');
		
		if (!in_array($field_type, $types_conf)) return FALSE;

        if ($field_data == "" && $field_required) return FALSE;
        if ($field_data == "" && !$field_required) return TRUE;
        
        if ($field_type == 'mintext' && strlen($field_data) > 2) return FALSE;
        if ($field_type == 'smltext' && strlen($field_data) > 10) return FALSE;
        if ($field_type == 'medtext' && strlen($field_data) > 50) return FALSE;
        if ($field_type == 'bigtext' && strlen($field_data) > 300) return FALSE;
        if ($field_type == 'hugtext' && strlen($field_data) > 1000000000000) return FALSE;
        if ($field_type == 'password' && strlen($field_data) > 300) return FALSE;
        if ($field_type == 'email' && !preg_match("/([A-Za-z0-9\.\-\_\!\#\$\%\&\'\*\+\/\=\?\^\`\{\|\}]+)\@([A-Za-z0-9.-_]+)(\.[A-Za-z]{2,5})/", $field_data)) return FALSE;
        if ($field_type == 'number' && !is_numeric($field_data)) return FALSE;
        if ($field_type == 'date' && !preg_match("/\d+\/\d+\/\d+/", $field_data)) return FALSE;
        if ($field_type == 'dateday' && !is_numeric($field_data) && $field_data > 31) return FALSE;
        if ($field_type == 'datemon' && !is_numeric($field_data) && $field_data > 12) return FALSE;
        if ($field_type == 'dateyr' && !is_numeric($field_data) && $field_data > 9999) return FALSE;
        if ($field_type == 'array' && !is_array($field_data)) return FALSE;
        
        return TRUE;
    }
    
    public function form_construct ($field, $formvalues = NULL) {
    	if (is_null($formvalues)) $formvalues = $this->form_populate;
    
    	if (!array_key_exists($field, $formvalues)) return '';
    	
    	$form_group_error = (in_array($field, $this->form_wrong)) ? ' has-error has-feedback' : '';
    	$form_warn_error = (in_array($field, $this->form_wrong)) ? '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' : '';
    	$form_required_class = (in_array($field, $this->form_wrong)) ? 'text-danger' : 'text-primary';
    	$form_required = ($formvalues[$field][1]) ? '&nbsp;<span class="'.$form_required_class.'">*</span>' : '';
    	$form_value = (isset($this->form_values[$field])) ? $this->form_values[$field] : '';
    	
    	echo '<div class="form-group'.$form_group_error.'">';
		echo '<label class="control-label" for="'.$this->form_pref.$field.'">'.$formvalues[$field][2].$form_required.'</label>';
		
		if ($formvalues[$field][0] == 'mintext') {
			echo '<input type="text" maxlength="2" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'smltext') {
			echo '<input type="text" maxlength="10" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'medtext') {
			echo '<input type="text" maxlength="50" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'bigtext') {
			echo '<input type="text" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'email') {
			echo '<input type="text" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'password') {
			echo '<input type="password" class="form-control" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'" value="'.$form_value.'">'.$form_warn_error;
		}
		if ($formvalues[$field][0] == 'hugtext') {
			echo '<textarea class="form-control" rows="3" id="'.$this->form_pref.$field.'" placeholder="'.$formvalues[$field][2].'" name="'.$this->form_pref.$field.'">'.$form_value.'</textarea>'.$form_warn_error;
		}
		
		echo '</div>';
    }
    
    public function form_fetchvalue ($field, $value = NULL) {
        if (!$value) {
            return (isset($this->form_values[$field])) ? $this->form_values[$field] : '';
        } else {
            return (isset($this->form_values[$field])) ? $this->form_values[$field] : $value;
        }
        
        return '';
    }
    
    public function form_displaytrigger ($class, $trigger = NULL) {
    	if (is_null($trigger)) $trigger = $this->form_trigger;
    
    	$trigger_class = ($class != '') ? ' class="'.$class.'"' : '';
    
    	echo '<div class="form-group">
                  <input type="submit"'.$trigger_class.' name="'.$trigger.'" value="Enviar">
              </div>';
    }
    
    public function form_displayerror () {
    	$class = ($this->str_error_type == 1) ? 'bg-danger' : 'bg-primary';
    	$display = ($this->str_error == "") ? 'none' : 'block';
    
    	echo '<p class="'.$class.'" style="'.$this->form_errorstyle.' display: '.$display.';">'.$this->str_error.'</p>';
    }
    
    /*
     * Emails
     */
    public function send_email ($to, $subject, $msg, $from) {
		$headers = "MIME-Version: 1.1\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: ".$from."\n"; // remetente
		$headers .= "Return-Path: ".$from."\n"; // return-path
		$headers .= "Reply-To: ".$from."\n"; // return-path
		
		$message = '<html><body>'.$msg.'</body></html>';
	
		$mail_sent = @mail( $to, $subject, $message, $headers );
	
		if ($mail_sent) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	
	}
    
    /*
     * TEXT FORMAT
     */
    public function text_responsify ($text) {
        $text = preg_replace("/<img/", '<img class="img-responsive"', $text);
        
        $text = preg_replace("/<(iframe|embed|video|object)(.*?)width=\".*?\"/si", "<$1$2", $text);
        $text = preg_replace("/<(iframe|embed|video|object)(.*?)style=\".*?\"/si", "<$1$2", $text);
        $text = preg_replace("/<(iframe|embed|video|object)/si", "<$1 width=\"100%\"", $text);
        
        return $text;
    }
    
    public function text_format ($text) {
        return $this->text_responsify($this->char_unescape($text));
    }
    
    public function text_cut ($text, $start, $end, $jumps) {
        preg_match_all("/$start.*?$end/si", $text, $match);
        if (isset($match[0][$jumps-1])) return $match[0][$jumps-1];
        return '';
    }
    
    public function text_strip ($text) {
        return strip_tags($text);
    }
    
    /*
     * GALERIAS
     */
    public function get_galeria_code ($gid) {
        $return = array();
        $each = explode("--$$--", $gid);
        foreach ($each as $ec) {
                $es = explode("++$$++", $ec);
                $return[] = $es;
        }

        return $return;
    }
    
    /*
     * Tags
     */
    public function tags_extract ($field) {
        return explode(",", $field);
    }

    public function tags_fetch_results ($items, $field) {
        $tags_arr = array();
        $max_res = 1000;

        $m_cnt = 0;
        foreach ($items as $it) {
            $m_cnt++;
            if ($m_cnt > $max_res) break;

            $tgs = $this->tags_extract($it[$field]);
            foreach ($tgs as $ts) {
                if (!in_array($ts, $tags_arr) && $ts != "") $tags_arr[] = $ts;
            }
        }

        return $tags_arr;
    }

    public function tags_links ($field, $page, $class) {
        $cntfield = count($field);
        $return = '';

        for ($x = 0; $x < $cntfield; $x++) {
            $return .= '<a class="'.$class.'" href="'.$page.'?'.$this->query_call.'='.$field[$x].'">'.$field[$x].'</a>';
            if ($x != $cntfield - 1) $return .= ', ';
        }

        return $return;
    }

    public function tags_list ($field) {
        $cntfield = count($field);
        $return = '';

        for ($x = 0; $x < $cntfield; $x++) {
            $return .= $field[$x];
            if ($x != $cntfield - 1) $return .= ', ';
        }

        return $return;
    }

    public function tags_hashlist ($field) {
        $cntfield = count($field);
        $return = '';

        for ($x = 0; $x < $cntfield; $x++) {
            $return .= '#'.$field[$x];
            if ($x != $cntfield - 1) $return .= ' ';
        }

        return $return;
    }

    public function tags_search ($field) {
        $cntfield = count($field);
        $return = '';

        for ($x = 0; $x < $cntfield; $x++) {
            $return .= $field[$x];
            if ($x != $cntfield - 1) $return .= ' ';
        }

        return $return;
    }
}

?>
