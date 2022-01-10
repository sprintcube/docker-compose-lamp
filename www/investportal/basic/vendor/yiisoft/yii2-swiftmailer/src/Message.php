<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\swiftmailer;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\mail\BaseMessage;

/**
 * Message implements a message class based on SwiftMailer.
 *
 * @see http://swiftmailer.org/docs/messages.html
 * @see Mailer
 *
 * @method Mailer getMailer() returns mailer instance.
 *
 * @property-write array $headers Headers in format: `[name => value]`.
 * @property int $priority Priority value as integer in range: `1..5`, where 1 is the highest priority and 5
 * is the lowest.
 * @property string $readReceiptTo Receipt receive email addresses. Note that the type of this property
 * differs in getter and setter. See [[getReadReceiptTo()]] and [[setReadReceiptTo()]] for details.
 * @property string $returnPath The bounce email address.
 * @property-write array|callable|\Swift_Signer $signature Signature specification. See [[addSignature()]] for
 * details on how it should be specified.
 * @property-read \Swift_Message $swiftMessage Swift message instance.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Message extends BaseMessage
{
    /**
     * @var \Swift_Message Swift message instance.
     */
    private $_swiftMessage;
    /**
     * @var \Swift_Signer[] attached signers
     */
    private $signers = [];


    /**
     * This method is called after the object is created by cloning an existing one.
     * It ensures [[swiftMessage]] and [[signers]] is also cloned.
     * @since 2.0.7
     */
    public function __clone()
    {
        if ($this->_swiftMessage !== null) {
            $this->_swiftMessage = clone $this->_swiftMessage;
        }
        foreach ($this->signers as $key => $signer) {
            $this->signers[$key] = clone $signer;
        }
    }

    /**
     * @return \Swift_Message Swift message instance.
     */
    public function getSwiftMessage()
    {
        if ($this->_swiftMessage === null) {
            $this->_swiftMessage = $this->createSwiftMessage();
        }

        return $this->_swiftMessage;
    }

    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        return $this->getSwiftMessage()->getCharset();
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        $this->getSwiftMessage()->setCharset($charset);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->getSwiftMessage()->getFrom();
    }

    /**
     * @inheritdoc
     */
    public function setFrom($from)
    {
        $this->getSwiftMessage()->setFrom($from);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->getSwiftMessage()->getReplyTo();
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {
        $this->getSwiftMessage()->setReplyTo($replyTo);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->getSwiftMessage()->getTo();
    }

    /**
     * @inheritdoc
     */
    public function setTo($to)
    {
        $this->getSwiftMessage()->setTo($to);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return $this->getSwiftMessage()->getCc();
    }

    /**
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $this->getSwiftMessage()->setCc($cc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->getSwiftMessage()->getBcc();
    }

    /**
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $this->getSwiftMessage()->setBcc($bcc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->getSwiftMessage()->getSubject();
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->getSwiftMessage()->setSubject($subject);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $this->setBody($text, 'text/plain');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        $this->setBody($html, 'text/html');

        return $this;
    }

    /**
     * Sets the message body.
     * If body is already set and its content type matches given one, it will
     * be overridden, if content type miss match the multipart message will be composed.
     * @param string $body body content.
     * @param string $contentType body content type.
     */
    protected function setBody($body, $contentType)
    {
        $message = $this->getSwiftMessage();
        $oldBody = $message->getBody();
        $charset = $message->getCharset();
        if (empty($oldBody)) {
            $parts = $message->getChildren();
            $partFound = false;
            foreach ($parts as $key => $part) {
                if (!($part instanceof \Swift_Mime_Attachment)) {
                    /* @var $part \Swift_Mime_MimePart */
                    if ($part->getContentType() == $contentType) {
                        $charset = $part->getCharset();
                        unset($parts[$key]);
                        $partFound = true;
                        break;
                    }
                }
            }
            if ($partFound) {
                reset($parts);
                $message->setChildren($parts);
                $message->addPart($body, $contentType, $charset);
            } else {
                $message->setBody($body, $contentType);
            }
        } else {
            $oldContentType = $message->getContentType();
            if ($oldContentType == $contentType) {
                $message->setBody($body, $contentType);
            } else {
                $message->setBody(null);
                $message->setContentType(null);
                $message->addPart($oldBody, $oldContentType, $charset);
                $message->addPart($body, $contentType, $charset);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {
        $attachment = \Swift_Attachment::fromPath($fileName);
        if (!empty($options['fileName'])) {
            $attachment->setFilename($options['fileName']);
        }
        if (!empty($options['contentType'])) {
            $attachment->setContentType($options['contentType']);
        }
        $this->getSwiftMessage()->attach($attachment);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attachContent($content, array $options = [])
    {
        $attachment = new \Swift_Attachment($content);
        if (!empty($options['fileName'])) {
            $attachment->setFilename($options['fileName']);
        }
        if (!empty($options['contentType'])) {
            $attachment->setContentType($options['contentType']);
        }
        if (!empty($options['setDisposition'])) {
          $attachment->setDisposition($options['setDisposition']);
        }
        $this->getSwiftMessage()->attach($attachment);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function embed($fileName, array $options = [])
    {
        $embedFile = \Swift_EmbeddedFile::fromPath($fileName);
        if (!empty($options['fileName'])) {
            $embedFile->setFilename($options['fileName']);
        }
        if (!empty($options['contentType'])) {
            $embedFile->setContentType($options['contentType']);
        }

        return $this->getSwiftMessage()->embed($embedFile);
    }

    /**
     * @inheritdoc
     */
    public function embedContent($content, array $options = [])
    {
        $embedFile = new \Swift_EmbeddedFile($content);
        if (!empty($options['fileName'])) {
            $embedFile->setFilename($options['fileName']);
        }
        if (!empty($options['contentType'])) {
            $embedFile->setContentType($options['contentType']);
        }

        return $this->getSwiftMessage()->embed($embedFile);
    }

    /**
     * Sets message signature
     * @param array|callable|\Swift_Signer $signature signature specification.
     * See [[addSignature()]] for details on how it should be specified.
     * @return $this self reference.
     * @since 2.0.6
     */
    public function setSignature($signature)
    {
        if (!empty($this->signers)) {
            // clear previously set signers
            $swiftMessage = $this->getSwiftMessage();
            foreach ($this->signers as $signer) {
                $swiftMessage->detachSigner($signer);
            }
            $this->signers = [];
        }
        return $this->addSignature($signature);
    }

    /**
     * Adds message signature.
     * @param array|callable|\Swift_Signer $signature signature specification, this can be:
     *
     * - [[\Swift_Signer]] instance
     * - callable, which returns [[\Swift_Signer]] instance
     * - configuration array for the signer creation
     *
     * @return $this self reference
     * @throws InvalidConfigException on invalid signature configuration
     * @since 2.0.6
     */
    public function addSignature($signature)
    {
        if ($signature instanceof \Swift_Signer) {
            $signer = $signature;
        } elseif (is_callable($signature)) {
            $signer = call_user_func($signature);
        } elseif (is_array($signature)) {
            $signer = $this->createSwiftSigner($signature);
        } else {
            throw new InvalidConfigException('Signature should be instance of "Swift_Signer", callable or array configuration');
        }

        $this->getSwiftMessage()->attachSigner($signer);
        $this->signers[] = $signer;

        return $this;
    }

    /**
     * Creates signer from it's configuration.
     * @param array $signature signature configuration:
     * `[type: string, key: string|null, file: string|null, domain: string|null, selector: string|null]`
     * @return \Swift_Signer signer instance
     * @throws InvalidConfigException on invalid configuration provided
     * @since 2.0.6
     */
    protected function createSwiftSigner($signature)
    {
        if (!isset($signature['type'])) {
            throw new InvalidConfigException('Signature configuration should contain "type" key');
        }
        $signature['type'] = strtolower($signature['type']);
        if (!in_array($signature['type'], ['dkim', 'opendkim'], true)) {
            throw new InvalidConfigException("Unrecognized signature type '{$signature['type']}'");
        }

        if (isset($signature['key'])) {
            $privateKey = $signature['key'];
        } elseif (isset($signature['file'])) {
            $privateKey = file_get_contents(Yii::getAlias($signature['file']));
        } else {
            throw new InvalidConfigException("Either 'key' or 'file' signature option should be specified");
        }
        $domain = ArrayHelper::getValue($signature, 'domain');
        $selector = ArrayHelper::getValue($signature, 'selector');

        if ($signature['type'] === 'opendkim') {
            Yii::warning(__METHOD__ . '(): signature type "opendkim" is deprecated, use "dkim" instead.');
            return new \Swift_Signers_OpenDKIMSigner($privateKey, $domain, $selector);
        }

        return new \Swift_Signers_DKIMSigner($privateKey, $domain, $selector);
    }

    /**
     * @inheritdoc
     */
    public function toString()
    {
        return $this->getSwiftMessage()->toString();
    }

    /**
     * Creates the Swift email message instance.
     * @return \Swift_Message email message instance.
     */
    protected function createSwiftMessage()
    {
        return new \Swift_Message();
    }

    // Headers setup :

    /**
     * Adds custom header value to the message.
     * Several invocations of this method with the same name will add multiple header values.
     * @param string $name header name.
     * @param string $value header value.
     * @return $this self reference.
     * @since 2.0.6
     */
    public function addHeader($name, $value)
    {
        $this->getSwiftMessage()->getHeaders()->addTextHeader($name, $value);
        return $this;
    }

    /**
     * Sets custom header value to the message.
     * @param string $name header name.
     * @param string|array $value header value or values.
     * @return $this self reference.
     * @since 2.0.6
     */
    public function setHeader($name, $value)
    {
        $headerSet = $this->getSwiftMessage()->getHeaders();

        if ($headerSet->has($name)) {
            $headerSet->remove($name);
        }

        foreach ((array)$value as $v) {
            $headerSet->addTextHeader($name, $v);
        }

        return $this;
    }

    /**
     * Returns all values for the specified header.
     * @param string $name header name.
     * @return array header values list.
     * @since 2.0.6
     */
    public function getHeader($name)
    {
        $headerSet = $this->getSwiftMessage()->getHeaders();
        if (!$headerSet->has($name)) {
            return [];
        }

        $headers = [];
        foreach ($headerSet->getAll($name) as $header) {
            $headers[] = $header->getValue();
        }
        return $headers;
    }

    /**
     * Sets custom header values to the message.
     * @param array $headers headers in format: `[name => value]`.
     * @return $this self reference.
     * @since 2.0.7
     */
    public function setHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        return $this;
    }

    // SwiftMessage shortcuts :

    /**
     * Set the return-path (the bounce address) of this message.
     * @param string $address the bounce email address.
     * @return $this self reference.
     * @since 2.0.6
     */
    public function setReturnPath($address)
    {
        $this->getSwiftMessage()->setReturnPath($address);
        return $this;
    }

    /**
     * Returns the return-path (the bounce address) of this message.
     * @return string the bounce email address.
     * @since 2.0.6
     */
    public function getReturnPath()
    {
        return $this->getSwiftMessage()->getReturnPath();
    }

    /**
     * Set the priority of this message.
     * @param int $priority priority value, should be an integer in range: `1..5`,
     * where 1 is the highest priority and 5 is the lowest.
     * @return $this self reference.
     * @since 2.0.6
     */
    public function setPriority($priority)
    {
        $this->getSwiftMessage()->setPriority($priority);
        return $this;
    }

    /**
     * Returns the priority of this message.
     * @return int priority value as integer in range: `1..5`,
     * where 1 is the highest priority and 5 is the lowest.
     * @since 2.0.6
     */
    public function getPriority()
    {
        return $this->getSwiftMessage()->getPriority();
    }

    /**
     * Sets the ask for a delivery receipt from the recipient to be sent to $addresses.
     * @param string|array $addresses receipt receive email address(es).
     * @return $this self reference.
     * @since 2.0.6
     */
    public function setReadReceiptTo($addresses)
    {
        $this->getSwiftMessage()->setReadReceiptTo($addresses);
        return $this;
    }

    /**
     * Get the addresses to which a read-receipt will be sent.
     * @return string receipt receive email addresses.
     * @since 2.0.6
     */
    public function getReadReceiptTo()
    {
        return $this->getSwiftMessage()->getReadReceiptTo();
    }
}
