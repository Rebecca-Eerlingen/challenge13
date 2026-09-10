<?php
$uploadMessage = "";

if (isset($_POST['submit'])) {
    // text data
    $caption = htmlspecialchars($_POST['caption']);

    // foto data
    $file = $_FILES['image'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // de extensie van de file ophalen en kijken of hij is toegestaan
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            // limiteer de file grootte tot 5MB
            if ($fileSize < 5000000) {
                // maak een unieke naam voor de file om fouten te voorkomen
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;

                // creeër de uploads map als deze nog niet bestaat
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                // verplaats de file naar de uploads map
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $uploadMessage = "<div style='margin-top: 20px; padding: 10px; background: rgba(0,255,0,0.1); border-radius: 5px;'>
                                        Upload success!<br>
                                        Caption: " . $caption . "<br>
                                        <img src='$fileDestination' width='200' style='margin-top: 10px; border-radius: 4px;'>
                                      </div>";
                } else {
                    $uploadMessage = "<p style='color: red;'>Failed to move the uploaded file.</p>";
                }
            } else {
                $uploadMessage = "<p style='color: red;'>Your file is too big!</p>";
            }
        } else {
            $uploadMessage = "<p style='color: red;'>There was an error uploading your file!</p>";
        }
    } else {
        $uploadMessage = "<p style='color: red;'>You cannot upload files of this type!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lorenzo von Matterhorn - Portofolio</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #FEFCE8;
      color: #1F2937;
      display: flex;
      flex-direction: column;
      min-height: 945px;
      transition: background-color 0.5s, color 0.5s;
    }

    body.dark-mode {
      background-color: #0F172A;
      color: #F8FAFC;
    }

    header {
      position: relative;
      background: linear-gradient(to top, #FFB347, #FF7E5F);
      color: white;
      padding: 90px 15px 150px 15px;
      text-align: center;
      overflow: hidden;
      transition: background-color 0.5s;
    }

    body.dark-mode header {
      background: linear-gradient(to top, #1E3A8A, #0F172A);
    }

    .mountains {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 1920px;
      height: 150px;
      background: linear-gradient(to top, #1E8A3A 0%, transparent 100%);
      clip-path: polygon(
        0% 100%, 10% 60%, 20% 85%, 30% 50%, 40% 80%,
        50% 40%, 60% 75%, 70% 45%, 80% 70%, 90% 50%, 100% 100%
      );
    }

    .dark-mode .mountains {
      background: linear-gradient(to top, #1E0A4A 0%, transparent 100%);
    }

    header h1,
    header p,
    header nav {
      position: relative;
      z-index: 1;
    }

    header h1 a {
      color: white;
      text-decoration: none;
    }

    header h1 a:hover {
        text-decoration: underline;
    }

    nav ul {
      list-style: none;
      display: flex;
      justify-content: center;
      padding: 0;
      margin-top: 10px;
    }

    nav li {
      margin: 0 15px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .dark-toggle {
      position: fixed;
      top: 15px;
      right: 15px;
      color: #1E3A8A;
      border: none;
      cursor: pointer;
      z-index: 999;
      background: transparent;
      font-size: 30px;
    }

    .dark-toggle::before {
        content: "🌕";
    }

    .dark-mode .dark-toggle {
      color: #fff;
    }
    
    .dark-mode .dark-toggle::before {
        content: "";
    }

    main {
      flex: 1;
      max-width: 960px;
      margin: 30px auto;
      padding: 23px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      width: 1000px;
      transition: background-color 0.5s;
    }

    body.dark-mode main {
      background-color: #1E293B;
    }

    footer {
      background-color: #1E3A8A;
      color: white;
      text-align: center;
      padding: 15px;
    }
  </style>
  <script>
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
    }
  </script>
</head>
<body>
  <button class="dark-toggle" onclick="toggleDarkMode()"></button>
  <header>
    <h1><a href=".">Lorenzo von Matterhorn</a></h1>
    <p>Student Software Developer op het Vista College</p>
    <nav>
      <ul>
        <li><a href="about.php">Over Mij</a></li>
        <li><a href="projects.php">Projecten</a></li>
        <li><a href="kerntaken.php">Kerntaken</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
    <div class="mountains"></div>
  </header>

  <main>
    <h1>Welkom op mijn portofolio!</h1>
    
    <p>Hier kunt u uw projecten uploaden en bekijken.</p>
    <form action="" method="POST" enctype="multipart/form-data">
      <label for="caption">Korte desciptie over jezelf:</label><br>
      <input type="text" id="caption" name="caption" required><br><br>

      <label for="image">Kies uw foto:</label><br>
      <input type="file" id="image" name="image" accept="image/*" required><br><br>

      <input type="submit" name="submit" value="Upload">
    </form>

    <?php echo $uploadMessage; ?>
  </main>

  <footer>
    © 2025 Swarley
  </footer>

</body>
</html>
