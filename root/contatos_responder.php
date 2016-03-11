<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(10, $website->user_permissao)) $website->page_redirect('index.php');
	
$website->item_id = $website->db_validate('sdk_contatos');

$website->modules_list = array('ckfinder');

$query = "SELECT * FROM sdk_admsettings WHERE dt_id=1";
$result = $website->sql_db($query);
$settings = $website->fetch_array_db($result);

$website->form_trigger = 'f_send';
$website->form_populate = array(
	'frm10' => array('bigtext', TRUE, 'Assunto'),
	'frm11' => array('hugtext', TRUE, 'Mensagem'),
    'frm12' => array('bigtext', TRUE, 'Para'),
    'frm13' => array('bigtext', TRUE, 'De'),
);

if ($website->form_checktrigger()) {
	$website->form_values = $website->form_getvalues($website->form_populate);
	
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$sql = "INSERT INTO sdk_contatos VALUES (NULL, '".$website->db_hashfield('sdk_contatos')."', 2, ".$website->item_id.", '".$website->user_array['dt_campo1']."', '".$website->form_values['frm12']."', '".$website->form_values['frm10']."', '".$website->form_values['frm11']."', ".$website->data_hj.", '".$website->form_values['frm12']."', '".$website->form_values['frm13']."', NULL)";
        $website->sql_db($sql);
        
        $website->send_email($website->form_values['frm12'], $website->form_values['frm10'], $website->form_values['frm11'], $website->form_values['frm13']);

	    $website->page_redirect('contatos.php');
    }
    else {
        $website->str_error = $website->get_string(104);
    }
}
	
$query = "SELECT * FROM sdk_contatos WHERE ".$website->db_id."=".$website->item_id;
$result = $website->sql_db($query);
$item = $website->fetch_array_db($result);

$item_extra = array();
$query = "SELECT * FROM sdk_contatos WHERE dt_pai=".$website->item_id;
$result = $website->sql_db($query);
while ($line = $website->fetch_array_db($result)) {
    $item_extra[] = $line;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(8); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(10); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-clipboard fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(8); ?></span> <small><?php echo $website->get_string(75); ?></small><?php $website->call_back('contatos.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="contatos_responder.php?i=<?php echo $item['dt_hash']; ?>">
		  <div class="row">
            <?php
			if ($item['dt_tipo'] == 1) {
			?>
            <div class="col-md-12">
              <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(36); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_nome']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(30); ?></div>
                    <div class="col-sm-4"><?php echo date("d/m/y H:i", $item['dt_data']); ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(37); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_email']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(32); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_id']; ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(62); ?></div>
                    <div class="col-sm-10"><?php echo $item['dt_mensagem']; ?></div>
                  </div>
              </div>
            </div>
            <?php
			} else {
			?>
            <div class="col-md-12">
              <div class="form-horizontal rt_l">
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(83); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_nome']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(30); ?></div>
                    <div class="col-sm-4"><?php echo date("d/m/y H:i", $item['dt_data']); ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(38); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_titulo']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(32); ?></div>
                    <div class="col-sm-4"><?php echo $item['dt_id']; ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(64); ?></div>
                    <div class="col-sm-10"><?php 
						$it_tmp = $item['dt_campo2'];
						$it_tmp = preg_replace("/</", '&lt;', $it_tmp);
						$it_tmp = preg_replace("/>/", '&gt;', $it_tmp);
						echo $it_tmp;
					?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(63); ?></div>
                    <div class="col-sm-10"><?php echo $item['dt_campo1']; ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(62); ?></div>
                    <div class="col-sm-10"><?php echo $item['dt_mensagem']; ?></div>
                  </div>
              </div>
            </div>
            <?php
			}
			foreach ($item_extra as $item_e) {
			?>
            <div class="col-md-12">
              <div class="form-horizontal rt_l">
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(83); ?></div>
                    <div class="col-sm-4"><?php echo $item_e['dt_nome']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(30); ?></div>
                    <div class="col-sm-4"><?php echo date("d/m/y H:i", $item_e['dt_data']); ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(38); ?></div>
                    <div class="col-sm-4"><?php echo $item_e['dt_titulo']; ?></div>
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(32); ?></div>
                    <div class="col-sm-4"><?php echo $item_e['dt_id']; ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(64); ?></div>
                    <div class="col-sm-10"><?php 
						$it_tmp = $item_e['dt_campo2'];
						$it_tmp = preg_replace("/</", '&lt;', $it_tmp);
						$it_tmp = preg_replace("/>/", '&gt;', $it_tmp);
						echo $it_tmp;
					?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(63); ?></div>
                    <div class="col-sm-10"><?php echo $item_e['dt_campo1']; ?></div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 text-right rt_k"><?php echo $website->get_string(62); ?></div>
                    <div class="col-sm-10"><?php echo $item_e['dt_mensagem']; ?></div>
                  </div>
              </div>
            </div>
            <?php
			}
			?>
		  </div>
		  <div class="row">
			<div class="col-md-12 rt_e">
              <div class="form-group<?php if (in_array('frm10', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm10"><?php echo $website->get_string(38); ?>&nbsp;<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="frm10" placeholder="<?php echo $website->get_string(38); ?>" name="f_frm10" value="<?php echo (isset($website->form_values['frm10'])) ? $website->form_values['frm10'] : 'RE: '.$item['dt_titulo']; ?>">
                <?php if (in_array('frm10', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group<?php if (in_array('frm11', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm11"><?php echo $website->get_string(62); ?></label>
                <textarea class="form-control ckeditor" id="frm11" name="f_frm11"><?php echo (isset($website->form_values['frm11'])) ? $website->form_values['frm11'] : ''; ?></textarea>
                <?php if (in_array('frm11', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group<?php if (in_array('frm12', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm12"><?php echo $website->get_string(63); ?></label>
                <input type="text" class="form-control" id="frm12" placeholder="<?php echo $website->get_string(63); ?>" name="f_frm12" value="<?php echo (isset($website->form_values['frm12'])) ? $website->form_values['frm12'] : $item['dt_email']; ?>">
                <?php if (in_array('frm12', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group<?php if (in_array('frm13', $website->form_wrong)) echo ' has-error has-feedback'; ?>">
                <label class="control-label" for="frm13"><?php echo $website->get_string(64); ?></label>
                <input type="text" class="form-control" id="frm13" placeholder="<?php echo $website->get_string(64); ?>" name="f_frm13" value="<?php echo (isset($website->form_values['frm13'])) ? $website->form_values['frm13'] : (($settings['dt_email_name'] != "") ? $settings['dt_email_name'].' <'.$settings['dt_email_addr'].'>' : ''); ?>">
                <?php if (in_array('frm13', $website->form_wrong)) echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>'; ?>
              </div>
              <div class="form-group rt_f">
                <button type="submit" class="btn btn-success" name="f_send" value="Salvar"><?php echo $website->get_string(77); ?></button>
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
