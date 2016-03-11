<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(9, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array('datetimepicker', 'ckfinder');
	
$website->form_trigger = 'f_send';
$website->form_populate = array(
	'frm2'  => array('number', FALSE, 'Lista'),
	'frm3'  => array('bigtext', FALSE, 'Data Início'),
	'frm4'  => array('bigtext', FALSE, 'Data Fim'),
	'frm5'  => array('number', FALSE, 'Ativado'),
	'frm6'  => array('number', FALSE, 'Especial'),
	'frm10' => array('bigtext', TRUE, 'Usuário'),
	'frm11' => array('hugtext', FALSE, 'Informações'),
    'frm12' => array('bigtext', FALSE, 'Nome'),
    'frm13' => array('bigtext', FALSE, 'E-mail'),
    'frm21' => array('bigtext', FALSE, 'Senha'),
    'frm22' => array('bigtext', FALSE, 'Repetir Senha'),
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
		$website->db_id = 'dt_cat_id';
		if (!$website->validate_id('sdk_categorias', $website->form_values['frm2n'])) $website->page_redirect('atencao.php?er=cat');
		$website->db_id = 'dt_id';
	} else {
		$website->form_values['frm2n'] = 0;
	}

    if ($website->form_status == 'ALLOW' && $website->validate_bdfield('sdk_cadastrados', 'dt_usuario', $website->form_values['frm10'])) {  
        $website->form_status = 'VALIDUSER';
        $website->form_wrong[] = 'frm10';
    }
    
    if ($website->form_values['frm21'] != '' || $website->form_values['frm22'] != '') {
        if ($website->form_status == 'ALLOW') {
            if ($website->form_values['frm21'] != $website->form_values['frm22']) {
                $website->form_status = 'WRONGSENHA';
                $website->form_wrong[] = 'frm21';
                $website->form_wrong[] = 'frm22';
            } else {
                $website->form_values['frm21'] = $website->pass_crypt($website->form_values['frm21']);
            }
        }
    }
	
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$sql = "INSERT INTO sdk_cadastrados VALUES (NULL, '".$website->db_hashfield('sdk_cadastrados')."', '".$website->form_values['frm10']."', '".$website->form_values['frm21']."', '".$website->form_values['frm6']."', NULL, ".$website->form_values['frm2n'].", '".$website->form_values['frm3n']."', '".$website->form_values['frm4n']."', '".$website->form_values['frm5']."', ".$website->p_id.", ".$website->data_hj.", NULL, '".$website->form_values['frm11']."', '".$website->form_values['frm12']."', '".$website->form_values['frm13']."', NULL, 0)";
        $website->sql_db($sql);					
		
	    $website->page_redirect('cadastrados.php');
    }
    else if ($website->form_status == 'VALIDUSER') {
        $website->str_error = $website->get_string(105);
    }
    else if ($website->form_status == 'WRONGSENHA') {
        $website->str_error = $website->get_string(106);
    }
    else {
        $website->str_error = $website->get_string(104);
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(7); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(11); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-globe fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(7); ?></span> <small><?php echo $website->get_string(15); ?></small><?php $website->call_back('cadastrados.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="cadastrados_inserir.php">
		  <div class="row">
			<div class="col-md-6">
                <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label for="frm2" class="col-sm-3 control-label"><?php echo $website->get_string(33); ?></label>
                    <div class="col-sm-9">
                      <select id="frm2" name="f_frm2" class="form-control">
                        <option value=""></option>
                      	<?php
						$website->echo_tree_categories($website->get_categories(), 0, $website->form_fetchvalue('frm2n'));
						?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group<?php if (in_array('frm3', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                    <label for="frm3" class="col-sm-3 control-label"><?php echo $website->get_string(44); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control datepick" id="frm3" name="f_frm3" placeholder="<?php echo $website->get_string(44); ?>" value="<?php echo $website->form_fetchvalue('frm3'); ?>">
					  <?php if (in_array('frm3', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
                    </div>
                  </div>
                  <div class="form-group<?php if (in_array('frm4', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                    <label for="frm4" class="col-sm-3 control-label"><?php echo $website->get_string(45); ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control datepick" id="frm4" name="f_frm4" placeholder="<?php echo $website->get_string(45); ?>" value="<?php echo $website->form_fetchvalue('frm4'); ?>">
					  <?php if (in_array('frm4', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm5" class="col-sm-3 control-label"><?php echo $website->get_string(78); ?></label>
                    <div class="col-sm-9 rt_d">
                      <input type="checkbox" id="frm5" name="f_frm5" value="1"<?php if (!isset($website->form_values['frm5']) || $website->form_values['frm5'] == 1) echo ' checked="checked"'; ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm6" class="col-sm-3 control-label"><?php echo $website->get_string(57); ?></label>
                    <div class="col-sm-9 rt_d">
                      <input type="checkbox" id="frm6" name="f_frm6" value="1"<?php if ($website->form_fetchvalue('frm6') == 1) echo ' checked="checked"'; ?>>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(32); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static">0</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(66); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static">0</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(48); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static">&nbsp;</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(49); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static">&nbsp;</p>
                    </div>
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(50); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static">&nbsp;</p>
                    </div>
                  </div>
                </div>
            </div>
		  </div>
          <div class="row">
			<div class="col-md-12 rt_e">
              <div class="form-group<?php if (in_array('frm10', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm10"><?php echo $website->get_string(58); ?>&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="frm10" placeholder="<?php echo $website->get_string(58); ?>" name="f_frm10" value="<?php echo $website->form_fetchvalue('frm10'); ?>">
                <?php if (in_array('frm10', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group">
                <label class="control-label" for="frm12"><?php echo $website->get_string(36); ?></label>
                <input type="text" class="form-control" id="frm12" placeholder="<?php echo $website->get_string(36); ?>" name="f_frm12" value="<?php echo $website->form_fetchvalue('frm12'); ?>">
              </div>
              <div class="form-group">
                <label class="control-label" for="frm13"><?php echo $website->get_string(37); ?></label>
                <input type="text" class="form-control" id="frm13" placeholder="<?php echo $website->get_string(37); ?>" name="f_frm13" value="<?php echo $website->form_fetchvalue('frm13'); ?>">
              </div>
              <div class="form-group<?php if (in_array('frm21', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm21"><?php echo $website->get_string(59); ?></label>
                <input type="password" class="form-control" id="frm21" placeholder="<?php echo $website->get_string(59); ?>" name="f_frm21" value="<?php echo $website->form_fetchvalue('frm21'); ?>">
                <?php if (in_array('frm21', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group<?php if (in_array('frm22', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm22"><?php echo $website->get_string(60); ?></label>
                <input type="password" class="form-control" id="frm22" placeholder="<?php echo $website->get_string(60); ?>" name="f_frm22" value="<?php echo $website->form_fetchvalue('frm22'); ?>">
                <?php if (in_array('frm22', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(61); ?></label>
                <textarea class="form-control ckeditor" id="frm11" name="f_frm11"><?php echo $website->form_fetchvalue('frm11'); ?></textarea>
              </div>
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
