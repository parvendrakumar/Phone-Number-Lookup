document.getElementById("lookupForm").addEventListener("submit", async e => {
    e.preventDefault();

    const phoneNumber = document.getElementById("phoneNumber").value.trim();
    const countryCode = document.getElementById("countryCode").value;

    if (!phoneNumber) { showError("Please enter a phone number"); return; }
    const phoneRegex = /^\+?[\d\s\-]+$/;
    if (!phoneRegex.test(phoneNumber)) { showError("Invalid phone number format"); return; }

    hideResults();
    hideError();
    document.getElementById("loading").style.display = "block";
    document.querySelector(".btn-submit").disabled = true;

    try {
        const response = await fetch("api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ phoneNumber, countryCode })
        });
        const data = await response.json();
        document.getElementById("loading").style.display = "none";
        document.querySelector(".btn-submit").disabled = false;

        if (data.success) { displayResults(data.data); }
        else { showError(data.error || "Failed to retrieve phone details"); }

    } catch (error) {
        document.getElementById("loading").style.display = "none";
        document.querySelector(".btn-submit").disabled = false;
        showError("Network error: " + error.message);
    }
});

function displayResults(data) {
    document.getElementById("resultPhone").textContent = data.number || "-";
    document.getElementById("resultCountry").textContent = data.country_name || "-";
    document.getElementById("resultCarrier").textContent = data.carrier || "-";
    document.getElementById("resultLocation").textContent = data.location || "-";
    document.getElementById("resultLineType").textContent = data.line_type || "-";
    document.getElementById("resultValid").textContent = data.valid ? "Yes" : "No";

    document.getElementById("results").style.display = "block";
}

function showError(message) {
    document.getElementById("errorMessage").textContent = message;
    document.getElementById("error").style.display = "block";
}

function hideError() { document.getElementById("error").style.display = "none"; }
function hideResults() { document.getElementById("results").style.display = "none"; }

function resetForm() {
    document.getElementById("lookupForm").reset();
    hideResults();
    hideError();
    document.getElementById("phoneNumber").focus();
}
