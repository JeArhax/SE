<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner with Instascan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        #video {
            width: 100%;
            height: auto;
            border: 1px solid black;
            display: none; /* Hide video initially */
        }
        #result {
            margin-top: 20px;
            font-size: 1.2em;
            color: green;
        }
        #start-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <button id="start-button">Open Camera</button>
    <video id="video"></video>
    <div id="result">Scan a QR code to see the result here.</div>

    <script src="https://unpkg.com/instascan@latest/instascan.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const resultDiv = document.getElementById('result');
        const startButton = document.getElementById('start-button');
        const scanner = new Instascan.Scanner({ video: video });

        scanner.addListener('scan', function(content) {
            resultDiv.innerText = `Scanned Result: ${content}`;
        });

        startButton.addEventListener('click', function() {
            Instascan.Camera.getCameras()
                .then(function(cameras) {
                    if (cameras.length > 0) {
                        // Show the video element and start the scanner
                        video.style.display = 'block';
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        resultDiv.innerText = "No cameras found. Please check your device.";
                    }
                })
                .catch(function(e) {
                    console.error(e);
                    resultDiv.innerText = "Error accessing camera. Please check permissions.";
                });
        });
    </script>
</body>
</html>