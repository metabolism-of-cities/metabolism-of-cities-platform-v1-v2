<?php
/**
 * Mail Functions
 *
 * We use PEAR to send HTML mail. Rather than repeating the same code again
 * and again, we call the function pearMail with more or less the same parameters
 * as the regular PHP mail() function, so that we can centralize the way
 * mail is sent. Also, if a server does not support / have PEAR, we can change
 * only this function and the rest will be fine. If you need to send a more
 * complicated mail (e.g. with attachments), then you can still include this
 * file to get the PEAR includes, but you'll have to write the code instead 
 * of using this function. 
 *
 * PHP 5
 *
 * Uses PEAR - PHP Extension and Application Repository
 * PEAR is BSD Licensed
 * More at http://pear.php.net/
 */


require_once 'Mail.php';
require_once 'Mail/mime.php';

function pearMail($to, $subject, $text, $from = false) {

  if (!$from) {
    $from = SITENAME . '<' . EMAIL . '>';
  }

  $headers = array(
    'From'    => $from,
    'Subject' => utf8_decode($subject),
  );

  if ($to != EMAIL) {
    $headers['Bcc'] = EMAIL;
  }

  if ($return_path) {
    $headers['Return-Path'] = WEBMASTER_MAIL;
  }

  $html = bbcode($text);

  $mail_header = '<td style="background:#CF4803;height:30px;text-align:center;color:#fff;font-size:15px;font-weight:bold"></td>';

  $html = 
  '<html>
    <head>
    <style type="text/css">
      body{font-family: Arial, sans-serif;}
      a{color: #0088cc}
      a:hover {color: #005580;text-decoration: none;}
      td,th{padding:0px}
      body,html,table{margin:0;padding:0}
      table{width:600px;margin:0 auto}
      body{padding:5px}
      table {
        border-collapse: collapse;
        border-spacing: 0;
      }
      body,td,tr{font-size:13px}
      h1{font-size:15px}
    </style>
    </head>
      <body>

      <table>

      <tr>
        '.$mail_header.'
      </tr>
      <tr>
        <td style="padding:20px 6px">   
      ' . $html . '
      </td>
      </tr>
      <tr>
          <td style="background:#CF4803;color:#fff;font-size:11px;height:30px;text-align:center">
            ' . URL . ' | ' . EMAIL . '
          </td>
        </tr>
        </table>
        </body>
      </html>';

    $html = utf8_decode($html);
    $text = utf8_decode($text);

  // We never want mails to send out from local machines. It's all too easy
  // to accidentally send out a test mail to a client or their clients. 
  if (LOCAL) {
    //die($html);
  } else {
    $mime = new Mail_mime();
    $mime->setTXTBody($text);

    // Add standard CSS or other mail headers to this string, and all mails will
    // be styled uniformly. 
    $mime->setHTMLBody($html);
    $body = $mime->get();
    $hdrs = $mime->headers($headers);
    $mail =& Mail::factory('mail');
    $mail->send($to, $hdrs, $body);
  }
}

?>
