<?php

/**
 * Contract that any decision engine must fulfill.
 * 
 * This interface decouples the experiment runner from any specific
 * decision tool (Flagship, custom simulator, etc.).
 * To swap decision engines, just implement this interface and
 * pass the new adapter to ExperimentRunner.
 */
interface DecisionAdapterInterface {
    /**
     * Decides which variant a visitor should see for a specific experiment.
     * The decision must be deterministic: the same visitorId + experimentId
     * must always return the same variant.
     *
     * @param string $visitorId   Unique visitor identifier from fingerprinting
     * @param string $experimentId Unique experiment identifier
     * @return string The variant name e.g. "control" or "variation_b"
     */
    public function decide(string $visitorId, string $experimentId): string;
}