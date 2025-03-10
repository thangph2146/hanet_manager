<?php
/**
 * Đây là file class Token liên quan đến bảo mật và mã hóa
 * @author Phùng Duy Vũ <vupd@buh.edu.vn>
 * 4/2/2022
 * 10:48 PM
 *
 * @func getValue() trả về chuỗi token
 * @func getHash() trả về chuỗi đã mã hóa với token
 */
namespace App\Libraries;

class Token
{
	/**
	 * @var mixed|string
	 */
	private $token;

	/**
	 * @param null $token
	 * @throws \Exception
	 */
	public function __construct($token = null)
	{
		if ($token === null) {
			
			$this->token = bin2hex(random_bytes(16));
			
		} else {
			
			$this->token = $token;
			
		}
	}

	/**
	 * @return mixed|string
	 */
	public function getValue()
	{
		return $this->token;
	}

	/**
	 * @return false|string
	 */
	public function getHash()
	{
		return hash_hmac('sha256', $this->token, $_ENV['HASH_SECRET_KEY']);
	}
}
