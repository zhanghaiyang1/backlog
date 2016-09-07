<?php
class DES1 {	
	var $key;		
	function 	DES1($key) {		
		$this->key = $key;		
	}		
	function encrypt($input) {		
		$size = mcrypt_get_block_size('des', 'ecb');    	
		$input = $this->pkcs5_pad($input, $size);   		
		$key = $this->key;    	
		$td = mcrypt_module_open('des', '', 'ecb', '');	    
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);	   
		@mcrypt_generic_init($td, $key, $iv);	    
		$data = mcrypt_generic($td, $input);	    
		mcrypt_generic_deinit($td);	   
		mcrypt_module_close($td);	    
		$data = base64_encode($data);	    
		return $data;	
	}		
	function decrypt($encrypted) {		
		$encrypted = base64_decode($encrypted);    	
		$key =$this->key;    	
		$td = mcrypt_module_open('des','','ecb',''); 
		//使用MCRYPT_DES算法,cbc模式          	
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);       	
		$ks = mcrypt_enc_get_key_size($td);         	
		@mcrypt_generic_init($td, $key, $iv);       
		//初始处理           	
		$decrypted = mdecrypt_generic($td, $encrypted);       
		//解密           	
		mcrypt_generic_deinit($td);       
		//结束          
		mcrypt_module_close($td);               
		$y=$this->pkcs5_unpad($decrypted);        
		return $y;	
	}		
	function pkcs5_pad ($text, $blocksize) {    	
		$pad = $blocksize - (strlen($text) % $blocksize);    	
		return $text . str_repeat(chr($pad), $pad);	
	} 	
	function pkcs5_unpad($text) {		
		$pad = ord($text{strlen($text)-1});		
		if ($pad > strlen($text)) 			
			return false;		
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) 			
			return false;    	
		return substr($text, 0, -1 * $pad);	
	}
	/**
	 * 手动写入日志
	 * @param string $data  写入内容
	 * @param stirng $dir 写入目录
	 * @param string $method 写入方式
	 */
	function wlog($data, $dir, $method = "a+") {
		$dir = empty($dir) ? 'interface/' : $dir;
		$file_dir = '../Runtime/Logs/' . $dir . date('Y') . '/';
		if (!file_exists($file_dir)) {
			if (!@mkdir($file_dir, 0777, true)) {
				die($file_dir . '创建目录失败!');
			}
		}
		$file_dir = $file_dir . date('m') . '/';
		$file_name = date('d') . '.log';
		$fileDir = $file_dir . $file_name;
		if (!file_exists(dirname($fileDir))) {
			if (!@mkdir(dirname($fileDir), 0777, true)) {
				die($fileDir . '创建目录失败!');
			}
		}

		if (is_file($fileDir) && floor(1024 * 1000 * 50) <= filesize($fileDir)) {
			rename($fileDir, dirname($fileDir) . '/' . time() . '-' . basename($fileDir));
		}

		
		 error_log($data,3,$fileDir);
	}
} 
?> 