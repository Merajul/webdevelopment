<?php 

class APIAccessor{

	private $serviceNameBL = "";
    private $tbl_fv_contents_user = 'fantastic_video.dbo.tbl_fv_contents_user';
    private $tbl_fv_content_files_user = 'fantastic_video.dbo.tbl_fv_content';
    private $tbl_subscriber_profile_wap = 'tbl_subscriber_profile_wap';
    private $tbl_fv_contents_download_history = 'fantastic_video.dbo.tbl_fv_contents_download_history';
    private $tbl_wap_package = 'tbl_package_list_wap';
	private $tbl_SDP_Charge = "vas_bl.dbo.tbl_SDP_Charge";
	private $tbl_wap_subs_shorturl = "vas_bl.dbo.tbl_wap_subs_shorturl";
	private $tbl_promotion_log = 'vas_bl.dbo.tbl_promotion_log';
    public  $msisdn="";
    public  $serviceName="petpuja";
    public  $package_name="daily";
    public  $shortCode="";
    public  $subsChannel="";

    /****Robi and Airtel****/
    private $serviceNameRA = "fantastic";
    private $RAtbl_wap_package = 'vas_robi.dbo.tbl_wap_package';
    private $RAtbl_subscriber_profile_wap = 'vas_robi.dbo.tbl_subscriber_profile_wap';
    private $RAtbl_wap_sub_api = 'vas_robi.dbo.tbl_wap_sub_api';

    public $domain = 'http://wap.example.com';
    private $homeApi = 'http://wap.example.com/getHomeContent';
    private $homeApinew = 'http://wap.example.com/getHome';
    private $getVideobyID = 'http://wap.example.com/getVideo';
    private $getStaus = 'http://wap.example.com/status';
    private $getPackage = 'http://wap.example.com/getPackage';
    private $subscribeApi = 'http://wap.example.com/subscribeApi';
    private $unsubscribeApi = 'http://wap.example.com/unsubscribeApi';
    private $getVideoList = 'http://wap.example.com/getVideoList';
    private $getSubscribeRA = 'http://wap.example.com/getSubscribeRA';
    private $getContact = 'http://wap.example.com/getContact';
    private $getAboutus = 'http://wap.example.com/getAboutus';
    private $NotFoundList = 'http://wap.example.com/NotFoundList';
    private $getFreeVideoList = 'http://wap.example.com/getFreeVideoList';
    private $getFreeVideo = 'http://wap.example.com/getFreeVideo';
    private $unsubscribebl = "http://192.168.7.50:8844/bl_vas_api/unsubscribe?";
    private $callbackApi = 'http://wap.example.com/callbackApi';
	
	private $promotionupdate = 'http://wap.example.com/promotionupdate';
    
    /****MSG***/
    public $onfountnumber = 'Please use banglalink number to access all our recipes';

    public $primaryDB="vas_petpuja";
    public $VASBLDB="vas_bl";
    public $DBHost="localhost";
    public $DBUser="vasuser";
    public $DBPwd="vaspwd";
    public $needSubscribe="subscribe.php";
    public $Unauthorized="unauthorized.php";
    public $executeSubscribe="executeSubscribe.php";
    public $userAuthorized=0;

    public function baseName(){
        return basename($_SERVER['REQUEST_URI']);
    }

    public function redirect($link=''){
        header('location:'.$link);
    }

