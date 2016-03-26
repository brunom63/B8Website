<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(15, $website->user_permissao)) $website->page_redirect('index.php');
	
$website->item_id = $website->db_validate('sdk_compras');

$website->modules_list = array('datetimepicker');

$website->form_trigger = 'f_send';
$website->form_populate = array(
	'frm4'  => array('bigtext', FALSE, 'Data Fim'),
	'frm5'  => array('number', FALSE, 'Ativado'),
	'frm6'  => array('number', FALSE, 'Finalizado'),
    'frm7'  => array('number', FALSE, 'Pagamento'),
);	
	
if ($website->form_checktrigger()) {
	$website->form_values = $website->form_getvalues($website->form_populate);
	
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
	
	if ($website->form_values['frm5'] == "") $website->form_values['frm5'] = 0;
	if ($website->form_values['frm6'] == "") $website->form_values['frm6'] = 0;
    
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$sql = "UPDATE sdk_compras SET dt_data_entrega=".$website->form_values['frm4n'].", dt_ativado=".$website->form_values['frm5'].", dt_finalizado=".$website->form_values['frm6'].", dt_pagamento=".$website->form_values['frm7']." WHERE ".$website->db_id."=".$website->item_id;
		$website->sql_db($sql);					
		
	    $website->form_values = array();
		$website->str_error = $website->get_string(107);
		$website->str_error_type = 1;
    }
    else {
        $website->str_error = $website->get_string(104);
    }
}
	
$query = "SELECT * FROM sdk_compras WHERE ".$website->db_id."=".$website->item_id;
$result = $website->sql_db($query);
$website->item = $website->fetch_array_db($result);

$query = "SELECT * FROM sdk_cadastrados WHERE dt_id=".$website->item['dt_cadastrado'];
$result = $website->sql_db($query);
$cadastrado = $website->fetch_array_db($result);

$query = "SELECT * FROM sdk_produtos WHERE dt_id=".$website->item['dt_produto'];
$result = $website->sql_db($query);
$produto = $website->fetch_array_db($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(120); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(15); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-money fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(120); ?></span> <small><?php echo $website->get_string(16); ?></small><?php $website->call_back('compras.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="compras_editar.php?i=<?php echo $website->item[$website->db_hash]; ?>">
		  <div class="row">
			<div class="col-md-6">
                <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(121); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $cadastrado['dt_usuario']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(121); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <a href="cadastrados_editar.php?i=<?php echo $cadastrado['dt_hash']; ?>"><?php echo $cadastrado['dt_id']; ?></a>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(129); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $website->item['dt_cep']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(130); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $website->item['dt_endereco']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="frm5" class="col-sm-3 control-label"><?php echo $website->get_string(78); ?></label>
                    <div class="col-sm-3 rt_d">
                      <input type="checkbox" id="frm5" name="f_frm5" value="1"<?php echo (isset($website->form_values['frm5'])) ? (($website->form_values['frm5'] == 1) ? ' checked="checked"' : '') : (($website->item['dt_ativado'] == 1) ? ' checked="checked"' : ''); ?>>
                    </div>
                    <label for="frm6" class="col-sm-3 control-label"><?php echo $website->get_string(114); ?></label>
                    <div class="col-sm-3 rt_d">
                      <input type="checkbox" id="frm6" name="f_frm6" value="1"<?php echo (isset($website->form_values['frm6'])) ? (($website->form_values['frm6'] == 1) ? ' checked="checked"' : '') : (($website->item['dt_finalizado'] == 1) ? ' checked="checked"' : ''); ?>>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(122); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $produto['dt_titulo']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(126); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <a href="produtos_editar.php?i=<?php echo $produto['dt_hash']; ?>"><?php echo $produto['dt_id']; ?></a>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(127); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $website->item['dt_id']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(125); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo $website->item['dt_hash']; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(123); ?></label>
                    <div class="col-sm-9 rt_fixtext">
                      <?php echo date("d/m/y H:i", $website->item['dt_data_compra']); ?>
                    </div>
                  </div>
                </div>
            </div>
		  </div>
          <div class="row">
			<div class="col-md-12 rt_e">
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(115); ?></label>
                <p><?php echo $website->item['dt_detalhes']; ?></p>
              </div>
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(116); ?></label>
                <p><?php echo $website->item['dt_valor']; ?></p>
              </div>
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(117); ?></label>
                <p><?php echo $website->item['dt_quantidade']; ?></p>
              </div>
              <div class="form-group">
                <label for="frm11"><?php echo $website->get_string(131); ?></label>
                <p><?php echo $website->item['dt_frete']; ?></p>
              </div>
              <div class="form-group<?php if (in_array('frm7', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label for="frm7" class="control-label"><?php echo $website->get_string(132); ?></label>
                <input type="text" class="form-control" id="frm7" name="f_frm7" placeholder="<?php echo $website->get_string(132); ?>" value="<?php echo $website->form_fetchvalue('frm7', $website->item['dt_pagamento']); ?>">
                <?php if (in_array('frm7', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group<?php if (in_array('frm4', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label for="frm4" class="control-label"><?php echo $website->get_string(124); ?></label>
                <input type="text" class="form-control datepick" id="frm4" name="f_frm4" placeholder="<?php echo $website->get_string(124); ?>" value="<?php echo $website->form_fetchvalue('frm4', ($website->item['dt_data_entrega'] == "" || $website->item['dt_data_entrega'] == 0) ? '' : date("d/m/Y H:i", $website->item['dt_data_entrega'])); ?>">
                <?php if (in_array('frm4', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
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
