// Sidebar
var sidebar = document.querySelector("#sidebar");

sidebar.addEventListener("mouseover", function () {
  if (window.innerWidth > 950) {
    sidebar.classList.add("expand");
  }
});

sidebar.addEventListener("mouseout", function () {
  if (window.innerWidth > 950) {
    sidebar.classList.remove("expand");
  }
});

function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("expand");
  document.body.classList.add("showSideBar");

  var subMenuWrap = $(".sub-menu-wrap");
  var userMenu = $(".user-menu");
  if (subMenuWrap.hasClass("show")) {
    subMenuWrap.toggleClass("show");
    userMenu.toggleClass("active");
  }
}

function closeSideBar() {
  document.getElementById("sidebar").classList.remove("expand");
  document.body.classList.remove("showSideBar");
}

$("a.sidebar-link").click(function () {
  $("a.sidebar-link").removeClass("selected");
  $(this).addClass("selected");
});

// Get all sidebar links and sections
var sidebarLinks = document.querySelectorAll(".sidebar-link:not(.new-entry)");
var sections = document.querySelectorAll("section");

// Function to hide all sections
function hideAllSections() {
  sections.forEach((section) => {
    section.style.display = "none";
  });
}

// Function to remove the 'selected' class from all links
function deselectAllLinks() {
  sidebarLinks.forEach((link) => {
    link.classList.remove("selected");
  });
}

// Add click event listeners to sidebar links
sidebarLinks.forEach((link, index) => {
  link.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent the default action
    // Hide all sections and deselect all links
    hideAllSections();
    deselectAllLinks();

    // Show the associated section and select the clicked link
    sections[index].style.display = "block";
    link.classList.add("selected");
  });
});

// Initially hide all sections except the one associated with the selected link
hideAllSections();
const selectedLinkIndex = Array.from(sidebarLinks).findIndex((link) =>
  link.classList.contains("selected")
);
if (selectedLinkIndex !== -1) {
  sections[selectedLinkIndex].style.display = "block";
}

function checkEntryAndRedirect() {
  var urlParams = new URLSearchParams(window.location.search);
  var entry = urlParams.get("entry");
  if (entry === "true") {
    window.location.href = "home.php";
  }
}

function formatDoc(cmd, value = null) {
  if (value) {
    document.execCommand(cmd, false, value);
  } else {
    document.execCommand(cmd);
  }
}

function addLink() {
  const url = prompt("Insert url");
  formatDoc("createLink", url);
}

const content = document.getElementById("content");

content.addEventListener("mouseenter", function () {
  const a = content.querySelectorAll("a");
  a.forEach((item) => {
    item.addEventListener("mouseenter", function () {
      content.setAttribute("contenteditable", false);
      item.target = "_blank";
    });
    item.addEventListener("mouseleave", function () {
      content.setAttribute("contenteditable", true);
    });
  });
});

const showCode = document.getElementById("show-code");
let active = false;

showCode.addEventListener("click", function () {
  showCode.dataset.active = !active;
  active = !active;
  if (active) {
    content.textContent = content.innerHTML;
    content.setAttribute("contenteditable", false);
  } else {
    content.innerHTML = content.textContent;
    content.setAttribute("contenteditable", true);
  }
});

const filename = document.getElementById("filename");

function fileHandle(value) {
  if (value === "new") {
    content.innerHTML = "";
    filename.value = "untitled";
  } else if (value === "txt") {
    const blob = new Blob([content.innerText]);
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = `${filename.value}.txt`;
    link.click();
  } else if (value === "pdf") {
    html2pdf(content).save(filename.value);
  }
}

function toggleDropdown() {
  document.getElementById("dropdown-content").style.display =
    document.getElementById("dropdown-content").style.display === "none"
      ? "block"
      : "none";
}

// Profile Menu
function openSubMenu() {
  var subMenuWrap = $(".sub-menu-wrap");
  var userMenu = $(".user-menu");

  subMenuWrap.toggleClass("show");
  userMenu.toggleClass("active");
  // document.body.classList.add("showSubMenuForm");
}
// $(document).click(function(event) {
// 	if (!$(event.target).closest('.user-pic').length) {
// 	  closeSubMenu();
// 	}
//   });
// function closeSubMenu(){
// 	console.log("close sub")
// 	var subMenuWrap = $('.sub-menu-wrap');
//     if (subMenuWrap.hasClass('show')) {
// 		subMenuWrap.remove('show');
// 	}
// 	else{
// 		subMenuWrap.toggleClass('show');
// 	}
// }

// POPUP
function openLoginForm() {
  document.body.classList.add("showLoginForm");
}
function closeLoginForm() {
  document.body.classList.remove("showLoginForm");
}

function viewAttachments() {
  document.body.classList.add("showViewAttachments");
}

function closeViewAttachments() {
  document.body.classList.remove("showViewAttachments");
}

function changeForm(action) {
  var avatarImage = $("#avatarImage");
  var formHeader = $("#formHeader");
  var viewMedia = $(".viewAttachments .view-images");
  var viewTag = $(".viewAttachments .view-tags");

  if (action === "media") {
    avatarImage.attr("src", "images/text-editor-img/view.svg");
    formHeader.text("View Images");
    viewMedia.show();
    viewTag.hide();
  } else if (action === "tag") {
    avatarImage.attr("src", "images/text-editor-img/tag-2.svg");
    formHeader.text("View Tags");
    viewMedia.hide();
    viewTag.show();
  }
}

//save updated profile pic to database
function loadFile(event) {
  var output = document.getElementById("output");
  output.src = URL.createObjectURL(event.target.files[0]);
  document.getElementById("profile_pic").value = output.src;
  output.onload = function () {
    URL.revokeObjectURL(output.src); // free memory
  };
}

// update the profile pic when change
$("form").on("submit", function (e) {
  e.preventDefault();

  var formData = new FormData(this);

  $.ajax({
    url: window.location.href, // Current page URL
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      // Update the image on the page with the new URL
      $(".user-pic").attr("src", data.newImageUrl);
      $("#output").attr("src", data.newImageUrl);
    },
  });
});

// Database
function deleteFromDatabase(entryId) {
    // Create an object with the entryId
    console.log(entryId);
    var data = {
      entryId: entryId,
    };
  
    // Send a POST request with the entryId
    fetch("crud/delete.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          window.location.href = data.redirect; // Redirect to the URL returned by the server
        } else {
          console.error("Error:", data.error);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

// TIMELINE

//ENTRY VIEW
