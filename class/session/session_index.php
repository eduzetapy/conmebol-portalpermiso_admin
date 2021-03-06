<?php
    if(!isset($_SESSION)){ 
        session_start(); 
    }

    require '../../class/function/curl_api.php';
    require '../../class/function/function.php';

    $val_01         = $_POST['val_01'];
    $val_02         = $_POST['val_02'];
    $val_03         = $_SERVER['REMOTE_ADDR'];

    $dataJSON       = json_encode(
        array(
            'usuario_var01' => $val_01,
            'usuario_var02' => $val_02,
            'usuario_var03' => $val_03,
            'usuario_var04'	=> $_SERVER['HTTP_HOST'],
            'usuario_var05'	=> $_SERVER['HTTP_USER_AGENT'],
            'usuario_var06'	=> $_SERVER['HTTP_REFERER']
        ));
    
    $resultJSON     = post_curl('login', $dataJSON);
    $resultJSON     = json_decode($resultJSON, true);

    if ($resultJSON['code'] === 200) {
        if ($val_01 == 'czelaya' || $val_01 == 'CZELAYA') {
            $personJSON         = get_curl('000/colaborador/1019125');
        } else {
            $personJSON         = get_curl('000/colaborador/'.$resultJSON['data'][0]['user_var05']);
        }
        
        $_SESSION['log_01'] = trim(strtoupper($val_01));
        $_SESSION['log_02'] = $val_02;
        $_SESSION['log_03'] = $val_03;

        $_SESSION['usu_01'] = $resultJSON['data'][0]['user_var01'];
        $_SESSION['usu_02'] = $resultJSON['data'][0]['user_var02'];
        $_SESSION['usu_03'] = $resultJSON['data'][0]['user_var03'];
        $_SESSION['usu_04'] = $resultJSON['data'][0]['user_var04'];

        if ($val_01 == 'czelaya' || $val_01 == 'CZELAYA') {
            $_SESSION['usu_05'] = '1019125';
        } else {
            $_SESSION['usu_05'] = $resultJSON['data'][0]['user_var05'];
        }

        if (isset($resultJSON['data'][0]['user_var06'])) {
            $_SESSION['usu_06'] = 'data:image/jpeg;base64,'.base64_encode($resultJSON['data'][0]['user_var06']);
        } else {
            $_SESSION['usu_06'] = '../assets/images/users/photo.png';
        }
        
        $_SESSION['usu_07'] = $personJSON['data'][0]['gerencia_codigo'];
        $_SESSION['usu_08'] = $personJSON['data'][0]['gerencia_nombre'];
        $_SESSION['usu_09'] = $personJSON['data'][0]['departamento_codigo'];
        $_SESSION['usu_10'] = $personJSON['data'][0]['departamento_nombre'];
        $_SESSION['usu_11'] = $personJSON['data'][0]['superior_cargo_codigo'];
        $_SESSION['usu_12'] = $personJSON['data'][0]['superior_cargo_nombre'];
        $_SESSION['usu_13'] = $personJSON['data'][0]['cargo_codigo'];
        $_SESSION['usu_14'] = $personJSON['data'][0]['cargo_nombre'];
        $_SESSION['usu_15'] = $personJSON['data'][0]['email'];
        $_SESSION['usu_16'] = $personJSON['data'][0]['superior_manager_nombre'];
        $_SESSION['usu_17'] = $personJSON['data'][0]['superior_manager_email'];
        
        $_SESSION['expire'] = time() + 1800;
        
        header('Location: ../../public/home.php');
    } else {
        $val_01             = NULL;
        $val_02             = NULL;
        $val_03             = NULL;

        header('Location: ../../index.php?code='.$resultJSON['code'].'&msg='.$resultJSON['message']);
    }
?>