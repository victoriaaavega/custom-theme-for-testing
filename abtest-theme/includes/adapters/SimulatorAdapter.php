<?php

/**
 * Simulates AB Tasty Flagship SDK in Bucketing mode.
 *
 * In production, this adapter would be replaced by FlagshipAdapter
 * which uses the real Flagship PHP SDK.
 *
 * This simulation replicates the same deterministic behavior:
 * the same visitorId + experimentId always gets the same variant.
 */
class SimulatorAdapter implements DecisionAdapterInterface {

    private const TRAFFIC_SPLIT = 50; // 50% control, 50% variation_b

    /**
     * Decides which variant a visitor should see.
     * Uses a deterministic hash so the same visitor always gets the same variant.
     *
     * @param string $visitorId
     * @param string $experimentId
     * @return string "control" or "variation_b"
     */
    public function decide(string $visitorId, string $experimentId): string {
        // Combine visitorId and experimentId so the same visitor
        // can get different variants across different experiments
        $bucket = abs(crc32($visitorId . $experimentId)) % 100;

        return $bucket < self::TRAFFIC_SPLIT ? 'control' : 'variation_b';
    }
}