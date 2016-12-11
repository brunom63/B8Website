<?php
include 'include/include.php';

$website = new B8Admin();
$website->db_id = 'dt_cat_id';
$website->db_hash = 'dt_cat_hash';

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(1, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array('datetimepicker', 'ckfinder', 'multiupload-e');
	
$website->item_id = $website->db_validate('sdk_categorias');

$website->form_trigger = 'f_send';
$website->form_populate = array(
	'frm1'  => array('bigtext', FALSE, 'Autor'),
	'frm7'  => array('bigtext', FALSE, 'Alias'),
	'frm2'  => array('number', FALSE, 'Categoria'),
	'frm3'  => array('bigtext', FALSE, 'Data Início'),
	'frm4'  => array('bigtext', FALSE, 'Data Fim'),
	'frm5'  => array('number', FALSE, 'Ativado'),
	'frm6'  => array('number', FALSE, 'Destaque'),
	'frm8'  => array('bigtext', FALSE, 'Meta Descrição'),
	'frm9'  => array('hugtext', FALSE, 'Meta Tags'),	
	'frm10' => array('bigtext', TRUE, 'Título'),
	'frm11' => array('hugtext', FALSE, 'Descrição'),
	'frm20' => array('array', FALSE, 'Upload Imagens'),
	'frm100' => array('array', FALSE, 'Upload Imagens - Legenda'),    
);	
	
if ($website->form_checktrigger()) {
	$website->form_values = $website->form_getvalues($website->form_populate);
	
	if ($website->form_values['frm3'] != "") {
		$get_data = $website->data_converter($website->form_values['frm3']);
		if ($get_data == 0) {
			$website->form_status = 'WRONG';
			$website->form_wrong[] = 'frm3';
		} else {
			$website->form_values['frm3n'] = $get_data;
		}
	} else {
		$website->form_values['frm3n'] = 'NULL';
	}
	if ($website->form_values['frm4'] != "") {
		$get_data = $website->data_converter($website->form_values['frm4']);
		if ($get_data == 0) {
			$website->form_status = 'WRONG';
			$website->form_wrong[] = 'frm4';
		} else {
			$website->form_values['frm4n'] = $get_data;
		}
	} else {
		$website->form_values['frm4n'] = 'NULL';
	}
	
	if ($website->form_values['frm2'] != "" && $website->form_values['frm2'] != 0) {
		$website->form_values['frm2n'] = $website->form_values['frm2'];
		if (!$website->validate_id('sdk_categorias', $website->form_values['frm2n'])) $website->page_redirect('atencao.php');
	} else {
		$website->form_values['frm2n'] = 0;
	}
    
    $website->form_values['frm20n'] = '';
    if (is_array($website->form_values['frm20'])) {
        $bd_gallery = array();
        for ($x = 0; $x < count($website->form_values['frm20']); $x++) {
            $bd_gallery[] = $website->form_values['frm20'][$x].'++$$++'.$website->form_values['frm100'][$x];
        }
        $website->form_values['frm20n'] = implode('--$$--', $bd_gallery);
    }
	
	if ($website->form_values['frm5'] == "") $website->form_values['frm5'] = 0;
	if ($website->form_values['frm6'] == "") $website->form_values['frm6'] = 0;
	
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$sql = "UPDATE sdk_categorias SET dt_cat_pai=".$website->form_values['frm2n'].", dt_cat_titulo='".$website->form_values['frm10']."', dt_cat_alias='".$website->form_values['frm7']."', dt_cat_autor='".$website->form_values['frm1']."', dt_cat_data_inicio=".$website->form_values['frm3n'].", dt_cat_data_fim=".$website->form_values['frm4n'].", dt_cat_ativado=".$website->form_values['frm5'].", dt_cat_destaque=".$website->form_values['frm6'].", dt_cat_alterado_data=".$website->data_hj.", dt_cat_corpo='".$website->form_values['frm11']."', dt_cat_galeria='".$website->form_values['frm20n']."', dt_cat_meta_descricao='".$website->form_values['frm8']."', dt_cat_meta_tags='".$website->form_values['frm9']."' WHERE ".$website->db_id."=".$website->item_id;
		$website->sql_db($sql);					
		
	    $website->form_values = array();
		$website->str_error = $website->get_string(107);
		$website->str_error_type = 1;
    }
    else {
        $website->str_error = $website->get_string(104);
    }
}
	
$query = "SELECT * FROM sdk_categorias WHERE ".$website->db_id."=".$website->item_id;
$result = $website->sql_db($query);
$website->item = $website->fetch_array_db($result);
	
