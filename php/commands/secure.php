<?php

use \EE\Utils;
use \EE\Dispatcher;

class Secure_Command extends EE_Command {


	public function secure_auth(){
		//***This function Secures authentication***
		$passwd = EE_Utils::random_string();

		$value = EE::input_value( "Provide HTTP authentication user name :" );
		if ( $value ) {
			$nginx_user = $value;
		}else{
			$nginx_user = get_ee_git_config('user','name');
		}

		$value = EE::input_value( "Provide HTTP authentication password :" );
		if ( $value ) {
			$nginx_pass = $value;
		}else{
			$nginx_pass = $passwd;
		}

		if(EE::exec_cmd( "printf \"".$nginx_user.":$(openssl passwd -crypt " . $passwd . "2> /dev/null)\" > /etc/nginx/htpasswd-ee 2>/dev/null" )){
			EE::success("HTTP AUTH USERNAME:PASSWORD : ".$nginx_user.":" . $passwd);
		}

		EE_Git::add("/etc/nginx");

	}

	public function secure_port(){
		//***This function Secures port***
		//todo:

	}

	public static function secure_ip(){
		//***This function Secures IP***
		//todo:

	}


	/**
	 * EE Secure:: Change HTTP AUTH
	 *
	 * ## OPTIONS
	 *
	 *
	 * [--auth]
	 * : secure auth
	 *
	 * [--port]
	 * : secure port
	 *
	 * [--ip]
	 * : secure ip
	 *
	 *
	 * ## EXAMPLES
	 *
	 *      # EE Secure
	 *      $ ee secure --auth
	 *      $ ee secure --ip
	 *
	 */
	public function __invoke( $args, $assoc_args ) {
		if (!empty($assoc_args['auth'])){
			$this->secure_auth();
		}elseif (!empty($assoc_args['port'])){
			$this->secure_port();
		}elseif (!empty($assoc_args['ip'])){
			$this->secure_ip();
		}
	}


}

EE::add_command( 'secure', 'Secure_Command' );

