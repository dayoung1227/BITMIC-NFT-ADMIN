<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Blockchain Wallet API
 *
 * This library allows you to use the Blockchain Wallet API: https://blockchain.info/api/blockchain_wallet_api
 * In order for this library to work, you need to have the Blockchain Wallet Service installed.
 * https://github.com/blockchain/service-my-wallet-v3#getting-started
 *
 * @author	Mehdi Bounya
 * @link	https://github.com/mehdibo/Codeigniter-blockchain
 */
class Blockchain_lib{
	private $_ci;

	private $guid;
	private $api_code;
	private $main_password;
	private $second_password;
	private $port = 9776;
	private $base_url = 'http://10.0.0.123';
	private $user;
	private $pass;
	private $url;


	public function __construct($options = NULL)
	{
		$this->_ci =& get_instance();
		// Load config file
		$this->_ci->config->load('blockchain', TRUE, TRUE);
		// Set config values

		$this->url = "http://" . $this->_ci->config->item('user', 'blockchain') . ":" . $this->_ci->config->item('pass', 'blockchain') . "@" . $this->_ci->config->item('host', 'blockchain') . ":" . $this->_ci->config->item('port', 'blockchain') . "/";


		/*
		$this->main_password = $config['main_password'];
		$this->api_code = $config['api_code'];
		$this->second_password = $config['second_password'];


		log_message('info', 'Blockchain Class Initialized');
		// Check if the Blockchain Wallet service is running
		if ($this->_exec('') === NULL) {
			show_error('Blockchain Wallet: Unable to connect to Blockchain Wallet Service on: '.$this->base_url.':'.$this->port.'');
			log_message('error', "Blockchain: Unable to connect to Blockchain Wallet Service.");
		}
		*/

	}


	public function getinfo(){
		$parameters = array();
		return $this->_exec('getinfo', $parameters);
	}

	public function create_wallet($uid){
		$parameters = array();
		$parameters[] = $uid;
		return $this->_exec('getaccountaddress', $parameters);
	}

	public function account_confirmbalance($uid){
		$parameters = array();
		$parameters[] = $uid;
		$parameters[] = 1;
		return $this->_exec('getbalance', $parameters);
	}

	public function account_unconfirmbalance($uid){
		$parameters = array();
		$parameters[] = $uid;
		$parameters[] = 0;
		return $this->_exec('getbalance', $parameters);
	}

	public function sendmany($uid, $to, $amount){
		$parameters = array();
		$subparam = array();
		$parameters[] = $uid;
		$subparam[$to] = $amount;
		$parameters[] = $subparam;
		$parameters[] = 6;
		$parameters[] = $uid." to ".$to;
		return $this->_exec('sendmany', $parameters);
		//return $parameters;
	}
	//params":["crysasis@gmail.com",{"qqqXfesnisdfsfsfsMo4uaGHE1CXExV5VpE1mWec7tv3W":10},6,"testing"]}

	public function listtransactions($uid, $length){
		$parameters = array();
		$subparam = array();
		$parameters[] = $uid;
		$parameters[] = $length;
		return $this->_exec('listtransactions', $parameters);
		//return $parameters;
	}

    public function listaccounts(){
        $parameters = array();
        return $this->_exec('listaccounts', $parameters);
        //return $parameters;
    }

    public function getpeerinfo(){
        $parameters = array();
        return $this->_exec('getpeerinfo', $parameters);
        //return $parameters;
    }

    public function getwalletinfo(){
        $parameters = array();
        return $this->_exec('getwalletinfo', $parameters);
        //return $parameters;
    }

	/**
	 * Send funds
	 *
	 * @param string $to	 Recipient's Bitcoin address.
	 * @param string $amount Amount to send in Satoshis.
	 * @param string $from	 Send from a specific Bitcoin address. (optional)
	 * @param string $fee	 Transaction fee value in satoshi. (Must be greater than default fee) (Optional)
	 * 
	 * @return array API's response
	 */
	public function send($to, $amount, $from = NULL, $fee = NULL)
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
			'to' => $to,
			'amount' => $amount,
			'second_password' => $this->second_password,
			'from' => $from,
			'fee' => $fee
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/payment', $parameters);
	}
	/**
	 * Send funds to multiple addresses
	 *
	 * @param array $recipients An array of 'address' => 'amount to send in satoshis'.
	 * @param string $from	 Send from a specific Bitcoin address. (optional)
	 * @param string $fee	 Transaction fee value in satoshi. (Must be greater than default fee) (Optional)
	 * 
	 * @return array API's response
	 */
	public function send_many($recipients, $from = NULL, $fee=NULL)
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
			'second_password' => $this->second_password,
			'recipients' => json_encode($recipients),
			'from' => $from,
			'fee' => $fee,
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/sendmany', $parameters);
	}
	/**
	 * Get wallet's balance
	 *
	 * @return array API's response
	 */
	public function wallet_balance()
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/balance', $parameters);
	}
	/**
	 * List all active addresses
	 *
	 * @return array API's response
	 */
 	public function list_addresses()
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/list', $parameters);
	}
	/**
	 * Get the balance of a specific address
	 *
	 * @param string $address Bitcoin address to lookup
	 * 
	 * @return array API's response
	 */
	public function address_balance($address)
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
			'address' => $address
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/address_balance', $parameters);
	}
	/**
	 * Generate a new address
	 *
	 * @param string $label The new address's label. (optional)
	 * 
	 * @return array API's response
	 */
	public function new_address($label = NULL)
	{
		// Build parameters
		$parameters = [
			'password' => $this->main_password,
			'second_password' => $this->second_password,
			'label' => $label,
		];
		// Execute
		return $this->_exec('merchant/'.urlencode($this->guid).'/new_address', $parameters);
	}
	/**
	 * Execute an API request
	 *
	 * @param string $endpoint	 API's endpoint (the part after the base_url)
	 * @param array  $parameters Array of GET parameters 'parameter'=>'value'
	 * 
	 * @return array API's decoded response
	 */
	public function _exec($endpoint, $parameters = NULL)
	{
		/*
		$this->uri = "http://" . $user . ":" . $pass . "@" . $host . ":" . $port . "/";
		$this->jsonrpc = new jsonRPCClient($this->uri);


		// Start building URL
		$url = $this->base_url;
		// Add port
		$url .= ':'.$this->port.'/';
		// Add endpint
		$url .= trim($endpoint, '/').'/';
		// Build query
		if(!empty($parameters)){
			$url .= '?'.http_build_query($parameters);
		}

		// Get CURL resource
		$curl = curl_init();
		// Set options
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $url,
		));
		// Send the request & save response
		$response = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		log_message('debug', 'Blockchain: URL executed '.$url);
		// Return the decoded response as an associative array
 		return json_decode($response, TRUE);
		*/
		// prepares the request
		$request = array(
						'jsonrpc' => '1.0',
						'method' => $endpoint,
						'params' => $parameters,
						'id' => 'curltest'
						);
		$request = json_encode($request);
		
		// performs the HTTP POST
		$opts = array ('http' => array (
							'method'  => 'POST',
							'header'  => 'Content-type: application/json',
							'content' => $request
							));
		$context  = stream_context_create($opts);


		if ($fp = fopen($this->url, 'r', false, $context)) {
			$response = '';
			while($row = fgets($fp)) {
				$response.= trim($row)."\n";
			}
			$response = json_decode($response,true);
		} else {
			$response["result"] = "";
		}

		return $response['result'];

		//$request = http_build_query($request);
		//return json_encode($request);
		//return json_decode($request, true);


	}
}