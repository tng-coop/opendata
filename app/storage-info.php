<!-- storage-info.php -->
<div id="storage-info" style="margin-top: 20px;">
    <h2>Storage Information</h2>
    <div id="cookies-info">
        <h3>Cookies:</h3>
        <pre id="cookies-data"></pre>
    </div>
    <div id="local-storage-info">
        <h3>Local Storage:</h3>
        <pre id="local-storage-data"></pre>
    </div>
    <div id="session-storage-info">
        <h3>Session Storage:</h3>
        <pre id="session-storage-data"></pre>
    </div>
</div>

<script>
    function displayCookies() {
        const cookies = document.cookie.split(';').reduce((cookies, cookie) => {
            const [name, value] = cookie.split('=').map(c => c.trim());
            cookies[name] = value;
            return cookies;
        }, {});
        document.getElementById('cookies-data').textContent = JSON.stringify(cookies, null, 2);
    }

    function displayLocalStorage() {
        const localStorageData = { ...localStorage };
        document.getElementById('local-storage-data').textContent = JSON.stringify(localStorageData, null, 2);
    }

    function displaySessionStorage() {
        const sessionStorageData = { ...sessionStorage };
        document.getElementById('session-storage-data').textContent = JSON.stringify(sessionStorageData, null, 2);
    }

    function displayStorageInfo() {
        displayCookies();
        displayLocalStorage();
        displaySessionStorage();
    }

    document.addEventListener('DOMContentLoaded', displayStorageInfo);
</script>
