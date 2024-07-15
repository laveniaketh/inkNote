<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inkNote | Home</title>
  <link rel="icon" href="images/logo-light.png" type="image/x-icon" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <!-- Line Icons -->
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <!-- CSS Style -->
  <link rel="stylesheet" href="home.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Ubuntu&display=swap" rel="stylesheet">
  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

</head>

<?php

include('database.php');
session_start();
function updateProfile()
{
  global $conn;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];


    // Check if username was sent
    if (isset($_POST['username'])) {
      $username = $_POST['username'];

      // Prepare the SQL statement
      $stmt = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
      $stmt->bind_param("si", $username, $user_id);

      // Execute the statement
      if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        return;
      }

      // Close the statement
      $stmt->close();
    }

    // Check if profile_pic was sent
    if (isset($_FILES['profile_pic'])) {
      $profile_pic = $_FILES['profile_pic'];

      // Validate the file here...

      // Specify the directory where the file will be moved
      $target_dir = "images/uploads/";
      $target_file = $target_dir . basename($profile_pic["name"]);

      // Move the uploaded file to the target directory
      if (move_uploaded_file($profile_pic["tmp_name"], $target_file)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE user_id = ?");
        $stmt->bind_param("si", $target_file, $user_id);

        // Execute the statement
        if (!$stmt->execute()) {
          echo "Error: " . $stmt->error;
          return;
        }

        // Close the statement
        $stmt->close();

        // Check if the request is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
          // Set the Content-Type header to "application/json"
          header('Content-Type: application/json');

          // Return the new image URL in a JSON response
          echo json_encode(['newImageUrl' => $target_file]);
          exit;
        }
      }
    }

    // Fetch the updated data
    $stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
      $username = $row['username'];
      $profile_pic = $row['profile_pic'];
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }
}

updateProfile();
?>

