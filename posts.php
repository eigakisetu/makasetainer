<?php
header("Content-Type: text/javascript; charset=utf-8");
define('CLIENT_ID', '3MVG9I1kFE5Iul2CIt19RNiJd81L6kMcpQPq_0krjDfVNfPaWQ83AjQIHQKMhmkgcfLbzAn0BS3ien_UDc4T4');
define('CLIENT_SECRET', '6876272078059479918');
define('CALLBACK_URL', 'http://localhost:9000/');
define('LOGIN_URL', 'https://login.salesforce.com');

define('USERNAME', 'makasetainer@gmail.com');
define('PASSWORD', 'tam12345UmZk7oXjax6aFQwt8B6dAqDv');

require_once('oauth.php');

//設定

$oauth = new oauth(CLIENT_ID, CLIENT_SECRET, CALLBACK_URL, LOGIN_URL);
$oauth->auth_with_password(USERNAME, PASSWORD, 120);

if($_POST['type'] == 'post'){
    //新規会員登録
    $url = "$oauth->instance_url/services/apexrest/makasetainer/post";
    $curl = curl_init($url);

    $POST_DATA = array(
        'mid' => $_POST['mid'],
        'category' => $_POST['category'],
        'content' => $_POST['content'],
        'gps' => $_POST['gps']
    );

    curl_setopt($curl, CURLOPT_POST, TRUE); //POST
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($POST_DATA)); //POST値
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded;charset=UTF-8', "Authorization: OAuth " . $oauth->access_token));
    $output= curl_exec($curl);
    curl_close($curl);

    echo '"data":' . $output;
}else if($_POST['type'] == 'get'){
    //新規会員登録
    $url = "$oauth->instance_url/services/apexrest/makasetainer/get";
    $curl = curl_init($url);

    $POST_DATA = array(
        'mid' => $_POST['mid']
    );

    curl_setopt($curl, CURLOPT_POST, TRUE); //POST
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($POST_DATA)); //POST値
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded;charset=UTF-8', "Authorization: OAuth " . $oauth->access_token));
    $output= curl_exec($curl);
    curl_close($curl);

    $json = json_decode($output, true);

    $response = array();
    foreach($json as $key => $come){
        $come["answer_date"] = strtotime($come["answer_date"]) * 1000;
        $response[] = $come;
    }
    //echo '"data":' . json_encode($response);
    echo json_encode($response);

}else{
    return 0;
}

?>