$(document).ready(function () {
  setDateSetter();
  setWordEditor();
  setSentiment();
  setMedia();
  setTag();
});

// Date Setter
function setDateSetter() {
  $("#dateInput").datepicker({
    dateFormat: "MM dd, yy",
  });

  // Set the initial value to the current date
  $("#dateInput").datepicker("setDate", new Date());
}

// Word Editor
function setWordEditor() {
  var buttons = document.querySelectorAll(".toolbar .btn-toolbar button");

  // Add click event listeners to buttons
  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      // If the button is already selected, remove 'selected' from it
      // If it's not, remove 'selected' from all other buttons and add it to this one
      if (button.classList.contains("selected")) {
        button.classList.remove("selected");
      } else {
        buttons.forEach((btn) => {
          btn.classList.remove("selected");
        });
        button.classList.add("selected");
      }
    });
  });
}

const scontent = document.getElementById('content');

scontent.addEventListener('keydown', function (event) {
    
    if (event.key === 'Backspace' && (this.textContent.trim() === '')) {
        event.preventDefault();
    }
});

// Sentiment
function setSentiment() {
  var dropdownItems = document.querySelectorAll(
    ".sentiment-dropdown .dropdown-item"
  );
  var buttonImage = document.querySelector(".sentiment-button img");
  var originalButtonImageSrc = buttonImage.src;
  var lastClickedItem = null;

  // Loop through all dropdown items
  for (var i = 0; i < dropdownItems.length; i++) {
    // Add click event listener to each dropdown item
    dropdownItems[i].addEventListener("click", function (event) {
      // Prevent the default action
      event.preventDefault();

      // Get the image of the clicked dropdown item
      var dropdownItemImage = this.querySelector(".dropdown-item img");

      // If this item was the last clicked item, restore the original button image and remove the highlight
      if (this === lastClickedItem) {
        buttonImage.src = originalButtonImageSrc;
        this.classList.remove("selected");
        lastClickedItem = null;
      } else {
        // Set the src attribute of the button image to the src attribute of the dropdown item image
        buttonImage.src = dropdownItemImage.src;

        // If there was a previously clicked item, remove the highlight
        if (lastClickedItem) {
          lastClickedItem.classList.remove("selected");
        }

        // Highlight the clicked item
        this.classList.toggle("selected");

        // Store this item as the last clicked item
        lastClickedItem = this;
      }
    });
  }
}

// Media
function setMedia() {
  // Get the image upload input
  var imageUpload = document.getElementById("imageUpload");
  // Get the file name element
  var fileNameElement = document.getElementById("fileName");
  // Get the remove button
  var removeButton = document.getElementById("removeButton");

  // Add change event listener to the image upload input
  imageUpload.addEventListener("change", function () {
    // Check if a file was selected
    if (this.files && this.files[0]) {
      // Get the name of the uploaded file
      var fileName = this.files[0].name;

      // Set the text content of the file name element to the name of the uploaded file
      fileNameElement.textContent = fileName;

      // Show the remove button
      removeButton.style.display = "inline";
    }
  });

  // Add click event listener to the remove button
  removeButton.addEventListener("click", function () {
    // Clear the selected file
    imageUpload.value = "";

    // Reset the file name element and the remove button
    fileNameElement.textContent = "Choose file...";
    removeButton.style.display = "none";
  });

  //Display uploaded images to view when saved
  $("#saveButton").click(function (e) {
    e.preventDefault();

    var file = $("#imageUpload")[0].files[0];

    if (!file) {
      $("#errorMessage").text("No image uploaded");
      return;
    }

    if ($("#imageGallery div").length >= 4) {
      $("#errorMessage").text("You can only upload 4 images");
      return;
    }

    var reader = new FileReader();

    reader.onloadend = function () {
      var imgContainer = $("<div>").css({
        width: "23%", // Adjust this value to change the size of the image container
        paddingBottom: "23%", // This should be the same as the width to maintain a square aspect ratio
        position: "relative",
        overflow: "hidden",
        "border-radius": "10px",
        "box-shadow": "2px 2px 5px rgba(0, 0, 0, 0.3)",
        margin: "1%", // Adjust this value to change the spacing between the images
        float: "left", // This makes the images align horizontally
      });

      var img = $("<img>").attr("src", reader.result).css({
        position: "absolute",
        top: "0",
        left: "0",
        width: "100%",
        height: "100%",
        "object-fit": "cover",
        "object-position": "center center",
      });

      var deleteButton = $("<button>").html("&times;").css({
        position: "absolute",
        top: "5px",
        right: "10px",
        background: "red",
        color: "white",
        border: "none",
        "border-radius": "50%",
        cursor: "pointer",
        width: "20px",
        height: "20px",
        "font-size": "14px",
        "text-align": "center",
        "line-height": "1px",
        "padding-right": "5px",
        "font-weight": "600",
        transition: "background 0.2s ease",
      });

      deleteButton.hover(
        function () {
          $(this).css("background", "#ff7f7f");
        },
        function () {
          $(this).css("background", "red");
        }
      );

      deleteButton.click(function () {
        $(this).parent().remove();
      });

      imgContainer.append(img).append(deleteButton);

      $("#imageGallery").append(imgContainer);
    };

    reader.readAsDataURL(file);

    closeUploadForm();
  });
}

