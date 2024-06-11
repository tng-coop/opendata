<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable QR Codes</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 210mm;
            height: 297mm;
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* Reduced to fit larger gaps */
            grid-template-rows: repeat(10, 1fr);  /* Reduced to fit larger gaps */
            gap: 10mm; /* Increased gap for better spacing */
            margin: auto;
            padding: 15mm; /* Increased padding to fit everything on A4 */
            box-sizing: border-box;
        }
        .rectangle {
            width: 25.4mm; /* 1 inch */
            height: 25.4mm; /* 1 inch */
            background-color: lightgray;
            border: 1px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .print-button {
            margin: 20px;
            text-align: center;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
    </script>
</head>
<body>
    <div class="print-button no-print">
        <button onclick="window.print()">Print this page</button>
    </div>
    <div class="container">
        <!-- Create 70 divs with a class of rectangle -->
        <?php for ($i = 0; $i < 40; $i++): ?>
            <div class="rectangle" id="qrcode-<?php echo $i; ?>"></div>
        <?php endfor; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            for (let i = 0; i < 40; i++) {
                let uuid = generateUUID();
                new QRCode(document.getElementById("qrcode-" + i), {
                    text: "http://tng.coop/umb/" + uuid,
                    width: 96, // 96px = 1 inch
                    height: 96  // 96px = 1 inch
                });
            }
        });
    </script>
</body>
</html>