    public function url_origin( $s, $use_forwarded_host = false )
    {
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    public function full_url( $s, $use_forwarded_host = false )
    {
        return $this->url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
    }

    public function wh_log($type='log',$log_msg)
    {
        $newSTR=PHP_EOL;
        $newSTR.="Genarated = ".date('Y-m-d H:i:s')." ||     ".$log_msg;
        $log_filename = $type;
        if (!file_exists($log_filename)) 
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($log_file_data, $newSTR, FILE_APPEND);
    }

    public function handleCheckSubscribe($data=array()){

        //echo $this->baseName(); die();
        //$this->printPre($data);
        $parseData=json_decode($data);
        if(isset($parseData->status))
        {
            if($parseData->status=="success"){

                if(isset($parseData->data)){

                    $dataRow=$parseData->data;
                    //
                    if(empty($dataRow->isSubscribed) && $this->baseName()!=$this->needSubscribe){

                        $this->redirect($this->needSubscribe);
                    }elseif($dataRow->isSubscribed==true){

                        $this->userAuthorized=1;
                    }
                    
                }
            }
            else{
                $this->redirect($this->Unauthorized);
            }
        }
        else{
            $this->redirect($this->Unauthorized);
        }

        
    }

	public function responsJson($var = array()) {
        header('Content-Type: application/json');
        return json_encode($var);
    }

    private function responsJsonWitoutHeader($var = array()) {
        return json_encode($var);
    }

    public function HTTPStatus($num) {
	    $http = array(
	        100 => 'HTTP/1.1 100 Continue',
	        101 => 'HTTP/1.1 101 Switching Protocols',
	        200 => 'HTTP/1.1 200 OK',
	        201 => 'HTTP/1.1 201 Created',
	        202 => 'HTTP/1.1 202 Accepted',
	        203 => 'HTTP/1.1 203 Non-Authoritative Information',
	        204 => 'HTTP/1.1 204 No Content',
	        205 => 'HTTP/1.1 205 Reset Content',
	        206 => 'HTTP/1.1 206 Partial Content',
	        300 => 'HTTP/1.1 300 Multiple Choices',
	        301 => 'HTTP/1.1 301 Moved Permanently',
	        302 => 'HTTP/1.1 302 Found',
	        303 => 'HTTP/1.1 303 See Other',
	        304 => 'HTTP/1.1 304 Not Modified',
	        305 => 'HTTP/1.1 305 Use Proxy',
	        307 => 'HTTP/1.1 307 Temporary Redirect',
	        400 => 'HTTP/1.1 400 Bad Request',
	        401 => 'HTTP/1.1 401 Unauthorized',
	        402 => 'HTTP/1.1 402 Payment Required',
	        403 => 'HTTP/1.1 403 Forbidden',
	        404 => 'HTTP/1.1 404 Not Found',
	        405 => 'HTTP/1.1 405 Method Not Allowed',
	        406 => 'HTTP/1.1 406 Not Acceptable',
	        407 => 'HTTP/1.1 407 Proxy Authentication Required',
	        408 => 'HTTP/1.1 408 Request Time-out',
	        409 => 'HTTP/1.1 409 Conflict',
	        410 => 'HTTP/1.1 410 Gone',
	        411 => 'HTTP/1.1 411 Length Required',
	        412 => 'HTTP/1.1 412 Precondition Failed',
	        413 => 'HTTP/1.1 413 Request Entity Too Large',
	        414 => 'HTTP/1.1 414 Request-URI Too Large',
	        415 => 'HTTP/1.1 415 Unsupported Media Type',
	        416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
	        417 => 'HTTP/1.1 417 Expectation Failed',
	        500 => 'HTTP/1.1 500 Internal Server Error',
	        501 => 'HTTP/1.1 501 Not Implemented',
	        502 => 'HTTP/1.1 502 Bad Gateway',
	        503 => 'HTTP/1.1 503 Service Unavailable',
	        504 => 'HTTP/1.1 504 Gateway Time-out',
	        505 => 'HTTP/1.1 505 HTTP Version Not Supported',
	    );
	    header($http[$num]);
	    return array(
	            'code' => $num,
	            'error' => $http[$num],
	        );
    }

    public function getApacheHeader(){
        $prs = apache_request_headers();
        return $prs;
    }

    public function getMSISDN(){
        $param=$this->getMsisdnFromHandset();
        if(isset($param) && !empty($param)){
            return $param;
        }
        else
        {
            return "";
        }
    }

    public function getMsisdnFromHandset(){
        $mobile = "";
        if(!isset($_SESSION['msisdn']))
        {
            $_SESSION['msisdn']="";
        }

        $msisdn = $_SESSION['msisdn'];
        if(!empty($msisdn) && $msisdn != ""){
            $mobile = $msisdn;
        }
        else
        {
            if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
                $mobile = trim($_SERVER['HTTP_X_UP_CALLING_LINE_ID']);
            }else if(isset($_SERVER['HTTP_X_HTS_CLID'])){
                $mobile = trim($_SERVER['HTTP_X_HTS_CLID']);
            }else if(isset($_SERVER['HTTP_MSISDN'])){
                $mobile = trim($_SERVER['HTTP_MSISDN']);
            }else if(isset($_SERVER['HTTP_X_MSISDN'])){
                $mobile = trim($_SERVER['HTTP_X_MSISDN']);
            }
            
            if($mobile != '')
            {
                $_SESSION['msisdn']=$mobile;
            }
            
            /*else
            {
                $mob=$this->getApacheHeader();
                if(isset($mob['msisdn'])){
                    $_SESSION['msisdn']=$mob['msisdn'];
                }
            }*/
        }
        
        //echo 'Mobile: '.$mobile;
        //$mobile = '8801977136045';
        return $mobile;
    }

    public function printPre($prs){
        echo "<pre>";
        print_r($prs);
        die();
    }