$query = "SELECT * FROM sdk_admlogin WHERE dt_id=".$website->item['dt_cat_criado_por'];
$result = $website->sql_db($query);
$criado = $website->fetch_array_db($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(2); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(2); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-folder-open fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(2); ?></span> <small><?php echo $website->get_string(16); ?></small><?php $website->call_back('categorias.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="categorias_editar.php?i=<?php echo $website->item[$website->db_hash]; ?>">
		  <div class="row">
			<div class="col-md-6">
                <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label for="frm2" class="col-sm-3 control-label"><?php echo $website->get_string(29); ?></label>
                    <div class="col-sm-9">
                      <select id="frm2" name="f_frm2" class="form-control">
                        <option value=""></option>
                      	<?php
						$website->echo_tree_categories($website->get_categories(), 0, $website->form_fetchvalue('frm2n', $website->item['dt_cat_pai']));
						?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm1" class="col-sm-3 control-label"><?php echo $website->get_string(42); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="frm1" name="f_frm1" placeholder="<?php echo $website->get_string(42); ?>" value="<?php echo $website->form_fetchvalue('frm1', $website->item['dt_cat_autor']); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm7" class="col-sm-3 control-label"><?php echo $website->get_string(43); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="frm7" name="f_frm7" placeholder="<?php echo $website->get_string(43); ?>" value="<?php echo $website->form_fetchvalue('frm7', $website->item['dt_cat_alias']); ?>">
                    </div>
                  </div>
                  <div class="form-group<?php if (in_array('frm3', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                    <label for="frm3" class="col-sm-3 control-label"><?php echo $website->get_string(44); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control datepick" id="frm3" name="f_frm3" placeholder="<?php echo $website->get_string(44); ?>" value="<?php echo $website->form_fetchvalue('frm3', ($website->item['dt_cat_data_inicio'] == '' || $website->item['dt_cat_data_inicio'] == 0) ? '' : date("d/m/Y H:i", $website->item['dt_cat_data_inicio'])); ?>">
					  <?php if (in_array('frm3', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
                    </div>
                  </div>
                  <div class="form-group<?php if (in_array('frm4', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                    <label for="frm4" class="col-sm-3 control-label"><?php echo $website->get_string(45); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control datepick" id="frm4" name="f_frm4" placeholder="<?php echo $website->get_string(45); ?>" value="<?php echo $website->form_fetchvalue('frm4', ($website->item['dt_cat_data_fim'] == "" || $website->item['dt_cat_data_fim'] == 0) ? '' : date("d/m/Y H:i", $website->item['dt_cat_data_fim'])); ?>">
					  <?php if (in_array('frm4', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm5" class="col-sm-3 control-label"><?php echo $website->get_string(78); ?></label>
                    <div class="col-sm-9 rt_d">
                      <input type="checkbox" id="frm5" name="f_frm5" value="1"<?php echo (isset($website->form_values['frm5'])) ? (($website->form_values['frm5'] == 1) ? ' checked="checked"' : '') : (($website->item['dt_cat_ativado'] == 1) ? ' checked="checked"' : ''); ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm6" class="col-sm-3 control-label"><?php echo $website->get_string(28); ?></label>
                    <div class="col-sm-9 rt_d">
                      <input type="checkbox" id="frm6" name="f_frm6" value="1"<?php echo (isset($website->form_values['frm6'])) ? (($website->form_values['frm6'] == 1) ? ' checked="checked"' : '') : (($website->item['dt_cat_destaque'] == 1) ? ' checked="checked"' : ''); ?>>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label for="frm8" class="col-sm-3 control-label"><?php echo $website->get_string(46); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="frm8" name="f_frm8" placeholder="<?php echo $website->get_string(46); ?>" value="<?php echo $website->form_fetchvalue('frm8', $website->item['dt_cat_meta_descricao']); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm9" class="col-sm-3 control-label"><?php echo $website->get_string(47); ?></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" rows="3" id="frm9" name="f_frm9" placeholder="<?php echo $website->get_string(47); ?>"><?php echo $website->form_fetchvalue('frm9', $website->item['dt_cat_meta_tags']); ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(32); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $website->item['dt_cat_id']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(31); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $website->item['dt_cat_hits']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(48); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $criado['dt_campo1']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(49); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static"><?php echo date("d/m/y H:i", $website->item['dt_cat_criado_data']); ?></p>
                    </div>
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(50); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static"><?php if ($website->item['dt_cat_alterado_data'] != "") echo date("d/m/y H:i", $website->item['dt_cat_alterado_data']); ?></p>
                    </div>
                  </div>
                </div>
            </div>
		  </div>
          <div class="row">
			<div class="col-md-12 rt_e">
              <div class="form-group<?php if (in_array('frm10', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm10"><?php echo $website->get_string(26); ?>&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="frm10" placeholder="<?php echo $website->get_string(26); ?>" name="f_frm10" value="<?php echo $website->form_fetchvalue('frm10', $website->item['dt_cat_titulo']); ?>">
                <?php if (in_array('frm10', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(46); ?></label>
                <textarea class="form-control ckeditor" id="frm11" name="f_frm11"><?php echo $website->form_fetchvalue('frm11', $website->item['dt_cat_corpo']); ?></textarea>
              </div>
              <?php $website->call_modules(); ?>
              <div class="form-group rt_f">
                <button type="submit" class="btn btn-success" name="f_send" value="Salvar"><?php echo $website->get_string(68); ?></button>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>  
  </div>
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
