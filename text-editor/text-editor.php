<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inkNote | Entry </title>
  <link rel="icon" href="../images/logo-light.png" type="image/x-icon" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Ubuntu&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

  <!-- Boxicons -->
  <link href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css' rel='stylesheet'>


  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <!-- CSS Style -->
  <link rel="stylesheet" href="text-editor.css">
</head>

<body>
  <?php
  session_start();
  ?>
  <div class="container-fluid">
    <h4 class="date"><input type="text" id="dateInput"></h4>
    <div class="toolbar">
      <div class="head">
        <a href="../home.php" class="back-button"><img src="../images/text-editor-img/back.svg"></a>
        <div class="btn-toolbar">
          <button class="tb-button" onclick="formatDoc('undo')"><i class='bx bx-undo' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('redo')"><i class='bx bx-redo' style='color:#eeeeee'></i></button>
          <button class="tb-button" onclick="formatDoc('bold')"><i class='bx bx-bold' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('underline')"><i class='bx bx-underline' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('italic')"><i class='bx bx-italic' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('strikeThrough')"><i class='bx bx-strikethrough' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('justifyLeft')"><i class='bx bx-align-left' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('justifyCenter')"><i class='bx bx-align-middle' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('justifyRight')"><i class='bx bx-align-right' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('justifyFull')"><i class='bx bx-align-justify' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('insertOrderedList')"><i class='bx bx-list-ol' style='color:#eeeeee'></i></button>
          <button onclick="formatDoc('insertUnorderedList')"><i class='bx bx-list-ul' style='color:#eeeeee'></i></button>
          <button id="show-code" data-active="false">code</button>
        </div>
        <a href="#" class="save-button" id="store-html" onclick="saveToDatabase()"><img src="../images/text-editor-img/save.svg"></a>
      </div>
    </div>
    <div id="content" contenteditable="true" spellcheck="false">
      <p>start writing here</p>
    </div>
  </div>

  <div class="popup-overlay" onclick="closeUploadForm()"></div>
  <div class="popup">
    <div class="popup-close" onclick="closeUploadForm()">&times;</div>
    <div class="form">
      <div class="avatar">
        <div class="avatar-wrapper">
          <img id="avatarImage" src="../images/text-editor-img/media-dark.svg" height="30px">
        </div>
      </div>
      <div id="formHeader" class="header">
        Upload Image
      </div>
      <div class="element upload imageUpload">
        <label for="imageUpload" class="custom-file-upload">
          <input type="file" id="imageUpload" accept="image/*" style="display: none;">
          <span id="fileName">Choose image...</span>
        </label>
        <button id="removeButton" class="btn btn-danger rounded-2">&times;</button>
      </div>
      <div class="element upload imageSave">
        <p id="errorMessage">Choose a maximum of 4 images</p>
        <button type="submit" name="save" id="saveButton" class="btn btn-dark btn-lg rounded-5" onclick="setMedia();">Save</button>
      </div>
      <div class="element view" id="imageGallery">

      </div>

      <div class="element tag">
        <div class="addTag">
          <input type="text" id="tagName" placeholder="Enter tag name">
          <button id="addTag" class="btn btn-dark" onclick="setTag();">+</button>
        </div>
        <div class="scroll-tag">
        <div id="tagContainer">
        </div>
        </div>
        

      </div>
    </div>
  </div>


  <footer>
    <div class="sticker-tab">
      <div class="dropup">
        <a class="btn btn-secondary btn-sm dropdown-toggle media-button" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../images/text-editor-img/media.svg" height="25px">
        </a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" onclick="openUploadForm(); changeForm('upload');">
              <img src="../images/text-editor-img/upload.svg">
              Upload Image
            </a>
          </li>
          <li><a class="dropdown-item" href="#" onclick="openUploadForm(); changeForm('view');">
              <img src="../images/text-editor-img/view.svg">
              View Image
            </a>
          </li>
        </ul>

        <a class="btn btn-secondary btn-sm dropdown-toggle sentiment-button" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../images/text-editor-img/sentiment.svg" height="25px">
        </a>

        <ul class="dropdown-menu sentiment-dropdown">
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments-svg/1- joyful.svg"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/1.svg">
              joyful
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/2-enthusiastic.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/2.svg">
              enthusiastic
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/3-butterflies.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/3.svg">
              butterflies
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/4-amazed.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/4.svg">
              amazed
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/5-happy.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/5.svg">
              happy
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/6-hopeful.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/6.svg">
              hopeful
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/7-contented.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/7.svg">
              contented
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/8-confused.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/8.svg">
              confused
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/9-worried.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/9.svg">
              worried
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/10-sad.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/10.svg">
              sad
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <!-- <img src="../images/text-editor-img/sentiments/11-devastated.png"> -->
              <img src="../images/text-editor-img/sentiments-svg/sentiments-colors/11.svg">
              devastated
            </a>
          </li>
        </ul>

        <a class="btn btn-secondary btn-sm dropdown-toggle tag-button" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../images/text-editor-img/tag.svg" height="25px">
        </a>

        <ul class="dropdown-menu">
          <li>
            <a class="dropdown-item" href="#" onclick="openUploadForm(); changeForm('tag');">
              <img src="../images/text-editor-img/tag-2.svg">
              Add New Tag
            </a>
          </li>
        </ul>
      </div>

    </div>
  </footer>


  <script>
    var userId = "<?php echo $_SESSION['user_id']; ?>";
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="text-editor.js"></script>
</body>

</html>