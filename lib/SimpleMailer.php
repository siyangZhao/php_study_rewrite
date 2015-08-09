<?php 

class SimpleMailer
{
	private $host;

	private $port = 25;

	private $user;

	private $pass;

	private $debug = true;

	private $sock;

	private $mail_format = 0;

    private $errors = [];

	public function __construct($host, $port, $user, $pass, $format = 1, $debug = 1)
	{
		$this->host = $host;
		$this->post = $port;
		$this->user = base64_encode($user);
        $this->pass = base64_encode($pass);
        $this->mail_format = $format;
        $this->debug = $debug;

        $errno = '';
        $errstr = '';
        $this->sock = fsockopen($this->host, $this->port, $errno, $errstr, 10 );
        if (!$this->sock)
        {
            $this->errors[] = ['code' => 100, 'details' => "Error number:{$errno},Error message:{$errstr}"."连接服务器失败"];
            return false;
        }

        $response = fgets($this->sock);
        if (strstr($response,'220') === false)
        {
            $this->errors[] = ['code' => 101, 'details' => "Server Error:$response"."取得服务器信息失败"];
            return false;
        }

	}

    public function show_debug()
    {
        if ($this->debug)
        {
           if (!empty($this->errors))
           {
                var_dump($this->errors);
           }
        }
    }

    private function do_command($cmd, $return_code)
    {
        fwrite($this->sock, $cmd);

        $response = fgets($this->sock);
        if (strstr($response,"{$return_code}") === false)
        {
           
            $this->errors[] = ['code' => 104, 'details' => "执行命令{$cmd}出错"."return_code:{$return_code}"]; 
            return false;
        }

        return true;
    }

    public function  is_email($email)
    {
        $pattren = '/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/i';
        if (preg_match($pattren, $email))
        {
            return true;
        } else {
            
            return false;
        }
    }

    public function send_email($from, $to, $subject, $body)
    {
        if (!$this->is_email($from) or !$this->is_email($to))
        {
            $this->errors[] = ['code' => 102, 'details' => "发送信箱格式不正确！"];
            return false;
        }

        if (empty($subject) or empty($body))
        {
            $this->errors[] = ['code' => 103, 'details' => "目的信箱格式不正确！"];
            return false;
        }

        $detail = '';
        $detail .= "From:".$from."\r\n";
        $detail .= "To:".$to."\r\n";
        $detail .= "Subject:".$subject."\r\n";

        if ($this->mail_format == 1)
        {
            $detail .= "Content - Type: text/html;\r\n";
        } else {
            $detail .= "Content - Type: text/plain;\r\n";
        }

        $detail .= "charset=utf8\r\n\r\n";
        $detail .= $body;

        if (! $this->do_command("HELO {$this->host}\r\n", 250))      return false;
        if (! $this->do_command("AUTH LOGIN\r\n", 334))            return false;
        if (! $this->do_command($this->user."\r\n", 334))          return false;
        if (! $this->do_command($this->pass."\r\n", 235))          return false;
        if (! $this->do_command("MAIL FROM:".$from."\r\n", 250))   return false;
        if (! $this->do_command("RCPT TO:".$to."\r\n", 250))       return false;
        if (! $this->do_command("DATA\r\n", 354))                  return false;
        if (! $this->do_command($detail."\r\n.\r\n",250))          return false;
        if (! $this->do_command("QUIT\r\n", 221))                  return false;

        return true;
    }
}
