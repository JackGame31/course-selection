function showToast(message, type = "success") {
    const icons = {
        success: "✓",
        error: "✕",
    };
    const container = document.getElementById("toast-container");
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerHTML = `
                <span class="toast-icon">${icons[type] ?? "•"}</span>
                <span class="toast-body">${message}</span>
                <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">&#x2715;</button>
            `;
    container.appendChild(toast);
    setTimeout(() => dismissToast(toast), 4500);
}

function dismissToast(toast) {
    if (!toast) return;
    toast.classList.add("hiding");
    setTimeout(() => toast.remove(), 300);
}

console.log("Toast script loaded");
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded and parsed");
    console.log("Session messages:", _sessions);
    if (_sessions.success) showToast(_sessions.success, "success");
    if (_sessions.error) showToast(_sessions.error, "error");
});

window.showToast = showToast;
window.dismissToast = dismissToast;
