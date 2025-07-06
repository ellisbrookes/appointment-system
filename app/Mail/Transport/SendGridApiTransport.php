<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class SendGridApiTransport extends AbstractTransport
{
    private string $apiKey;
    private string $endpoint = 'https://api.sendgrid.com/v3/mail/send';

    public function __construct(string $apiKey, EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        $this->apiKey = $apiKey;
        parent::__construct($dispatcher, $logger);
    }

    public function __toString(): string
    {
        return 'sendgrid+api://api.sendgrid.com';
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        if (!$this->apiKey) {
            throw new \Exception('SendGrid API key is not configured');
        }
        
        $payload = [
            'personalizations' => [
                [
                    'to' => $this->formatAddresses($email->getTo()),
                    'subject' => $email->getSubject(),
                ]
            ],
            'from' => $this->formatAddress($email->getFrom()[0]),
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $email->getHtmlBody() ?: $email->getTextBody()
                ]
            ]
        ];

        // Add CC if present
        if ($email->getCc()) {
            $payload['personalizations'][0]['cc'] = $this->formatAddresses($email->getCc());
        }

        // Add BCC if present
        if ($email->getBcc()) {
            $payload['personalizations'][0]['bcc'] = $this->formatAddresses($email->getBcc());
        }

        // Add reply-to if present
        if ($email->getReplyTo()) {
            $payload['reply_to'] = $this->formatAddress($email->getReplyTo()[0]);
        }

        $response = $this->makeRequest($payload);

        if ($response['status'] >= 400) {
            $errorMessage = 'SendGrid API error (HTTP ' . $response['status'] . '): ' . ($response['body'] ?? 'Unknown error');
            
            // Log the error for debugging
            if ($this->logger) {
                $this->logger->error($errorMessage, [
                    'payload' => $payload,
                    'response' => $response
                ]);
            }
            
            throw new \Exception($errorMessage);
        }
    }

    private function formatAddresses(array $addresses): array
    {
        $formatted = [];
        foreach ($addresses as $address) {
            $formatted[] = $this->formatAddress($address);
        }
        return $formatted;
    }

    private function formatAddress($address): array
    {
        return [
            'email' => $address->getAddress(),
            'name' => $address->getName() ?: null
        ];
    }

    private function makeRequest(array $payload): array
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL error: ' . $error);
        }

        if ($response === false) {
            throw new \Exception('Failed to get response from SendGrid API');
        }

        return [
            'status' => $httpCode,
            'body' => $response
        ];
    }
}
