<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$event_id = $_POST['event_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$event_id || $amount === null) {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Payment</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('websitebg2.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      text-align: center;
      padding: 60px 20px;
    }

    .payment-box {
      background-color: rgba(255,255,255,0.95);
      color: #333;
      padding: 30px;
      max-width: 500px;
      margin: auto;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    h2 {
      margin-top: 0;
      color: #fe4500;
    }

    p {
      font-size: 18px;
      margin: 20px 0;
    }

    .btn {
      padding: 12px 25px;
      font-weight: 500;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin: 10px;
      transition: background 0.3s ease;
    }

    .confirm-btn {
      background-color: #fe4500;
      color: white;
    }

    .confirm-btn:hover {
      background-color: #d63b00;
    }

    .cancel-btn {
      background-color: #777;
      color: white;
    }

    .cancel-btn:hover {
      background-color: #555;
    }
  </style>
</head>
<body>
  <div class="payment-box">
    <h2>Confirm Payment</h2>
    <p>You are signing up for an event that requires a fee of:</p>
    <p><strong>RM <?php echo number_format((float)$amount, 2); ?></strong></p>

    <form method="POST" action="process_payment.php">
      <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">
      <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
      <button type="submit" class="btn confirm-btn">Proceed to Payment</button>
    </form>

    <a href="user_event.php"><button class="btn cancel-btn">Cancel</button></a>
  </div>
</body>
</html>