<?php
require 'B8.php';
require 'B8DataBase.php';

class B8Admin extends B8 {

	public $session_id = 'SessionRID';
	
	public $form_errorstyle = 'font-weight: normal !important; padding: 15px 15px 15px 15px; font-size: 16px;';
	
	public $modules_list = array(); // multiselect, datetimepicker, ckfinder, multiupload, multiupload-e, multifileupload, multifileupload-e
	
    public $language = 1;  // 1-Português, 2-English, 3-Deutsche
    
	/*
	 * Admin Variables
	 */
	 
	public $user_array = array();
	public $user_permissao = array();
    public $strings = array();

	public function __construct($cookie_session = NULL) {
        if ($this->session_use) {
            if ($cookie_session) $_COOKIE[$this->session_id] = $cookie_session;
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
        
        $this->strings = simplexml_load_file("../root/include/strings.xml");
        
		$this->data_hj = time();
		
		$this->query = (isset($_REQUEST[$this->query_call])) ? $_REQUEST[$this->query_call] : "";
		$this->order = (isset($_REQUEST[$this->order_call])) ? $_REQUEST[$this->order_call] : 0;
		$this->order_direction = (isset($_REQUEST[$this->order_dcall])) ? $_REQUEST[$this->order_dcall] : 'desc';
		$this->page = (isset($_REQUEST[$this->page_call]) && $_REQUEST[$this->page_call] != '' && is_numeric($_REQUEST[$this->page_call])) ? $_REQUEST[$this->page_call] : 1;
    
    	$query = "SELECT * FROM sdk_admsettings WHERE dt_id=1";
        $result = $this->sql_db($query);
        $setting = $this->fetch_array_db($result);
        if ($setting) {
            $this->language = $setting['dt_language'];
        }
        
        if ($this->p_id > 0) {
			$query = "SELECT * FROM sdk_admlogin WHERE dt_id=".$this->p_id;
			$result = $this->sql_db($query);
			$line = $this->fetch_array_db($result);		
			$this->user_array = $line;
			$this->user_permissao = explode(',', $this->user_array['dt_campo3']);
		}

    } 

	public function call_head () {
?>
<div class="row rt_tophdr">
	<div class="col-xs-2">
		<a href="index.php"><img src="style/img/sedek_logo.png" /></a>
	</div>  
	<div class="col-xs-10">
	  <?php if ($this->p_id > 0) { ?>
		<a class="pull-right rt_topmn" href="logout.php"><i class="fa fa-power-off fa-lg"></i> <?php echo $this->get_string(12); ?></a>
		<div class="pull-right rt_topmn hidden-xs" href="#"><?php echo $this->get_string(11).' '.$this->user_array['dt_campo1']; ?></div>
      <?php } ?>
	</div>  
</div>
<?php		
	}

	public function call_menu ($typ) {
	
	  echo '<div class="col-xs-2 rt_lftcol">
      <a class="rt_lftmn'.(($typ == 1) ? ' rt_lnkmn' : '').'" href="main.php"><i class="fa fa-home fa-2x"></i> <span class="hidden-xs rt_lfttxt rt_lfttxtaj">'.$this->get_string(1).'</span></a>';
	  if (in_array(1, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 2) ? ' rt_lnkmn' : '').'" href="categorias.php"><i class="fa fa-folder-open fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(2).'</span></a>';
      if (in_array(2, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 3) ? ' rt_lnkmn' : '').'" href="posts.php"><i class="fa fa-clipboard fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(3).'</span></a>';
	  if (in_array(7, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 8) ? ' rt_lnkmn' : '').'" href="paginas.php"><i class="fa fa-desktop fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(4).'</span></a>';
      if (in_array(3, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 4) ? ' rt_lnkmn' : '').'" href="imagens.php"><i class="fa fa-camera fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(5).'</span></a>';
	  if (in_array(8, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 9) ? ' rt_lnkmn' : '').'" href="newsletters.php"><i class="fa fa-envelope fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(6).'</span></a>';
	  if (in_array(9, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 11) ? ' rt_lnkmn' : '').'" href="cadastrados.php"><i class="fa fa-globe fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(7).'</span></a>';
	  if (in_array(10, $this->user_permissao)) echo '<a class="rt_lftmn'.(($typ == 10) ? ' rt_lnkmn' : '').'" href="contatos.php"><i class="fa fa-comments fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(8).'</span></a>';
      if ($this->user_array['dt_admin'] == 1) {
		  echo '<a class="rt_lftmn'.(($typ == 12) ? ' rt_lnkmn' : '').'" href="usuarios.php"><i class="fa fa-group fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(9).'</span></a>';
	  } else {
		  echo '<a class="rt_lftmn'.(($typ == 12) ? ' rt_lnkmn' : '').'" href="usuarios_editar.php?i='.$this->user_array['dt_hash'].'"><i class="fa fa-group fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(72).'</span></a>';
	  }
      if ($this->user_array['dt_admin'] == 1) {
		  echo '<a class="rt_lftmn'.(($typ == 13) ? ' rt_lnkmn' : '').'" href="configuracoes.php"><i class="fa fa-cogs fa-2x"></i> <span class="hidden-xs rt_lfttxt">'.$this->get_string(10).'</span></a>';
	  }
      echo '</div>';	
	
	}
	
	public function call_footer () {
?>

<?php	
	}
	
	public function call_header () {
?>
<link href="style/css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="style/css/font-awesome/css/font-awesome.min.css">
<link href="style/css/style.css" rel="stylesheet" type="text/css" />
<?php
		if (in_array('datetimepicker', $this->modules_list)) {
?>
<link href="style/css/jquery-ui-1.10.4.min.css" rel="stylesheet" type="text/css" />
<link href="style/css/jquery-timepicker.css" rel="stylesheet" type="text/css" />
<?php			
		}
		if (in_array('multiupload', $this->modules_list) || in_array('multiupload-e', $this->modules_list)
           || in_array('multifileupload', $this->modules_list) || in_array('multifileupload-e', $this->modules_list)) {
?>
<link href="style/css/fine-uploader-new.css" rel="stylesheet" type="text/css" />
<link href="style/css/jquery.dad.css" rel="stylesheet" type="text/css" />
<?php			
		}
	}
	
	public function call_scripts () {
?>
<script type="text/javascript" src="style/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="style/js/bootstrap.js"></script>
<?php
		if (in_array('multiselect', $this->modules_list)) {
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#allselect').click(function(event) { 
        if(this.checked) { 
            $('.checkbx').each(function() { 
                this.checked = true;               
            });
        }else{
            $('.checkbx').each(function() { 
                this.checked = false;                     
            });        
        }
    });
   
});
</script>
<?php			
		}
		if (in_array('datetimepicker', $this->modules_list)) {
?>
<script type="text/javascript" src="style/js/jquery-ui-1.10.4.min.js"></script>
<script type="text/javascript" src="style/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript">
	 $(function() {
		$( ".datepick" ).datetimepicker({
			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
			dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
			nextText: 'Próximo',
			prevText: 'Anterior'
		});
	});
</script>
<?php			
		}
		if (in_array('ckfinder', $this->modules_list)) {
?>
<script type="text/javascript" src="style/scripts/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="style/scripts/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
var cke = document.getElementsByClassName("ckeditor");
for (var i = 0; i < cke.length; i++) {
    CKEDITOR.replace( cke[i],  {
        language: '<?php echo $this->get_string(80); ?>'
    });
}
</script>
<script type="text/javascript">
    CKFinder.setupCKEditor( null, '/root/style/scripts/ckfinder/' );
</script>
<?php			
		}
		if (in_array('multiupload', $this->modules_list) || in_array('multiupload-e', $this->modules_list)
           || in_array('multifileupload', $this->modules_list) || in_array('multifileupload-e', $this->modules_list)) {
?>
<script type="text/javascript" src="style/js/jquery.fine-uploader.min.js"></script> <?php // Fine-Uploader ?>
<script type="text/javascript" src="style/js/jquery.dad.js"></script>  <?php // Jquery Draggable ?>
<script type="text/template" id="qq-template">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Arraste as imagens aqui">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div><?php echo $this->get_string(81); ?></div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
</script>
<?php			
		}
		if (in_array('multiupload', $this->modules_list) || in_array('multiupload-e', $this->modules_list)) {
?>
<script>
	var img_cnt = 0;
	
	function RemoveElement(divGal) {
		var img_gallery = document.getElementById('img_gallery');
		var oldGallery = document.getElementById(divGal);
		img_gallery.removeChild(oldGallery);
	}
	function SetFileField(fileUrl) {
		img_cnt++;
		var img_gallery = document.getElementById('img_gallery');
		
		var newGallery = document.createElement('div');
		newGallery.setAttribute('id', 'd' + img_cnt);
		newGallery.setAttribute('class', 'row');
		
		var newxs = document.createElement('div');
		newxs.setAttribute('class', 'col-xs-2');
		var newxs4 = document.createElement('div');
		newxs4.setAttribute('class', 'col-xs-4');
		
		var newImg = document.createElement('img');
		newImg.setAttribute('src', fileUrl);
		newImg.setAttribute('id', 'i' + img_cnt);
		newImg.setAttribute('class', 'img-thumbnail draggable');
		newxs.appendChild(newImg);
		
		var newHid = document.createElement('input');
		newHid.setAttribute('type', 'hidden');
		newHid.setAttribute('name', 'f_frm20[]');
		newHid.setAttribute('value', fileUrl);
		newxs.appendChild(newHid);
		
		var newInput = document.createElement('input');
		newInput.setAttribute('type', 'text');
		newInput.setAttribute('class', 'form-control');
		newInput.setAttribute('placeholder', '<?php echo $this->get_string(82); ?>');
		newInput.setAttribute('name', 'f_frm100[]');
		newxs4.appendChild(newInput);
		
		var newPar = document.createElement('p');
		var newLnk = document.createElement('a');
		newLnk.setAttribute('href', '#');
		newLnk.setAttribute('class', 'btn btn-danger rt_p');
		newLnk.setAttribute('onClick', 'RemoveElement(\'d' + img_cnt + '\'); return false;');
		newLnk.innerHTML = 'Excluir';
		newPar.appendChild(newLnk);
		newxs4.appendChild(newPar);
		
		newGallery.appendChild(newxs);
		newGallery.appendChild(newxs4);

		img_gallery.appendChild(newGallery);
	}

    var uploader = new qq.FineUploader({
        debug: true,
        element: document.getElementById('fine-uploader'),
        request: {
            endpoint: '/include/endpoint.php'
        },
        deleteFile: {
            enabled: true,
            endpoint: '/include/endpoint.php'
        },
        retry: {
           enableAuto: true
        },
        callbacks: {
            onComplete: function (id, name, obj) {
                SetFileField('/style/images/files/'+obj.uuid+'/'+name);
            },
            onAllComplete: function () {
                this.reset();
            }
        }
    });
    
    $(function(){ 
        $('#img_gallery').dad({draggable:'.draggable'});
    });
    
</script>
<?php			
		}
        if (in_array('multifileupload', $this->modules_list) || in_array('multifileupload-e', $this->modules_list)) {
?>
<script>
	var file_cnt = 0;
	
	function RemoveFileElement(divGal) {
		var img_gallery = document.getElementById('file_gallery');
		var oldGallery = document.getElementById(divGal);
		img_gallery.removeChild(oldGallery);
	}
	function SetuFileField(fileUrl, fileName) {
		file_cnt++;
		var img_gallery = document.getElementById('file_gallery');
		
		var newGallery = document.createElement('div');
		newGallery.setAttribute('id', 'd' + file_cnt);
		newGallery.setAttribute('class', 'row');
		
		var newxs = document.createElement('div');
		newxs.setAttribute('class', 'col-xs-2');
		var newxs4 = document.createElement('div');
		newxs4.setAttribute('class', 'col-xs-4');
		
		var newImg = document.createElement('i');
		newImg.setAttribute('class', 'fa fa-file fa-4x draggable');
		newxs.appendChild(newImg);
		
		var newHid = document.createElement('input');
		newHid.setAttribute('type', 'hidden');
		newHid.setAttribute('name', 'f_frm30[]');
		newHid.setAttribute('value', fileUrl);
		newxs.appendChild(newHid);
		
		var newInput = document.createElement('input');
		newInput.setAttribute('type', 'text');
		newInput.setAttribute('class', 'form-control');
		newInput.setAttribute('placeholder', '<?php echo $this->get_string(82); ?>');
		newInput.setAttribute('name', 'f_frm200[]');
        newInput.setAttribute('value', fileName);
		newxs4.appendChild(newInput);
		
		var newPar = document.createElement('p');
		var newLnk = document.createElement('a');
		newLnk.setAttribute('href', '#');
		newLnk.setAttribute('class', 'btn btn-danger rt_p');
		newLnk.setAttribute('onClick', 'RemoveFileElement(\'d' + file_cnt + '\'); return false;');
		newLnk.innerHTML = 'Excluir';
		newPar.appendChild(newLnk);
		newxs4.appendChild(newPar);
		
		newGallery.appendChild(newxs);
		newGallery.appendChild(newxs4);

		img_gallery.appendChild(newGallery);
	}

    var fuploader = new qq.FineUploader({
        debug: true,
        element: document.getElementById('fine-fuploader'),
        request: {
            endpoint: '/include/endpoint.php'
        },
        deleteFile: {
            enabled: true,
            endpoint: '/include/endpoint.php'
        },
        retry: {
           enableAuto: true
        },
        callbacks: {
            onComplete: function (id, name, obj) {
                SetuFileField('/style/images/files/'+obj.uuid+'/'+name, name);
            },
            onAllComplete: function () {
                this.reset();
            }
        }
    });
    
    $(function(){ 
        $('#file_gallery').dad({draggable:'.draggable'});
    });
    
</script>
<?php			
		}
	}
	
	/*
	 * Admin Functions
	 */
	
	public function call_back ($addr) {
		echo '<a class="pull-right rt_m" href="'.$addr.'"><i class="fa fa-arrow-left"></i>&nbsp;'.$this->get_string(17).'</a>';
	}
    
    public function get_language ($var) {
        if ($var == 1) return "portuguese";
        if ($var == 2) return "english";
        if ($var == 3) return "german";
        
        return "portuguese";
    }
    
    public function get_string ($id) {
        $field = 'string'.$id;
        $lang = $this->get_language($this->language);
        return $this->strings->$field->$lang;
    }
	
	public function call_modules () {
		if (in_array('multiupload', $this->modules_list)) {
?>
			  <div class="form-group<?php if (in_array('frm20', $this->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label"><?php echo $this->get_string(55); ?></label>
                <div class="form-control rt_h">
                  <div id="fine-uploader"></div>
                  <div class="clearfix"></div>
                  <div id="img_gallery" class="rt_i">
                  <?php
				  if ($this->form_fetchvalue('frm20') != '' && count($this->form_values['frm20']) > 0) {
					for ($cnt_g = 0; $cnt_g < count($this->form_values['frm20']); $cnt_g++) {
						if ($this->form_values['frm20'][$cnt_g] != "") {
				  ?>
                  	<div id="r<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <img src="<?php echo $this->form_values['frm20'][$cnt_g]; ?>" class="img-thumbnail draggable" id="i<?php echo $cnt_g + 1; ?>" />
                        <input type="hidden" name="f_frm20[]" value="<?php echo $this->form_values['frm20'][$cnt_g]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm100[]" value="<?php echo $this->form_values['frm100'][$cnt_g]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveElement('r<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
                  <?php	  
						}
					}
				  }
				  ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
<?php			
		}
		if (in_array('multiupload-e', $this->modules_list)) {
?>
			  <div class="form-group<?php if (in_array('frm20', $this->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label"><?php echo $this->get_string(55); ?></label>
                <div class="form-control rt_h">
                  <div id="fine-uploader"></div>
                  <div class="clearfix"></div>
                  <div id="img_gallery" class="rt_i">
                  <?php
				  $temp_gal = (isset($this->form_values['frm20'])) ? $this->form_values['frm20'] : $this->item['dt_galeria'];
				  if (isset($this->form_values['frm20'])) {
					  if (count($this->form_values['frm20']) > 0) {
						for ($cnt_g = 0; $cnt_g < count($this->form_values['frm20'][0]); $cnt_g++) {
							if ($this->form_values['frm20'][$cnt_g] != "") {
				  ?>
                  	<div id="r<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <img src="<?php echo $this->form_values['frm20'][$cnt_g]; ?>" class="img-thumbnail draggable" id="i<?php echo $cnt_g + 1; ?>" />
                        <input type="hidden" name="f_frm20[]" value="<?php echo $this->form_values['frm20'][$cnt_g]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm100[]" value="<?php echo $this->form_values['frm100'][$cnt_g]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveElement('r<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
					  <?php	  
                            }
                        }
                      }
				  } else {
					  if ($this->item['dt_galeria'] != "") {
						$gal_a = explode('--$$--', $this->item['dt_galeria']);
						$gal_arr = array();
						foreach ($gal_a as $igal) {
							$gal_arr[] = explode('++$$++', $igal);
						}
						for ($cnt_g = 0; $cnt_g < count($gal_arr); $cnt_g++) {
							if ($gal_arr[$cnt_g][0] != "") {
				  ?>
                  	<div id="r<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <img src="<?php echo $gal_arr[$cnt_g][0]; ?>" class="img-thumbnail draggable" id="i<?php echo $cnt_g + 1; ?>" />
                        <input type="hidden" name="f_frm20[]" value="<?php echo $gal_arr[$cnt_g][0]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm100[]" value="<?php echo $gal_arr[$cnt_g][1]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveElement('r<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
					  <?php	  
                            }
                        }
					  }
				  }
				  ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
<?php			
		}
        if (in_array('multifileupload', $this->modules_list)) {
?>
			  <div class="form-group<?php if (in_array('frm30', $this->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label"><?php echo $this->get_string(112); ?></label>
                <div class="form-control rt_h">
                  <div id="fine-fuploader"></div>
                  <div class="clearfix"></div>
                  <div id="file_gallery" class="rt_i">
                  <?php
				  if ($this->form_fetchvalue('frm30') != '' && count($this->form_values['frm30']) > 0) {
					for ($cnt_g = 0; $cnt_g < count($this->form_values['frm30']); $cnt_g++) {
						if ($this->form_values['frm30'][$cnt_g] != "") {
				  ?>
                  	<div id="f<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <i class="fa fa-file fa-4x draggable"></i>
                        <input type="hidden" name="f_frm30[]" value="<?php echo $this->form_values['frm30'][$cnt_g]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm200[]" value="<?php echo $this->form_values['frm200'][$cnt_g]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveFileElement('f<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
                  <?php	  
						}
					}
				  }
				  ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
<?php			
		}
		if (in_array('multifileupload-e', $this->modules_list)) {
?>
			  <div class="form-group<?php if (in_array('frm30', $this->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label"><?php echo $this->get_string(112); ?></label>
                <div class="form-control rt_h">
                  <div id="fine-fuploader"></div>
                  <div class="clearfix"></div>
                  <div id="file_gallery" class="rt_i">
                  <?php
				  $temp_gal = (isset($this->form_values['frm30'])) ? $this->form_values['frm30'] : $this->item['dt_arquivos'];
				  if (isset($this->form_values['frm30'])) {
					  if (count($this->form_values['frm30']) > 0) {
						for ($cnt_g = 0; $cnt_g < count($this->form_values['frm30'][0]); $cnt_g++) {
							if ($this->form_values['frm30'][$cnt_g] != "") {
				  ?>
                  	<div id="f<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <i class="fa fa-file fa-4x draggable"></i>
                        <input type="hidden" name="f_frm30[]" value="<?php echo $this->form_values['frm30'][$cnt_g]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm200[]" value="<?php echo $this->form_values['frm200'][$cnt_g]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveFileElement('f<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
					  <?php	  
                            }
                        }
                      }
				  } else {
					  if ($this->item['dt_arquivos'] != "") {
						$gal_a = explode('--$$--', $this->item['dt_arquivos']);
						$gal_arr = array();
						foreach ($gal_a as $igal) {
							$gal_arr[] = explode('++$$++', $igal);
						}
						for ($cnt_g = 0; $cnt_g < count($gal_arr); $cnt_g++) {
							if ($gal_arr[$cnt_g][0] != "") {
				  ?>
                  	<div id="f<?php echo $cnt_g + 1; ?>" class="row">
                      <div class="col-xs-2">
                        <i class="fa fa-file fa-4x draggable"></i>
                        <input type="hidden" name="f_frm30[]" value="<?php echo $gal_arr[$cnt_g][0]; ?>" />
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" placeholder="<?php echo $this->get_string(82); ?>" name="f_frm200[]" value="<?php echo $gal_arr[$cnt_g][1]; ?>">
                        <p><a href="#" class="btn btn-danger rt_p" onClick="RemoveFileElement('f<?php echo $cnt_g + 1; ?>'); return false;"><?php echo $this->get_string(25); ?></a></p>
                      </div>
                    </div>
					  <?php	  
                            }
                        }
					  }
				  }
				  ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
<?php			
		}
	}
	
	public function get_categories () {
		$query = "SELECT * FROM sdk_categorias WHERE dt_cat_ativado=1";
		$result = $this->sql_db($query);
	
		$items = array();
		while ($line = $this->fetch_array_db($result)) {
			$items[] = $line;
		}

		$childs = array();

        if (count($items) > 0) {
            foreach($items as &$item) {
                $childs[$item['dt_cat_pai']][] = &$item;
            }
            unset($item);

            foreach($items as &$item) {
                if (isset($childs[$item['dt_cat_id']])) $item['dt_cat_childs'] = $childs[$item['dt_cat_id']];
            }

            return $childs[0];
        }
        
        return $childs;
	}

	public function echo_tree_categories ($cats, $level, $categ) {
						
		if (count($cats) > 0) {
            foreach ($cats as $cat) {
                echo '<option value="'.$cat['dt_cat_id'].'"';
                if ($categ == $cat['dt_cat_id']) echo ' selected="selected"';
                echo'>';
                for ($x = 1; $x <= $level; $x++) echo '-';
                if ($level > 0) echo '&nbsp;';
                echo $cat['dt_cat_titulo'].'</option>';
                if (isset($cat['dt_cat_childs'])) $this->echo_tree_categories($cat['dt_cat_childs'], $level + 1, $categ);
            }
        }
	
	}
    
}
?>