// TAG
function setTag() {
  var originalTagButtonImageSrc = $(".tag-button img").attr("src");
  var newTagButtonImageSrc = "../images/text-editor-img/tag-contain.svg"; // Replace with the path to your new image

  $("#addTag").click(function () {
    var tagName = $("#tagName").val().trim();

    if (tagName) {
      var tagContainer = $("<span>").addClass("tag-container");

      var tag = $("<span>").text(tagName).addClass("tag-name");

      var deleteButton = $("<button>").html("&times;").addClass("delete-tag");

      deleteButton.click(function () {
        $(this).parent().remove();

        // Check if there are any tags left after removing a tag
        if ($("#tagContainer .tag-container").length === 0) {
          // If there aren't, change the tag button image back to the original image
          $(".tag-button img").attr("src", originalTagButtonImageSrc);
        }
      });

      tagContainer.append(tag, deleteButton);

      $("#tagContainer").append(tagContainer);

      $("#tagName").val("");

      // Check if there are any tags after adding a new tag
      if ($("#tagContainer .tag-container").length > 0) {
        // If there are, change the tag button image
        $(".tag-button img").attr("src", newTagButtonImageSrc);
      }
    }
  });
}

// Save to Database
function saveToDatabase() {
  // Get the content and date
  var content = $("#content").html();
  var dateInput = $("#dateInput").val();

  // Get the image data
  var images = Array.from(document.querySelectorAll("#imageGallery img")).map(
    (img) => img.src
  );

  // Get the tag names
  var tags = Array.from(
    document.querySelectorAll("#tagContainer .tag-name")
  ).map((tag) => tag.textContent);

  // Get the selected sentiment
  var sentiment = Array.from(
    document.querySelectorAll(".sentiment-dropdown .dropdown-item")
  ).findIndex((item) => item.classList.contains("selected")) + 1;

  // Create an object with the data
  var data = {
    content: content,
    dateInput: dateInput,
    userId: userId,
  };

  // Conditionally add images, tags, and sentiment to data
  if (images.length > 0) {
    data.images = images;
  }
  if (tags.length > 0) {
    data.tags = tags;
  }
  if (sentiment > 0) {
    data.sentiment = sentiment;
  }

  // Send a POST request with the data
  fetch("../crud/create.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
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

function formatDoc(cmd, value = null) {
  if (value) {
    document.execCommand(cmd, false, value);
  } else {
    document.execCommand(cmd);
  }
}

function openUploadForm() {
  document.body.classList.add("showUploadForm");
}

function closeUploadForm() {
  document.body.classList.remove("showUploadForm");
  $("#imageUpload").val("");
  $("#fileName").text("Choose image...");
  $("#removeButton").css("display", "none");
  $("#errorMessage").text("");
}

function changeForm(action) {
  var avatarImage = $("#avatarImage");
  var formHeader = $("#formHeader");
  var uploadElement = $(".form .upload");
  var viewElement = $(".form .view");
  var tagElement = $(".form .tag");

  if (action === "upload") {
    avatarImage.attr("src", "../images/text-editor-img/media-dark.svg");
    formHeader.text("Upload Image");
    uploadElement.show();
    viewElement.hide();
    tagElement.hide();
  } else if (action === "view") {
    avatarImage.attr("src", "../images/text-editor-img/view.svg");
    formHeader.text("View Image");
    uploadElement.hide();
    viewElement.show();
    tagElement.hide();
  } else if (action === "tag") {
    avatarImage.attr("src", "../images/text-editor-img/tag-2.svg");
    formHeader.text("Tags");
    uploadElement.hide();
    viewElement.hide();
    tagElement.show();
  }
}

const showCode = document.getElementById("show-code");
const content = document.getElementById("content"); // Add this line
let active = false;

showCode.addEventListener("click", function () {
  console.log("clicked");
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
