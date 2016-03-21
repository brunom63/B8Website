<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(1); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(1); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-home fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(1); ?></span></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <p><?php echo $website->get_string(13); ?> <strong><?php echo $website->user_array['dt_campo1']; ?></strong>!</p>
          <p><?php echo $website->get_string(14); ?> <?php
            $acessos = explode(",", $website->user_array['dt_acessos']);
			$ultimo_acesso = array_pop($acessos);
            if (count($acessos) > 1) {
                $ultimo_acesso = $acessos[count($acessos)-2];
            }
            echo date("d/m/Y H:i", $ultimo_acesso);
		  ?>.</p>
          <h4 class="rt_bdytico">&nbsp;</h4>
          <div class="row rt_bdycol">
            <?php if (in_array(1, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="categorias.php">
                <i class="fa fa-folder-open fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(2); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(2, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="posts.php">
                <i class="fa fa-clipboard fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(3); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(7, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="paginas.php">
                <i class="fa fa-desktop fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(4); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(3, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="imagens.php">
                <i class="fa fa-camera fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(5); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(14, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="produtos.php">
                <i class="fa fa-shopping-cart fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(113); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(15, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="compras.php">
                <i class="fa fa-money fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(120); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(8, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="newsletters.php">
                <i class="fa fa-envelope fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(6); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(9, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="cadastrados.php">
                <i class="fa fa-globe fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(7); ?></span></p>
              </a>
            </div>
            <?php } if (in_array(10, $website->user_permissao)) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="contatos.php">
                <i class="fa fa-comments fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(8); ?></span></p>
              </a>
            </div>
            <?php } ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="<?php echo ($website->user_array['dt_admin'] == 1) ? 'usuarios.php' : 'usuarios_editar.php?i='.$website->user_array['dt_hash']; ?>">
                <i class="fa fa-group fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo ($website->user_array['dt_admin'] == 1) ? $website->get_string(9) : $website->get_string(72); ?></span></p>
              </a>
            </div>
            <?php if ($website->user_array['dt_admin'] == 1) { ?>
            <div class="col-xs-4 text-center">
              <a class="rt_bdymn" href="configuracoes.php">
                <i class="fa fa-cogs fa-4x"></i>
                <p><span class="rt_bdyico"><?php echo $website->get_string(10); ?></span></p>
              </a>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>  
  </div>
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
