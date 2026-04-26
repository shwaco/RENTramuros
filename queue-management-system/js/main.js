// global state variables shts
var waitingTourists = [];
var historyTours = [];
var claimTimerInterval = null;
var currentQueuePosition = typeof queuePosition !== 'undefined' ? queuePosition : null;

// iniinitialize lahat ng dashboard functions kapag nag-load na yung DOM
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    initWaitingLobby();
    startPolling();
    initProfileDropdown();
});