    public function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function CallApi($header= array(), $params = array(), $url,$isPost){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($isPost){
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($header){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        
        if($params){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0) {
            error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
        }
        //echo $result;
        curl_close($ch);
        return json_decode($result);   
    }

    public function getCheckSubscribe($msisdn){
		$headers = array(
			'MSISDN: '.$msisdn,
		);
		$params = array();
		$D = CallApi($headers, $params, $this->getStaus,true);
		//print_pre($D);
		if($D->status=='success'){
			return $D->data;
		}else{
			return false;
		}
		
	}

    public function en2bn($number) {
        $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        return str_replace( $en,$bn, $number);
    } 

    public function getLoder($msisdn) {
        $Op = substr($msisdn, 0,5);
        if($Op == '88019' || $Op == '88014'){
            echo 'loader-bl';
        }else if($Op == '88016' || $Op == '88018'){
            echo 'loader-ar';
        }else{
            echo 'loader-oth';
        }
    }



    function getOperator($msisdn) {
        $Op = substr($msisdn, 0,5);
        if($Op == '88019' || $Op == '88014'){
            return 'Banglalink';
        }else if($Op == '88016' || $Op == '88018'){
            return 'RobiAirtel';
        }else if($Op == '88017' || $Op == '88013'){
            return 'RobiAirtel';
        }else{
            return $Op;
        }
    }

    private function checkNumber($msisdn) {
        $pattern = "/^(88)(019|014)\d{8}$/";
        if(preg_match($pattern, $msisdn)){
            return true;
        }else{
            return false;
        }
    }

    public function subscribeApi($msisdn=''){

        $this->msisdn = $msisdn;
        
        if(empty($this->msisdn)){
            $this->HTTPStatus(401);
            return $this->responsJson( array('status'=>'failed','message'=>'Please use banglalink number'));
            die();
        }
        if(!$this->checkNumber($this->msisdn)){
            $this->HTTPStatus(400);
            return $this->responsJson (array('status'=>'failed','message'=>'Please use banglalink number'));
            die();
        }

            include('vasbl_class.php');
            $blobj=new vasBlClass();
            $pK = (array) $blobj->blWapCheck();
            if(!$pK){
                $this->HTTPStatus(401);
                return $this->responsJson( array('status'=>'failed','message'=>'Invalid Service or Package'));
                die();
            }

            //$array =  (array) $object;

            $userData = array(array_merge($pK,array('msisdn' => $this->msisdn)));
            //$this->printPre($userData);
            $D = $blobj->blSubscribe($userData);
            return $this->responsJson($D);
    }



    public function DbLinkPrimary(){
        $connectionInfo = array(
                                "Database"=>$this->primaryDB, 
                                "UID"=>$this->DBUser, 
                                "PWD"=>$this->DBPwd, 
                                "CharacterSet"=>"UTF-8"
                            );
        $DbConnect = sqlsrv_connect($this->DBHost, $connectionInfo);  
        if($DbConnect===false){ die(print_r(sqlsrv_errors(),true)); }
        return $DbConnect;
    }

    

    public function closeConnection($conn){
        sqlsrv_close($conn);
    }

    public function msisdnLenthFix($msisdn=''){
        $newMsisdn="";
        if(!empty($msisdn)){
            if(strlen($msisdn)==10){
                $newMsisdn='880'.$msisdn;
            }elseif(strlen($msisdn)==11){
                $newMsisdn='88'.$msisdn;
            }elseif(strlen($msisdn)==13){
                $newMsisdn=$msisdn;
            }
        }

        return $newMsisdn;

    }



    public function Insert($table,$dataArray=array()){
       
        $keysString="";
        $keysStringParam="";
        //$whereClouse="";
        if(count($dataArray)>0)
        {
            $i=0;
            foreach($dataArray as $key=>$row){
                if
                ($i==0){ 
                    $keysString.="".$key; $keysStringParam.=" N'".$row."'"; 
                    //$whereClouse.=$key."='".$row."'";
                }
                else
                { 
                    $keysString.=",".$key; $keysStringParam.=", N'".$row."'"; 
                    //$whereClouse.=" AND ".$key."='".$row."'";
                }
                $i++;
            }
        }

        if(strlen($keysString)==0){
            return false;
        }

        $connection=$this->DbLinkPrimary();
        $sqlQuery="INSERT INTO ".$table." (".$keysString.") VALUES (".$keysStringParam.")";
        
        $exec = sqlsrv_query($connection,$sqlQuery);
        
        if($exec){
            sqlsrv_free_stmt( $exec);
            $this->closeConnection($connection);
            return true;

        }else{
            $this->printPre(sqlsrv_errors()); 
            $this->closeConnection($connection);
            return false;
        }

            
        
    }

    public function do_info_log($mobile){

        require_once 'Mobile_Detect.php'; 
        $detect = new Mobile_Detect;
        $device='';
        $browser='';
        $mobile_set='';
        $mobile_OS='';
            
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        //identity device
        if($detect->isMobile()){
            $device='Mobile';
        }
        else if($detect->isTablet()){
            $device='Tablet';
        }
        else {
            $device='Computer';
        }
        
        if($detect->isChrome()){
            $browser='Chrome';
        }
        else if($detect->isDolfin()){
            $browser='Dolfin';
        }
        else if($detect->isOpera()){
            $browser='Opera';
        }
        else if($detect->isSkyfire()){
            $browser='Skyfire';
        }
        else if($detect->isEdge()){
            $browser='Edge';
        }
        else if($detect->isIE()){
            $browser='IE';
        }
        else if($detect->isFirefox()){
            $browser='Firefox';
        }
        else if($detect->isSafari()){
            $browser='Safari';
        }
        else if($detect->isUCBrowser()){
            $browser='UCBrowser';
        } 
        else if($detect->isbaidubrowser()){
            $browser='baidubrowser';
        }
        else if($detect->isDiigoBrowser()){
            $browser='DiigoBrowser';
        }
        else if($detect->isObigoBrowser()){
            $browser='ObigoBrowser';
        }
        else if($detect->isGenericBrowser()){
            $browser='GenericBrowser';
        } 
        else {
            $browser='Unknown';
        }

        if($detect->isiPhone()){
            $mobile_set='iPhone';
        }
        else if($detect->isBlackBerry()){
            $mobile_set='BlackBerry';
        }
        else if($detect->isHTC()){
            $mobile_set='HTC';
        }
        else if($detect->isNexus()){
            $mobile_set='Nexus';
        }
        else if($detect->isDell()){
            $mobile_set='Dell';
        }
        else if($detect->isMotorola()){
            $mobile_set='Motorola';
        }
        else if($detect->isSamsung()){
            $mobile_set='Samsung';
        }
        else if($detect->isLG()){
            $mobile_set='LG';
        }
        else if($detect->isSony()){
            $mobile_set='Sony';
        }
        else if($detect->isAsus()){
            $mobile_set='Asus';
        }
        else if($detect->isNokiaLumia()){
            $mobile_set='NokiaLumia';
        }
        else if($detect->isMicromax()){
            $mobile_set='Micromax';
        }
        else if($detect->isPalm()){
            $mobile_set='Palm';
        }
        else if($detect->isVertu()){
            $mobile_set='Vertu';
        }
        else if($detect->isGenericPhone()){
            $mobile_set='GenericPhone';
        }
        else if($detect->isPantech()){
            $mobile_set='Pantech';
        }
        else if($detect->isFly()){
            $mobile_set='Fly';
        }
        else if($detect->isWiko()){
            $mobile_set='Wiko';
        }
        else if($detect->isiMobile()){
            $mobile_set='iMobile';
        }
        else if($detect->isSimValley()){
            $mobile_set='SimValley';
        }
        else if($detect->isWolfgang()){
            $mobile_set='Wolfgang';
        }
        else if($detect->isAlcatel()){
            $mobile_set='Alcatel';
        }
        else if($detect->isNintendo()){
            $mobile_set='Nintendo';
        }
        else if($detect->isAmoi()){
            $mobile_set='Amoi';
        }
        else if($detect->isINQ()){
            $mobile_set='INQ';
        }
        else if($detect->isiPad()){
            $mobile_set='iPad';
        } 
        else if($detect->isNexusTablet()){
            $mobile_set='NexusTablet';
        }
        else if($detect->isSamsungTablet()){
            $mobile_set='SamsungTablet';
        } 
        else {
            $mobile_set='Unknown';
        }
        
        if($detect->isAndroidOS()){
            $mobile_OS='AndroidOS';
        }
        else if($detect->isBlackBerryOS()){
            $mobile_OS='BlackBerryOS';
        }
        else if($detect->isPalmOS()){
            $mobile_OS='PalmOS';
        }
        else if($detect->isSymbianOS()){
            $mobile_OS='SymbianOS';
        }
        else if($detect->isWindowsMobileOS()){
            $mobile_OS='WindowsMobileOS';
        }
        else if($detect->isiOS()){
            $mobile_OS='iOS';
        }
        else if($detect->isMeeGoOS()){
            $mobile_OS='MeeGoOS';
        }
        else if($detect->isJavaOS()){
            $mobile_OS='JavaOS';
        }
        else if($detect->iswebOS()){
            $mobile_OS='webOS';
        }
        else if($detect->isbadaOS()){
            $mobile_OS='badaOS';
        }
        else if($detect->isBREWOS()){
            $mobile_OS='BREWOS';
        }
        else {
            $mobile_OS='Unknown';
        }
        
         
        $data = array(
            'msisdn' => $mobile,
            'device' => $device,
            'browser' => $browser,
            'mobile_set' => $mobile_set,
            'mobile_OS' => $mobile_OS,
            'userAgent' => $useragent
        );

        $this->Insert("tbl_pp_browsing_log",$data);

        return true;
    }



}