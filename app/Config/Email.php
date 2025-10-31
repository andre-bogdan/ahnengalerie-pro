<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $protocol = 'mail';
    public string $fromEmail = 'noreply@ihre-domain.de';
    public string $fromName = 'Ahnengalerie Pro';
    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public bool $validate = true;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    
    public string $SMTPHost = '';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int $SMTPPort = 25;
    public int $SMTPTimeout = 5;
    public string $SMTPCrypto = '';
    public bool $SMTPKeepAlive = false;

    public function __construct()
    {
        parent::__construct();
        if (getenv('email.fromEmail')) {
            $this->fromEmail = getenv('email.fromEmail');
        }
        if (getenv('email.fromName')) {
            $this->fromName = getenv('email.fromName');
        }
    }
}