<style>
    .explanation {
        width: 100%;
        /* Box takes full width, with text aligned to the right */
        cursor: pointer;
        text-align: right;
        transition: all 0.3s ease;
    }

    .explanation p {
        display: none;
        /* Hide full message initially */
        text-align: left;
        padding: 10px;
        margin-top: 5px;
        /* Space between title and expanded content */
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="explanation" onclick="toggleContent()">
    <strong>Open Data Project</strong>
    <p>This project aims to provide open access to data for public use. Our mission is to enhance transparency, promote research, and empower communities through free and open access to data.</p>
</div>

<script>
    function toggleContent() {
        var content = document.querySelector('.explanation p');
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    }
</script>