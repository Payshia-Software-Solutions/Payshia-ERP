// contentScript.js

// Send a message to the extension's background script
chrome.runtime.sendMessage({ message: 'Hello from content script!' }, function(response) {
    console.log('Message sent from content script');
    chrome.runtime.sendMessage({ action: 'print' });
});