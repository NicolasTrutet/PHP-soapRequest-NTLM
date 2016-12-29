
<?php


   /**
   * SoapRequest
   * 
   * 
   * @author <trutetnicolas@gmail.com>
   */
	class SoapRequest {

		protected $response;

		/**
		* The following constructor method allows you 
		* to fire a soap request with NTLM authentification.
		*
		* @param {String} $url
		* @param {Array} $header
		* @param {String} $xmlRequest
		* @param {String} $credentials
		*
		* @return {XML}
		*/
		public function __construct($url, $header, $xmlRequest, $credentials) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
			curl_setopt($ch, CURLOPT_POST, 1 ); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest); 
			curl_setopt($ch, CURLOPT_USERPWD, $credentials); 

			try {
				$this->response = curl_exec($ch);	
			} catch(Exception $e) {
				print_r("<b>SoapClientManager error: </b><br/>" . $e);
			}
			
		    curl_close($ch);	
		}

		
		public function getResponse() {
			return $this->response;
		}
	}	







	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* The following illustrates how to perform a soap request using the above SoapRequest class.
	*/
	
    $header = array(
		"POST /YOUR_URL/CLIENT HTTP/1.1",
		"Host: nav.client.com:1000",
		"Accept-Encoding: gzip,deflate",
		'Connection: Keep-Alive',
		"Content-Type: text/xml;charset=UTF-8",
		"SOAPAction: urn:microsoft-dynamics-schemas/CLIENT:GetCustomerById",
		"User-Agent: Apache-HttpClient/4.1.1 (java 1.5)",
	); 


	$soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:client="urn:microsoft-dynamics-schemas/client" xmlns:x10="urn:microsoft-dynamics-nav/xmlports/x10084">';
	$soap_request .= '<soapenv:Header/>';
	$soap_request .= '<soapenv:Body>';
	$soap_request .= '<client:GetCustomerById>';
	$soap_request .= '<client:xmlCustomer>';
	$soap_request .= '<x10:Customer>';
	$soap_request .= '<x10:ID_NAV></x10:ID_NAV>';
	$soap_request .= '<x10:Name></x10:Name>';
	$soap_request .= '<x10:First_Name></x10:First_Name>';
	$soap_request .= '</x10:Customer>';
	$soap_request .= '</client:xmlCustomer>';
	$soap_request .= '<client:Email>john_doe@gmail.com</client:pEmail>';
	$soap_request .= '</client:GetCustomerById>';
	$soap_request .= '</soapenv:Body>';
	$soap_request .= '</soapenv:Envelope>';

	$url = 'http://nav.client.com:1000/DynamicsNAV90/WS/CLIENT/';

	/* NTLM = 'domain \ login:password' */
	$credentials = "US-CLIENT\myLogin:myPassword";

	/* Perform the request. */
	$soapRequest = new SoapRequest($url, $header, $soap_request, $credentials);
	
	/* Get your XML response. */
	$xmlDoc = $soapRequest->getResponse();









?>
