class ActiveUsersUpdater {
    constructor({ modelId, userContainerElementId, updateInterval = 5000, textCallback = (activeUsersCount) => `You + ${activeUsersCount}` }) {
        if (!modelId || !userContainerElementId) {
            throw new Error("Both 'modelId' and 'userContainerElementId' are required options.");
        }
        this.modelId = modelId;
        this.userContainerElementId = userContainerElementId;
        this.updateInterval = updateInterval;
        this.textCallback = textCallback;
        this._interval = null;
    }

    // Updates the UI with the list of active users
    updateUI(userIds) {
        const activeUsersCount = userIds.length - 1;
        const usersContainer = document.getElementById(this.userContainerElementId);

        if (!usersContainer) {
            console.error(`Element with ID '${this.userContainerElementId}' not found.`);
            return;
        }

        usersContainer.innerHTML = '';
        if (activeUsersCount > 0) {
            const userElement = document.createElement('div');
            // Use the provided callback to generate the text content
            userElement.textContent = this.textCallback(activeUsersCount);
            usersContainer.appendChild(userElement);
        }
    }

    // Fetches the access list and updates the UI
    async updateAccessList() {
        try {
            const response = await fetch(
                `/de/prototype/api/less/update-access-list?id=${this.modelId}&fields=recentAccessList`,
                {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }
            );
            const data = await response.json();

            if (data.recentAccessList) {
                const userIds = Object.keys(data.recentAccessList);
                this.updateUI(userIds);
            } else {
                throw new Error(`Could not find recent access list for ID ${this.modelId}`);
            }
        } catch (err) {
            console.error(err.message);
            this.stop(); // Stops the interval on error
        }
    }

    // Starts the updater
    start() {
        this.updateAccessList();
        this._interval = setInterval(() => {
            if (!document.hidden) {
                this.updateAccessList();
            }
        }, this.updateInterval);
    }

    // Stops the updater
    stop() {
        if (this._interval) {
            clearInterval(this._interval);
            this._interval = null;
        }
    }
}

// Example usage
const options = {
    modelId: 3,
    userContainerElementId: 'active-users',
    updateInterval: 5000, // Optional, defaults to 5000ms
    textCallback: (activeUsersCount) => `You and ${activeUsersCount} others are active` // Custom callback to generate the text
};

const activeUsersUpdater = new ActiveUsersUpdater(options);
activeUsersUpdater.start();
