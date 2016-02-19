<?php
class MW_Ipchecker_Block_Ipchecker extends Mage_Core_Block_Template
{		
		public function getGeoip(){                   
                            try { 								
							$realip=$_SERVER['REMOTE_ADDR'];							
							 //  vnip: 203.162.0.181   canadaip: 64.26.133.155 usip:66.249.71.117   real ip: $_SERVER['REMOTE_ADDR'] impotain 45.78.23.77	
								foreach(unserialize(Mage::getStoreConfig('ipchecker/info/ipexception')) as $ip){
									$array=explode("-",$ip['ip']);						
									if($realip==$ip['ip'])														
											return null;								
									if(count($array)>1){
											$ipstr=explode('.',$realip);
											$ipend=$ipstr[count($ipstr)-1]; 
											$head=explode('.',$array[0]);
											$min=$head[count($head)-1];
											$max=$array[1];	$equal=true;
											for($i=0;$i<=2;$i++)											
												if($ipstr[$i]!=$head[$i]) {$equal=false;break;};						
											if($equal)
												if($ipend >= $min&& $ipend <= $max){										
												return null;												
												}									
									};									
								};				
							
									$info_ip_address=array();					
									$country=$this->getCountryFromIP($realip);
									if(isset($country)&&$country!=''&&$country!=' '){
										$info_ip_address[0]='OK';
										$info_ip_address[1]=strtoupper($country);
										return $info_ip_address;
									}else{	
										/* Only use when GeoIp Database has been imported in Magento Database*/
										/*  $country=$this->geoip($realip);
										if(isset($country)&&$country!=''&&$country!=' '){
											$info_ip_address[0]='OK';
											$info_ip_address[1]=strtoupper($country);
											return $info_ip_address;
										};	 */								
									};							
									/*  if(Mage::getStoreConfig('ipchecker/info/online'))
										{	 */									
										 	
										 	$key="718b4c6e9d4b09b15cbb35e846457723cd0fe7d842401c7203069b7252f7d289";			
										 	$xml = $this->loadXML2('api.ipinfodb.com', '/v3/ip-country/?key='.$key."&ip=".$realip."&format=xml");
											if(isset($xml)&&$xml) {
												$info_ip_address1=$xml->children();											
												if ($info_ip_address1[0]=="OK") {
													$returnArray = array();
													$returnArray[] = (string)$info_ip_address1->statusCode;
													$returnArray[] = (string)$info_ip_address1->countryCode;
													$returnArray[] = 'host1';
													return $returnArray;		
												}
											}

										 /* PINT: OLD, NON WORKING CODE FROM API V2
											$xml = simplexml_load_file('http://api.ipinfodb.com/v2/ip_query.php?key='.$key."&ip=".$realip."&timezone=true");														
										if(isset($xml)&&$xml)
										{
										$info_ip_address1=$xml->children();											
										  if($info_ip_address1[0]=="OK")											
											return $info_ip_address1;									
										}; 
										*/

										// PINT: file() used to have $this->StripTags_IP around it, but it was unnecessary 
										// because file() returns an array of strings, not a single string.
										$lines = file('http://api.hostip.info/get_html.php?ip='.$realip);
										$str=substr($lines[0], 9);																
										if(isset($str)){
										   $begin= strpos($str,'(');
										   $end= strpos($str,')');
										   $code=substr($str,$begin+1,$end-$begin-1);
										   if($begin>0&&$end>0){
												$info_ip_address[0]='OK';
											$info_ip_address[1]=strtoupper($code);
											$info_ip_address[2]='host2';
										//	echo 'checker roi ne';var_dump($info_ip_address);echo 'fdfd';die;
											return $info_ip_address;										
										}; 											
										};	
										return null;
						   } catch (Exception $e) {                                 
								 }
     } 
	 
	 
	  public function getCountryFromIP($ip)
	 {		$country = @exec("whois $ip  | grep -i country"); // Run a local whois and get the result back
		//$country = strtolower($country); // Make all text lower case so we can use str_replace happily
		// Clean up the results as some whois results come back with odd results, this should cater for most issues
		$country = str_replace("country:", "", "$country");
		$country = str_replace("Country:", "", "$country");
		$country = str_replace("Country :", "", "$country");
		$country = str_replace("country :", "", "$country");
		$country = str_replace("network:country-code:", "", "$country");
		$country = str_replace("network:Country-Code:", "", "$country");
		$country = str_replace("Network:Country-Code:", "", "$country");
		$country = str_replace("network:organization-", "", "$country");
		$country = str_replace("network:organization-usa", "us", "$country");
		$country = str_replace("network:country-code;i:us", "us", "$country");
		$country = str_replace("eu#countryisreallysomewhereinafricanregion", "af", "$country");
		$country = str_replace("", "", "$country");
		$country = str_replace("countryunderunadministration", "", "$country");
		$country = str_replace(" ", "", "$country");
		return $country;
	 }
	 /* Only use when GeoIp Database has been imported in Magento Database*/
	 // PINT: Added public attribute to function
	public function geoip($realip){								
							 // vnip: 203.162.0.181   canadaip: 64.26.133.155 usip:66.249.71.117   real ip: $_SERVER['REMOTE_ADDR'] impotain 45.78.23.77		
			if(isset($realip))	{
					$ip=explode(".",$realip);
					$write = Mage::getSingleton('core/resource')->getConnection('core_write');
					$ip1=$ip[0];
					$ip2=$ip[1];
					$ip3=$ip[2];
					$ip4=$ip[3];
					$query="SELECT `code` FROM `countries`, ip4_".$ip1." WHERE `b`='".$ip2."' AND `c`='".$ip3."' AND countries.id=ip4_".$ip1.".country";		
					$readresult=$write->query($query);$result=null;		
					while ($row = $readresult->fetch()) {			
					$result=$row['code'];	
					};						
					return $result;					
			}
			return null;
	}
	// PINT: Added public attribute to function
	public function StripTags_IP($string){
         while(strstr($string, '>')){
            $currentBeg = strpos($string, '<');
            $currentEnd = strpos($string, '>');
            $tmpStringBeg = @substr($string, 0, $currentBeg);
            $tmpStringEnd = @substr($string, $currentEnd + 1, strlen($string));
            $string = $tmpStringBeg.$tmpStringEnd;
         }
         return $string;
      }	

	/*
	        Usage:
	       
	        $xml = loadXML2("127.0.0.1", "/path/to/xml/server.php?code=do_something");
	        if($xml) {
	            // xml doc loaded
	        } else {
	            // failed. show friendly error message.
	        } 
	*/
    public function loadXML2($domain, $path, $timeout = 5) {
	    @$fp = fsockopen($domain, 80, $errno, $errstr, $timeout);
	    if($fp) {
	        // make request
	        $out = "GET $path HTTP/1.1\r\n";
	        $out .= "Host: $domain\r\n";
	        $out .= "Connection: Close\r\n\r\n";
	        fwrite($fp, $out);
	       
	        // get response
	        $resp = "";
	        while (!feof($fp)) {
	            $resp .= fgets($fp, 128);
	        }
	        fclose($fp);
	        // check status is 200
	        $status_regex = "/HTTP\/1\.\d\s(\d+)/";
	        if(preg_match($status_regex, $resp, $matches) && $matches[1] == 200) {   
	            // load xml as object
	            $parts = explode("\r\n\r\n", $resp);   
				return simplexml_load_string($parts[1]);               
	        }
	    }
		    return false;
	   
	} 		
}
                   
                  