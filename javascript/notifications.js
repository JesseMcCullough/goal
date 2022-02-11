let notifications = document.querySelector(".notifications");

function addNotification(text, duration, type) {
    let notification = document.createElement("p");
    notification.innerHTML = text;

    if (type) {
        notification.classList.add(type);
    }

    notifications.appendChild(notification);

    // Fade in, requires a delay
    setTimeout(function() {
        notification.style.opacity = 1;
    }, 100);

    if (duration) {
        let fadeOutDuration = 0.4 * 1000; // grabbing 0.4 from stylesheet isn't working. supplying it here.

        setTimeout(function() {
            notification.style.opacity = "0"; // Fade out
            setTimeout(function() {
                notification.remove(); // Remove notification after fade out.
            }, fadeOutDuration);
        }, duration);
    }
}
