/**
 * Check-in WebSocket Client
 * Used for real-time updates of check-in displays
 */
class CheckinWebSocketClient {
    constructor(serverUrl, eventId) {
        this.serverUrl = serverUrl;
        this.eventId = eventId;
        this.ws = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectInterval = 5000; // 5 seconds
        this.callbacks = {
            onCheckin: [],
            onConnect: [],
            onDisconnect: [],
            onError: []
        };
    }
    
    /**
     * Connect to WebSocket server
     */
    connect() {
        this.ws = new WebSocket(this.serverUrl);
        
        this.ws.onopen = () => {
            console.log('Connected to WebSocket server');
            this.reconnectAttempts = 0;
            
            // Subscribe to event
            this.ws.send(JSON.stringify({
                type: 'subscribe',
                eventId: this.eventId
            }));
            
            // Execute callbacks
            this._executeCallbacks('onConnect');
        };
        
        this.ws.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                
                // Handle check-in notifications
                if (data.type === 'checkin_notification' && data.data.eventId === this.eventId) {
                    this._executeCallbacks('onCheckin', data.data);
                }
            } catch (error) {
                console.error('Error parsing WebSocket message:', error);
            }
        };
        
        this.ws.onclose = () => {
            console.log('Disconnected from WebSocket server');
            this._executeCallbacks('onDisconnect');
            
            // Attempt to reconnect
            if (this.reconnectAttempts < this.maxReconnectAttempts) {
                this.reconnectAttempts++;
                
                setTimeout(() => {
                    console.log(`Attempting to reconnect (${this.reconnectAttempts}/${this.maxReconnectAttempts})...`);
                    this.connect();
                }, this.reconnectInterval);
            } else {
                console.error('Max reconnect attempts reached');
            }
        };
        
        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
            this._executeCallbacks('onError', error);
        };
    }
    
    /**
     * Execute callbacks of a specific type
     * 
     * @param {string} type Callback type
     * @param {any} data Data to pass to callbacks
     */
    _executeCallbacks(type, data = null) {
        if (this.callbacks[type]) {
            this.callbacks[type].forEach(callback => {
                callback(data);
            });
        }
    }
    
    /**
     * Register a callback for check-in events
     * 
     * @param {Function} callback Function to call when a check-in occurs
     * @return {CheckinWebSocketClient}
     */
    onCheckin(callback) {
        this.callbacks.onCheckin.push(callback);
        return this;
    }
    
    /**
     * Register a callback for connection established
     * 
     * @param {Function} callback Function to call when connection is established
     * @return {CheckinWebSocketClient}
     */
    onConnect(callback) {
        this.callbacks.onConnect.push(callback);
        return this;
    }
    
    /**
     * Register a callback for disconnection
     * 
     * @param {Function} callback Function to call when disconnected
     * @return {CheckinWebSocketClient}
     */
    onDisconnect(callback) {
        this.callbacks.onDisconnect.push(callback);
        return this;
    }
    
    /**
     * Register a callback for errors
     * 
     * @param {Function} callback Function to call on error
     * @return {CheckinWebSocketClient}
     */
    onError(callback) {
        this.callbacks.onError.push(callback);
        return this;
    }
    
    /**
     * Disconnect from WebSocket server
     */
    disconnect() {
        if (this.ws) {
            this.ws.close();
        }
    }
}
