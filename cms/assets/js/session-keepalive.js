/**
 * Session Keep-Alive Script
 * Prevents session timeout across all browser tabs
 */

(function() {
    'use strict';
    
    let keepAliveInterval = null;
    let isActive = false;
    
    // Keep-alive interval: ping server every 5 minutes (300000ms)
    // Session timeout is 1 hour, so this ensures session stays active
    const KEEP_ALIVE_INTERVAL = 5 * 60 * 1000; // 5 minutes
    
    /**
     * Send keep-alive request to server
     */
    function sendKeepAlive() {
        // Only send if page is still active
        if (document.hidden) {
            return;
        }
        
        fetch('session-keepalive.php', {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 401) {
                // Session expired, show message and redirect to login
                stopKeepAlive();
                alert('Your session has expired. You will be redirected to the login page.');
                window.location.href = 'login.php?timeout=1';
            }
        })
        .catch(error => {
            console.error('Keep-alive failed:', error);
        });
    }
    
    /**
     * Start keep-alive mechanism
     */
    function startKeepAlive() {
        if (isActive) {
            return;
        }
        
        isActive = true;
        
        // Send immediate ping
        sendKeepAlive();
        
        // Set up interval
        keepAliveInterval = setInterval(sendKeepAlive, KEEP_ALIVE_INTERVAL);
        
        // Listen for visibility changes (user switches tabs)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // User returned to tab, send immediate ping
                sendKeepAlive();
            }
        });
        
        // Listen for focus events (user interacts with page)
        window.addEventListener('focus', sendKeepAlive);
        
        console.log('Session keep-alive started');
    }
    
    /**
     * Stop keep-alive mechanism
     */
    function stopKeepAlive() {
        if (!isActive) {
            return;
        }
        
        isActive = false;
        
        if (keepAliveInterval) {
            clearInterval(keepAliveInterval);
            keepAliveInterval = null;
        }
        
        document.removeEventListener('visibilitychange', sendKeepAlive);
        window.removeEventListener('focus', sendKeepAlive);
        
        console.log('Session keep-alive stopped');
    }
    
    // Start when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startKeepAlive);
    } else {
        startKeepAlive();
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', stopKeepAlive);
    
    // Export for manual control if needed
    window.sessionKeepAlive = {
        start: startKeepAlive,
        stop: stopKeepAlive,
        send: sendKeepAlive
    };
})();

