<?php


namespace HyperfLib\Library\Utils;

use HyperfLib\Exception\EmptyException;
use HyperfLib\Library\Cache\Redis;
use HyperfLib\Library\Logger\Logger;
use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

class Email
{
    /**
     * 邮件发送
     *
     * @param $email
     * @param $subject
     * @param $content
     * @param string $title
     * @throws Throwable
     */
    public static function send($email, $subject, $content, $title = '新世相系统')
    {
        if (!$email || !$subject || !$content) {
            return;
        }

        $mail = new PHPMailer(true);
        $config = getConfig('email.system_notice');
        try {
            if (!$config['username']) {
                throw new EmptyException('email config null');
            }
            $email = is_string($email) ? [$email] : $email;
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = 'ssl';
            $mail->Port = $config['port'];
            $mail->setFrom($config['username'], $title);
            foreach ($email as $item) {
                $mail->addAddress($item);
            }
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;
            $mail->send();
        } catch (Throwable $e) {
            Logger::get()->error(sprintf("email error %s, name %s", $e->getMessage(), $email), [
                'subject' => $subject,
                'content' => $content,
            ]);
            throw $e;
        }
    }

    /**
     * 每十分钟才能发送同样标题的邮件
     *
     * @param $email
     * @param $subject
     * @param $content
     * @param string $title
     * @param int $ttl
     * @return bool
     * @throws Throwable
     */
    public static function smartSendMail($email, $subject, $content, $title = '新世相系统', int $ttl = 600)
    {
        $md5 = md5(encode(['email' => $email]) . $subject);
        $key = env('APP_NAME') . '#SMART_SEND_MAIL#' . $md5;
        $redis = Redis::getContainer();
        if (empty($redis->get($key))) {
            self::send($email, $subject, $content, $title);
            $redis->setex($key, $ttl, encode(['email' => $email]));
        }
        return true;
    }
}
