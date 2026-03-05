<?php

/**
 * Generates a unique visitor ID based on request parameters.
 * This ID is used to consistently identify a visitor across sessions
 * without relying on cookies or login.
 */
class Fingerprint {

    /**
     * Generates a unique visitor ID based on request parameters.
     *
     * @return string SHA256 hash used as the visitor ID
     */
    public function generateVisitorId(): string {
        $data = [
            'ip'              => $this->getClientIp(),
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'accept_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'unknown',
        ];

        return hash('sha256', implode('|', $data));
    }

    /**
     * Gets the real client IP, taking into account proxies.
     * Checks headers in order of priority:
     * Cloudflare → Load balancer → Nginx proxy → Direct connection
     *
     * @return string Client IP address
     */
    private function getClientIp(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Load balancers / proxies
            'HTTP_X_REAL_IP',        // Nginx proxy
            'REMOTE_ADDR',           // Direct connection
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = trim(explode(',', $_SERVER[$header])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0';
    }
}