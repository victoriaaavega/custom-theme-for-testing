<?php

if (class_exists('FlagshipAdapter')) {
    return;
}

/**
 * AB Tasty Flagship SDK Adapter.
 *
 * This adapter will use the real Flagship PHP SDK in Bucketing mode.
 * Bucketing mode makes decisions locally using a campaigns JSON file
 * kept updated by the flagship-sync-agent, with no network latency.
 *
 * TODO: implement when Flagship credentials and SDK are available.
 *
 * Installation:
 * composer require flagship-io/flagship-php-sdk
 *
 * Usage example with real SDK:
 *
 * Flagship::start(ENV_ID, API_KEY, FlagshipConfig::bucketing(BUCKETING_URL));
 * $visitor = Flagship::newVisitor($visitorId, true)->build();
 * $visitor->fetchFlags();
 * return $visitor->getFlag($experimentId)->getValue('control');
 */
class FlagshipAdapter implements DecisionAdapterInterface {

    public function decide(string $visitorId, string $experimentId): string {
        // TODO: replace with real Flagship SDK implementation
        throw new \RuntimeException(
            'FlagshipAdapter is not implemented yet. Use SimulatorAdapter instead.'
        );
    }
}