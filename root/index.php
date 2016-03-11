<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id > 0) $website->page_redirect('main.php');

$website->form_trigger = 'f_send';
$website->form_populate = array(
	'login' => array('medtext', TRUE, 'Login'),
	'senha' => array('password', TRUE, 'Senha')
);
if ($website->form_checktrigger()) {
	$website->form_values = $website->form_getvalues();
	
	if ($website->form_status == 'ALLOW') {			
		// STRING FOR DATABASE
		$query = "SELECT dt_id, dt_usuario, dt_senha, dt_acessos, dt_ativado, dt_data_inicio, dt_data_fim FROM sdk_admlogin";
		$result = $website->sql_db($query);
		$login = 0;
		$acessos = '';
		while ($line = $website->fetch_array_db($result)) {		
			if (preg_match("/".$website->form_values['login']."/i", $line['dt_usuario'])) {
                if ($website->pass_check($website->form_values['senha'], $line['dt_senha'])) {
                    if ($line['dt_ativado'] != 1) break;
                    if ($line['dt_data_inicio'] != '' && $line['dt_data_inicio'] > $website->data_hj) break;
                    if ($line['dt_data_fim'] != '' && $line['dt_data_fim'] < $website->data_hj) break;			

                    $login = $line['dt_id'];
                    $acessos = ($line['dt_acessos'] != '') ? $line['dt_acessos'].','.$website->data_hj : $website->data_hj;
                }
				break;
			}
		}
		if ($login != 0) {
			$sql = "UPDATE sdk_admlogin SET dt_acessos='".$acessos."' WHERE dt_id=".$login;
			$website->sql_db($sql);
		
			$website->session_activate_cookie($login);
	    	$website->page_redirect('main.php');
		} else {
			$website->str_error = "Login ou senha n&atilde;o conferem.";
		}
    }
    else {
        $website->str_error = "Dados inv&aacute;lidos. Preencha todos os campos.";
    }
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(84); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
    <?php $website->call_head(); ?>
  
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 well rt_logn">
                <p class="rt_logerror text-danger" style="display:<?php if ($website->str_error == "") { echo 'none'; } else { echo 'block'; } ?>"><?php echo $website->str_error; ?></p>
                <form role="form" action="index.php" method="post">
                  <div class="form-group rt_logl">
                    <label class="sr-only" for="form_email"><?php echo $website->get_string(58); ?></label>
                    <input name="f_login" type="text" class="form-control label_better" id="form_email" placeholder="<?php echo $website->get_string(58); ?>">
                  </div>
                  <div class="form-group rt_logl">
                    <label class="sr-only" for="form_senha"><?php echo $website->get_string(59); ?></label>
                    <input name="f_senha" type="password" class="form-control label_better" id="form_senha" placeholder="<?php echo $website->get_string(59); ?>">
                  </div>
                  <div class="rt_logl">
                    <button type="submit" name="f_send" class="btn btn-default btn-lg rt_logm"><?php echo $website->get_string(84); ?></button>
                  </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
  
  
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
