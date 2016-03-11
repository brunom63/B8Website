<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if ($website->user_array['dt_admin'] != 1) $website->page_redirect('index.php');
	
$website->form_trigger = 'f_send';
$website->form_populate = array(
	'frm1'  => array('bigtext', FALSE, 'Nome'),
	'frm2'  => array('bigtext', FALSE, 'Endereço'),
    'frm3'  => array('number', FALSE, 'Idioma'),
);	
	
if ($website->form_checktrigger()) {
	$website->form_values = $website->form_getvalues($website->form_populate);
	
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$sql = "UPDATE sdk_admsettings SET dt_email_name='".$website->form_values['frm1']."', dt_email_addr='".$website->form_values['frm2']."', dt_language='".$website->form_values['frm3']."' WHERE ".$website->db_id."=1";
		$website->sql_db($sql);					
		
        $website->language = $website->form_values['frm3'];
	    $website->form_values = array();
		$website->str_error = $website->get_string(107);
		$website->str_error_type = 1;
    }
    else {
        $website->str_error = $website->get_string(104);
    }
}
	
$query = "SELECT * FROM sdk_admsettings WHERE ".$website->db_id."=1";
$result = $website->sql_db($query);
$website->item = $website->fetch_array_db($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(10); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(13); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-cogs fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(10); ?></span><?php $website->call_back('main.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="configuracoes.php">
          <div class="row">
			<div class="col-md-12 rt_e">
              <div class="form-group rt_o"><?php echo $website->get_string(69); ?></div>
              <div class="form-group">
                <label for="frm1"><?php echo $website->get_string(70); ?></label>
                <input type="text" class="form-control" id="frm1" placeholder="<?php echo $website->get_string(36); ?>" name="f_frm1" value="<?php echo $website->form_fetchvalue('frm1', $website->item['dt_email_name']); ?>">
              </div>
              <div class="form-group">
                <label for="frm1"><?php echo $website->get_string(71); ?></label>
                <input type="text" class="form-control" id="frm2" placeholder="<?php echo $website->get_string(76); ?>" name="f_frm2" value="<?php echo $website->form_fetchvalue('frm2', $website->item['dt_email_addr']); ?>">
              </div>
              <div class="form-group rt_o"><?php echo $website->get_string(86); ?></div>
              <div class="form-group">
                <?php $language_opts = $website->form_fetchvalue('frm3', $website->item['dt_language']); ?>
                <label for="frm3"><?php echo $website->get_string(86); ?></label>
                <select id="frm3" name="f_frm3" class="form-control">
                    <option value=""></option>
                    <option value="1"<?php if ($language_opts == 1) echo 'selected="selected"'; ?>><?php echo $website->get_string(87); ?></option>
                    <option value="2"<?php if ($language_opts == 2) echo 'selected="selected"'; ?>><?php echo $website->get_string(88); ?></option>
                    <option value="3"<?php if ($language_opts == 3) echo 'selected="selected"'; ?>><?php echo $website->get_string(89); ?></option>
                </select>
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
