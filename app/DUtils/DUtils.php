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
@session_start();
require_once('Sms.php');
class DUtils extends SmsAlert{
    protected $connection;
	protected $query;
	public $query_count = 0;
	
	public function __construct($dbhost = '127.0.0.1', $dbuser = '', $dbpass = '', $dbname = '') {
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
        print_r($data);
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

}
?>