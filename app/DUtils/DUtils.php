<?php
	/**
 * @author      Desmond Evans Kwachie Jr <iamdesmondjr@gmail.com>
 * @copyright   Copyright (C), 2019 Desmond Evans Kwachie Jr.
 * @license     MIT LICENSE (https://opensource.org/licenses/MIT)
 *              Refer to the LICENSE file distributed within the package.
 *
 * @todo PDO exception and error handling
 * @category    Database
 * @example
 * $this->query('INSERT INTO tb (col1, col2, col3) VALUES(?,?,?)', $var1, $var2, $var3);
 * 
 * for transactions
 * try{
 *  $this->beginTransaction();
 * 
 *  //statements ...
 * 
 * $this->commit();
 * } catch(Exception $e){
 *  $this->rollback();
 * }
 * 
 * 
 */
require_once('Sms.php');
class DUtils extends SmsAlert{
    protected $connection;
	protected $query;
	public $query_count = 0;
	
	public function __construct($dbhost = '', $dbuser = '', $dbpass = '', $dbname = '') {
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			die('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		//$this->connection->set_charset($charset);
	}
	
    public function query($query) {
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();

           	if ($this->query->errno) {
				die('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
			$this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }

	public function fetchAll() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
		return $result;
	}

	public function fetchArray() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
		return $result;
	}
	
	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	public function lastInserted(){
		return $this->query->insert_id;
	}

	private function _gettype($var) {
	    if(is_string($var)) return 's';
	    if(is_float($var)) return 'd';
	    if(is_int($var)) return 'i';
	    return 'b';
	}

	public function exist($query, $data){
		$stmt = $this->query($query, $data);
		return $this->numRows($stmt);
		$this->query->close();

	}

	public function num_format($number){
			return number_format($number,2, '.', ',');
		}

	/**
     * @param $input
     * @param $length
     * @param bool|true $ellipses
     * @param bool|true $strip_html
     * @return string
     */
    public function trim_text($input, $length, $ellipses = true, $strip_html = true) {
        if ($strip_html === true) {
            $input = strip_tags($input);
        }

        if (strlen($input) <= $length) {
            return $input;
        }

        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        if ($ellipses === true) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

     /**
     * redirect - Shortcut for a page redirect
     *
     * @param string $url
     */
    public function redirect($url) {
        header("Location: $url");
        exit(0);
    }

    /**
     * debug - print array elements nicely in the browser;
     *
     * @param array $data
     *
     *
     */
    public function debug($data = array()){
        echo "<pre style='background-color:#222; color: green;padding:20px;>'";
        print_r($data);
        echo "</pre>";
        die();
    }

    public function sort_multi_array($arr, $key){
        uasort($arr, function($i, $j){
            $a = $i[$key];
            $b = $j[$key];
            if($a == $b) return 0;
            elseif($a > $b) return 1;
            else return -1;
        });
    }

    // for password hashing
    public function hash_value($algo, $data, $salt = null) {
        if(is_null($salt) === true) {
            $context = hash_init($algo);
            hash_update($context, $data);
            return hash_final($context);
        } else {
            $context = hash_init($algo, HASH_HMAC, $salt);
            hash_update($context, $data);
            return hash_final($context);
        }
    }

     /**
     * beginTransaction - Overloading default method
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
        $this->activeTransaction = true;
    }

    /**
     * commit - Overloading default method
     */
    public function commit() {
        $result =  $this->connection->commit();
        $this->activeTransaction = false;
        return $result;
    }

    /**
     * rollback - Overloading default method
     */
    public function rollback() {
        $this->connection->rollback();
        $this->activeTransaction = false;
    }

    /**
     * generate_reqid - Generate unique random hashed hexcode padded with zeros to the left.
     *
     * @return void
     **/
    public function generate_orderCode(){
        $cstrong = time();
        $bytes = openssl_random_pseudo_bytes(10, $cstrong);
        $hex = bin2hex($bytes);
        $md5hash = md5($hex);
        $unqstring = crc32($md5hash);
        return str_pad(dechex($unqstring), 8, '0', STR_PAD_LEFT);
    }

    public function log($id, $_activity, $_msg){
        // change to suite your log table
        $this->query('INSERT INTO user_log(user_id, activity) VALUES(?,?)', $id, $_activity.' : '.$_msg);
    }

    //sanitizing a string
    public function sanitize_string($data){
        $string = trim(addslashes(filter_var($data, FILTER_SANITIZE_STRING)));
        return preg_replace('/\s+/', '', $string);
    }

    //validating email
    public function validate_email($data){
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }

    //generate random numbers.. can be use for forget password
    public function generate($length = 4){
        return substr(str_shuffle("0123456789"), 0, $length);
    }

    //for generating qr codes
    public function qr_code($data){
        $this->qr_code = 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='.$data;
    }

    // generate card number
    public function generate_card(){
        $prefix = "INTERN/";
        $year = date('y/');
        $num = substr(str_shuffle("01234567899876543210"), 0, 4);
        return $this->card_num = strtoupper($prefix.$year.$num);
    }

    /**
     * for displaying alert messages.... 
     * Using Argon bootstrap
     * change to suit your css styles or css framework
     */

    public function alert($message, $type){
        switch ($type) {
            case 'error':
                return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" aria-hidden="true" class="alert-dismissible close" data-dismiss="alert" aria-label="Close">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
                <span data-notify="icon" class="tim-icons icon-trophy"></span>
                <span><b> Heads up! - </b> '.$message.'</span>
                 </div>';
                break;
            case 'success':
                return '<div class="alert alert-success alert-with-icon">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
                <span data-notify="icon" class="tim-icons icon-trophy"></span>
                <span><b> Heads up! - </b> '.$message.'</span>
                 </div>';
            default:
                
                break;
        }
     
    }

    /** for hashing password
     * 
     * In this case, we want to increase the default cost for BCRYPT to 12.
     * Note that we also switched to BCRYPT, which will always be 60 characters.
     */
    public function passHash($password){
            $options = [
                'cost' => 12,
            ];
       return $passwordHash = password_hash($password, PASSWORD_BCRYPT, $options); 
    }

    public function passVerify($password, $hash){
       return $passVerify = (password_verify($password, $hash)) ? true : false ;
    }

     /**
     * @return mixed
     */
    public function get_ip(){
        if(function_exists('apache_request_headers')){
            $headers = apache_request_headers();
        } else{
            $headers = $_SERVER;
        }

        if(array_key_exists('X-Forwarded-For', $headers) &&
                filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $the_ip = $headers['X-Forwarded-For'];
        } elseif(array_key_exists('HTTP_X_FORWARDED_FOR', $headers) &&
                filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else{
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    /**
     * @param $data
     * @param null $filename
     */
    public static function createCSV($data, $filename = null){
        if(!isset($filename)){
            $filename = "replies";
        }

        //Clear output buffer
        ob_clean();

        //Set the Content-Type and Content-Disposition headers.
        header("Content-type: text/x-csv");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename={$filename}-".date('YmdHis',strtotime('now')).".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        //Open up a PHP output stream using the function fopen.
        $fp = fopen('php://output', 'w');

        //Loop through the array containing our CSV data.
        foreach ($data as $row) {
            //fputcsv formats the array into a CSV format.
            //It then writes the result to our output stream.
            fputcsv($fp, $row);
        }

        //Close the file handle.
        fclose($fp);
    }

    /**
     * hash_cost - Calculate the cost the server can take when using password_hash function
     *
     * @return int
     */
    public static function hash_cost(){
        $timeTarget = 0.05;
        $cost = 8;
        do{
            $cost++;
            $start = microtime(true);
            password_hash("diyframeworktest", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while(($end - $start) < $timeTarget);

        return $cost;
    }

    /**
     * crypt AES 256
     *
     * @param data $data
     * @param string $passphrase
     * @return base64 encrypted data
     */
    public static function encrypt($data, $passphrase){
        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
        $dx = hash('sha256', $dx.$passphrase.$salt, true);
        $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
        return base64_encode($salt . $encrypted_data);
    }

    /**
     * decrypt AES 256
    *
    * @param data $edata
    * @param string $password
    * @return decrypted data
    */
    public static function decrypt($edata, $passphrase){
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3; // depends on key length
        $data00 = $passphrase.$salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv  = substr($result, 32,16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address
     *
     * @param string $email The email address
     * @param integer $size Size of image in pixels. Desfaults to 80 [1 - 2048]
     * @param string $imageset Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boolean $tag True to return a complete IMG tag False for just the URL
     * @param array $attr Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     */
    public static function gravatar($email, $size = 80, $imageset = 'mp', $rating = 'g', $tag = false, $attr = array()){
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$size&d=$imageset&r=$rating";

        if ($tag) {
            $url = '<img src="' . $url . '"';
            foreach ( $attr as $key => $val ) $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }

        return $url;
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9-\-]/', '', $string); // Removes special chars.
     }

     public function validate_phone($phone){
        if(!preg_match('/^[0-9]{10}+$/', $phone)){
            return false;
        }else{
            return true;
        }
     }

}
?>