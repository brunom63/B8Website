<?php
include 'include/include.php';

$c_cookie = $_REQUEST['c'];
$website = new B8Admin($c_cookie);

set_time_limit(0);

if ($website->p_id == 0) exit;
if (!in_array(8, $website->user_permissao)) exit;

$c_host = $_REQUEST['h'];

$query = "SELECT * FROM sdk_admsettings WHERE dt_id=1";
$result = $website->sql_db($query);
$settings = $website->fetch_array_db($result);

$opts_arr = explode(",", $_REQUEST['o']);

$news_arr = array();
foreach ($opts_arr as $op) {
    if ($op == "") continue;

    $query = "SELECT * FROM sdk_newsletters WHERE dt_id=".$op;
    $result = $website->sql_db($query);
    $line = $website->fetch_array_db($result);

    $cad_arr = array();
    if ($line['dt_categoria'] != "" && $line['dt_categoria'] != 0) {
        $query = "SELECT * FROM sdk_cadastrados WHERE dt_ativado=1 AND dt_categoria=".$line['dt_categoria'];
        $result = $website->sql_db($query);
        while ($line2 = $website->fetch_array_db($result)) {
            if ($line2['dt_campo2'] == "") continue;
            $cad_arr[] = $line2;	
        }
    } else {
        $query = "SELECT * FROM sdk_cadastrados WHERE dt_ativado=1";
        $result = $website->sql_db($query);
        while ($line2 = $website->fetch_array_db($result)) {
            if ($line2['dt_campo2'] == "") continue;
            $cad_arr[] = $line2;	
        }
    }

    $line['cadastrados'] = $cad_arr;
    $news_arr[] = $line;
}

foreach ($news_arr as $news) {
    $hit_control = '<img src="http://'.$c_host.'/newsletter_hits.php?i='.$news['dt_hash'].'" width="0" height="0">';
    $news_body = $news['dt_corpo'].$hit_control;

    $return = '';
    $return .= "INICIO: ".date("d", $website->data_hj)."/".date("m", $website->data_hj)."/".date("Y", $website->data_hj)."<br><br>";

    $e_cnt = 0;
    foreach ($news['cadastrados'] as $cad) {
        $e_cnt++;

        $mail_stat = $website->send_email($cad['dt_campo2'], $news['dt_titulo'],  
                   $news_body, $settings['dt_email_name'].' <'.$settings['dt_email_addr'].'>');

        if ($mail_stat) {
            $return .= $cad['dt_campo2']."... OK<br>";
        } else {
            $return .= $cad['dt_campo2']."... N&Atilde;O ENVIADO<br>";
        }
    }

    $return .= "<br>FIM: ".$e_cnt. " email(s) enviado(s).<br><br>";

    $envi = $news['dt_enviados'] + $e_cnt;
    $rela = $news['dt_relatorio'].$return;

    $sql = "UPDATE sdk_newsletters SET dt_enviados=".$envi.", dt_relatorio='".$rela."' WHERE dt_id=".$news['dt_id'];
    $website->sql_db($sql);
}

$website->close_db();
?>