<body>
  <div class="wrapper">
    <div class="sidebar-overlay" onclick="closeSideBar()"></div>
    <aside id="sidebar">
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="text-editor/text-editor.php" class="sidebar-link new-entry">
            <img src="images/home-img/light/new-entry.svg" width="16" height="16">
            <span>New Entry</span>
          </a>
        </li>
        <li class="sidebar-item" onclick="checkEntryAndRedirect()">
          <a href="#" class="sidebar-link selected" id="tl-sidebar">
            <img src="images/home-img/light/timeline.svg" width="16" height="16">
            <span>Timeline</span>
          </a>
        </li>
        <li class="sidebar-item" onclick="checkEntryAndRedirect()">
          <a href="#" class="sidebar-link" id="cl-sidebar">
            <img src="images/home-img/light/calendar.svg" width="16" height="16">
            <span>Calendar</span>
          </a>
        </li>
        <li class="sidebar-item" onclick="checkEntryAndRedirect()">
          <a href="#" class="sidebar-link" id="md-sidebar">
            <img src="images/home-img/light/media.svg" width="16" height="16">
            <span>Media</span>
          </a>
        </li>
        <li class="sidebar-item" onclick="checkEntryAndRedirect()">
          <a href="#" class="sidebar-link">
            <img src="images/home-img/light/search.svg" width="16" height="16">
            <span>Search</span>
          </a>
        </li>

      </ul>
    </aside>
    <?php
    include('database.php');
    global $conn;
    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    if ($row = $result->fetch_assoc()) {
      $username = $row['username'];
      $profile_pic = $row['profile_pic'];
    } else {
      echo "Error: " . $stmt->error;
    }
    ?>

    <?php
    if (isset($_GET['entry'])) {
      $entry_id = $_GET['entry_id'];
      $_SESSION['entry_id'] =  $_GET['entry_id'];
      $entry_id = $_SESSION['entry_id'];

      

      // Prepare the SQL statement
      $stmt = $conn->prepare("SELECT sentiment_id, entry_date, content FROM entries WHERE entry_id = ?");
      $stmt->bind_param("i", $entry_id);

      // Execute the statement
      $stmt->execute();

      // Get the result
      $result = $stmt->get_result();

      // Fetch the data
      while ($row = $result->fetch_assoc()) {
        $date = new DateTime($row['entry_date']);
        $formattedDate = $date->format('F d, Y');
        $content = base64_decode($row['content']);

        // Remove HTML tags
        $plainTextContent = strip_tags($content);

        // URL encode the parameters
        $encodedDate = urlencode($formattedDate);
        $encodedContent = urlencode($content);

        // Decode the URL parameters
        $decodedDate = urldecode($encodedDate);
        $decodedContent = urldecode($encodedContent);

        $_SESSION['plainTextContent'] = strip_tags($content);
        $_SESSION['encodedDate'] = urlencode($formattedDate);
        $_SESSION['encodedContent'] = urlencode($content);
        $_SESSION['entry_id'] =  $entry_id;

        // Check if entry_id exists in tags table
        $stmt = $conn->prepare("SELECT * FROM tags WHERE entry_id = ?");
        $stmt->bind_param("i", $entry_id);
        $stmt->execute();
        $tagExists = $stmt->get_result()->num_rows > 0;

        // Check if entry_id exists in medias table
        $stmt = $conn->prepare("SELECT * FROM medias WHERE entry_id = ?");
        $stmt->bind_param("i", $entry_id);
        $stmt->execute();
        $mediaExists = $stmt->get_result()->num_rows > 0;
      }
    }

    ?>




    <d class="main" style="<?php echo isset($_GET['entry']) ? 'background-color: #252525 ;' : ''; ?>">
      <nav class="navbar" style="<?php echo isset($_GET['entry']) ? 'background-color:  #252525; border-bottom: 2px solid #eee;' : ''; ?>">

        <?php if (isset($_GET['entry'])) : ?>
          <div class="container-fluid d-flex justify-content-flex-end">
            <a href="home.php" class="back-button"><img src="images/text-editor-img/back.svg"></a>
            <h4 class="date"><?php echo $formattedDate; ?></h4>
            <div>
              <a href="#" class="edit-button"><img src="images/entry-view-img/edit.svg"></a>
              <!-- <a href="#" class="delete-button"><img src="images/entry-view-img/delete.svg"></a> -->
              <a href="#" class="delete-button" onclick="deleteFromDatabase(<?php echo $entry_id; ?>)"><img src="images/entry-view-img/delete.svg"></a>
            </div>

          </div>
        <?php else : ?>
          <div class="container-fluid d-flex justify-content-center">
            <div class="hamburger-menu" onclick="toggleSidebar()">
              <button data-mdb-toggle="sidenav" data-mdb-target="#sidenav-1" data-mdb-button-init data-mdb-ripple-init class="btn btn-light btn-md" aria-controls="#sidenav-1" aria-haspopup="true">
                <img src="images/home-img/sidebar.svg" alt="sidebar" />
              </button>
            </div>

            <a class="navbar-brand mx-auto" href="?page=text-editor-page">
              <img src="images/logo-dark.png" class="logo-img" alt="logo" width="24" height="24" class="d-inline-block align-text-top">
              inkNote
            </a>
            <div class="user-menu">
              <img src="<?php echo $profile_pic; ?>" alt="user-profile" class="user-pic menu" onclick="openSubMenu()">
            </div>
            <div class="sub-menu-wrap" id="subMenu">
              <div class="sub-menu">
                <div class="user-info">
                  <img src="<?php echo $profile_pic; ?>" alt="user-profile" class="user-pic profile">
                  <h5><?php echo $username; ?></h5>
                </div>
                <hr>
                <a class="sub-menu-link" onclick="openLoginForm()">
                  <img src="images/home-img/edit-profile.svg" alt="edit profile">
                  <p>Edit Profile</p>
                </a>
                <a href="index.php" class="sub-menu-link">
                  <img src="images/home-img/logout.svg" alt="logout">
                  <p>Signout</p>
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>


      </nav>
      <!-- <div class="popup-overlay" onclick="closeLoginForm()"></div>
      <div class="popup">
        <div class="popup-close" onclick="closeLoginForm()">&times;</div>
        <div class="form">
          <div class="avatar">
            <div class="avatar-wrapper">
              <img src="images/home-img/account-hover.png" alt="profile">
            </div>

          </div>
          <div class="header">
            Edit Profile
          </div>
          <div class="element">
            <div class="uname-change">
                <img src="images/login-reg-img/user.svg" class="icons" alt="user" />
                <input type="text" name="username" placeholder="username" />
              </div>
            </div>
            <div class="element">
              <button type="submit" name="save" class="btn btn-dark btn-lg rounded-5">Save Changes</button>
            </div>
          </div>
        </div>
      </div> -->



      <div class="popup-overlay" onclick="closeLoginForm()"></div>
      <div class="popup">
        <div class="popup-close" onclick="closeLoginForm()">&times;</div>
        <form method="post" enctype="multipart/form-data">
          <div class="form">
            <div class="avatar">
              <div class="avatar-wrapper" onclick="document.getElementById('profile_pic').click();">
                <div class="image-container">
                  <img src="<?php echo $profile_pic; ?>" alt="profile" id="output">
                </div>
              </div>
              <input type="file" name="profile_pic" id="profile_pic" style="display: none;" onchange="loadFile(event)">
            </div>
            <div class="header">
              Edit Profile
            </div>
            <div class="element">
              <button type="submit" name="save" class="btn btn-dark btn-lg rounded-5" onclick="">Save Changes</button>
            </div>
          </div>
        </form>
      </div>

      <div class="attachment-overlay" onclick="closeViewAttachments()"></div>
      <div class="viewAttachments">
        <div class="attachment-close" onclick="closeViewAttachments()">&times;</div>

        <div class="avatar">
          <div class="avatar-wrapper">
            <img id="avatarImage" src="" height="30px">
          </div>
        </div>
        <div id="formHeader" class="header">
          View
        </div>
        <div class="element view-images">
          <?php
          // Check if entry_id exists in medias table
          $stmt = $conn->prepare("SELECT media_content FROM medias WHERE entry_id = ?");
          $stmt->bind_param("i", $entry_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $mediaExists = $result->num_rows > 0;

          if ($mediaExists) {
            while ($row = $result->fetch_assoc()) {
              $media_content = $row['media_content'];
              echo '<div class="image-frame"><img src="' . $media_content . '"></div>';
            }
          }
          ?>
          <!-- <div class="image-frame">
            <img src="images/uploads/0e3211b55d7ccc97ce5d83a89cfe2085.jpg">
          </div> -->
        </div>
        <div class="element view-tags">
          <!-- <span class="tag-container"> <span class="tag-name">try</span> </span> -->
          <span class="tag-container">
            <?php
            if ($tagExists) {
              $stmt = $conn->prepare("SELECT * FROM tags WHERE entry_id = ?");
              $stmt->bind_param("i", $entry_id);
              $stmt->execute();
              $result = $stmt->get_result();
              while ($row = $result->fetch_assoc()) {
                echo '<span class="tag-name">' . $row['tag'] . '</span>';
              }
            }
            ?>
          </span>

        </div>


      </div>




      <main>

        <div class="sidebar-titles">
          <?php
          if (isset($_GET['entry'])) {
          ?>
            <section id="entry-view">
              <div id="content">
                <?php echo $decodedContent; ?>
              </div>
              <footer>
                <div class="sticker-tab container-fluid d-flex justify-content-flex-start">
                  <?php
                  if ($tagExists) {
                    echo '<img src="images/text-editor-img/tag.svg" alt="tag" style="height: 25px;" onclick="viewAttachments(); changeForm(\'tag\');">';
                  }
                  if ($mediaExists) {
                    echo '<img src="images/text-editor-img/media.svg" alt="media" style="height: 25px; " onclick="viewAttachments(); changeForm(\'media\');">';
                  }
                  ?>


                </div>
              </footer>
            </section>
          <?php
          } else {
          ?>
            <section id="timeline">
              <?php include 'submenu-contents/timeline.php'; ?>
            </section>

            <section id="calender">
              <h1>CALENDAR</h1>
              <h5>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur rem rerum, itaque deserunt ratione, dicta ducimus debitis hic excepturi maxime eaque similique explicabo, officia sapiente corrupti omnis. Illo, minima nulla.</h5>
            </section>

            <section id="media">
              <h1>MEDIA</h1>
              <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque temporibus sed blanditiis ipsam, reprehenderit at cum! Voluptatum, atque nam et nisi magnam consectetur quibusdam nobis illo, labore tempore suscipit! Laborum.</h5>
            </section>

            <section id="search">
              <h1>SEARCH</h1>
              <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fuga beatae dolores, eum voluptate exercitationem deserunt reprehenderit non, sequi accusantium qui tempora odit quibusdam earum et cum quis voluptas nemo cumque?</h5>
            </section>
          <?php
          }
          ?>


        </div>




      </main>





  </div>


  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="home.js"></script>
</body>

</html>