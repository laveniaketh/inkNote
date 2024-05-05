<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inkNote | Home</title>
  <link rel="icon" href="images/logo-light.png" type="image/x-icon" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- Line Icons -->
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <!-- CSS Style -->
  <link rel="stylesheet" href="home.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Ubuntu&display=swap" rel="stylesheet">
  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
  <div class="wrapper">
  <div class="sidebar-overlay" onclick="closeSideBar()"></div>
    <aside id="sidebar">
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="" class="sidebar-link" id="tl-sidebar">
            <img src="images/home-img/light/timeline.svg" width="16" height="16">
            <span>Timeline</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link">
            <img src="images/home-img/light/new-entry.svg" width="16" height="16">
            <span>New Entry</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link" id="cl-sidebar">
            <img src="images/home-img/light/calendar.svg" width="16" height="16">
            <span>Calendar</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link" id="md-sidebar">
            <img src="images/home-img/light/media.svg" width="16" height="16">
            <span>Media</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link">
            <img src="images/home-img/light/search.svg" width="16" height="16">
            <span>Search</span>
          </a>
        </li>

      </ul>
    </aside>


    <div class="main">
      <nav class="navbar bg-body-tertiary">
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
            <img src="images/home-img/account-hover.png" alt="user-profile" class="user-pic" onclick="openSubMenu()">
          </div>
            <div class="sub-menu-wrap" id="subMenu">
              <div class="sub-menu">
                <div class="user-info">
                  <img src="images/home-img/light/user.svg" alt="user-profile">
                  <h5>laveniaketh</h5>
                </div>
                <hr>
                <a href="#" class="sub-menu-link" onclick="openLoginForm()">
                  <img src="images/home-img/edit-profile.svg" alt="edit-profile">
                  <p>Edit Profile</p>
                </a>
                <a href="#" class="sub-menu-link">
                  <img src="images/home-img/logout.svg" alt="logout">
                  <p>Signout</p>
                </a>
              </div>
            </div>

          </div>
      </nav>
      <main>
        <div class="popup-overlay" onclick="closeLoginForm()" ></div>
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
              <!-- <div class="uname-change">
                <img src="images/login-reg-img/user.svg" class="icons" alt="user" />
                <input type="text" name="username" placeholder="username" />
              </div>
            </div> -->
              <div class="element">
                <button type="submit" name="save" class="btn btn-dark btn-lg rounded-5">Save Changes</button>
              </div>
            </div>
          </div>
      </main>





    </div>


  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="home.js"></script>
</body>

</html>