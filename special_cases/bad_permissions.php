<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Access Denied</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #2c3e50;
      font-family: 'Arial', sans-serif;
    }

    .message-container {
      text-align: center;
      color: #ecf0f1;
      animation: fade-in-out 4s ease-in-out forwards;
    }

    .message-container h1 {
      font-size: 2.5rem;
      margin: 0;
      opacity: 0;
      animation: slide-down 0.5s forwards, pulse 1.5s infinite alternate;
    }

    .message-container p {
      font-size: 1.2rem;
    }

    @keyframes slide-down {
      0% {
        transform: translateY(-50px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes pulse {
      0% {
        opacity: 1;
        transform: scale(1);
      }
      100% {
        opacity: 0.8;
        transform: scale(1.05);
      }
    }

    @keyframes fade-in-out {
      0% {
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
  </style>
</head>
<body>
  <div class="message-container">
    <h1>Access Denied</h1>
    <p id="countdown">Returning to login page in 2 seconds...</p>
  </div>

  <script>
    let countdownTime = 2; // seconds remaining

    // Function to update the countdown text
    function updateCountdown() {
      const countdownElement = document.getElementById('countdown');
      countdownElement.textContent = `Returning to login page in ${countdownTime} second${countdownTime !== 1 ? 's' : ''}...`;
      
      countdownTime--; // Reduce the time by 1 each second

      if (countdownTime < 0) {
        // Once the countdown is done, redirect to login page
        window.location.href = "/berkemrepaker/login.php"; // Replace with your login page URL
      }
    }

    // Call the updateCountdown function every second
    setInterval(updateCountdown, 1000);

    // Initial call to start the countdown
    updateCountdown();
  </script>
</body>
</html>
