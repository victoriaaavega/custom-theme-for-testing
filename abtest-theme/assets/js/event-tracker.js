/**
 * AB Test Event Tracker
 *
 * Reads the experiment configuration defined in window.abTestConfig and registers click listeners for each element,
 * when a click is detected, it sends a hit to the WordPress REST API endpoint which forwards it to Flagship
 * 
 */

(function () {

    /**
     * Sends a hit event to the WordPress REST API endpoint
     *
     * @param {string} experimentId
     * @param {string} eventName
     * @param {string} variant
     */
    function sendHit(experimentId, eventName, variant) {
        const { visitorId, apiUrl, nonce } = window.abTestData;

        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': nonce
            },
            body: JSON.stringify({
                visitor_id:    visitorId,
                experiment_id: experimentId,
                event_name:    eventName,
                variant:       variant
            })
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            console.log('[AB Test] Hit sent:', data);
        })
        .catch(function (error) {
            console.error('[AB Test] Failed to send hit:', error);
        });
    }

    /**
     * Registers click listeners for all experiments defined in window.abTestConfig
     */
    function registerListeners() {
        if (!window.abTestConfig || !window.abTestData) {
            console.warn('[AB Test] abTestConfig or abTestData not found.');
            return;
        }

        window.abTestConfig.forEach(function (config) {
            const element = document.querySelector(config.selector);

            if (!element) {
                console.warn('[AB Test] Element not found for selector:', config.selector);
                return;
            }

            const variant = window.abTestData.experiments[config.experimentId];

            const eventType = config.type || 'click';

            element.addEventListener(eventType, function () {
                console.log('[AB Test] Event detected:', eventType, 'on:', config.selector);
                sendHit(config.experimentId, config.eventName, variant);
            });

            console.log('[AB Test] Listener registered for:', config.selector);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', registerListeners);
    } else {
        registerListeners();
    }

})();