
// Sidebar
var sidebar = document.querySelector('#sidebar');

sidebar.addEventListener('mouseover', function() {
    if (window.innerWidth > 950) {
        sidebar.classList.add('expand');
    }
});

sidebar.addEventListener('mouseout', function() {
    if (window.innerWidth > 950) {
        sidebar.classList.remove('expand');
    }
});


function toggleSidebar() {
	document.getElementById('sidebar').classList.toggle('expand');
	document.body.classList.add("showSideBar");

	var subMenuWrap = $('.sub-menu-wrap');
    if (subMenuWrap.hasClass('show')) {
		subMenuWrap.toggleClass('show');
	}
}

function closeSideBar(){
	document.getElementById('sidebar').classList.remove('expand');
	document.body.classList.remove("showSideBar");
}

$(function() {
    $('.sidebar-link').click(function(e) {
        e.preventDefault();
		
		// $('#timeline').show();
		$('#media').show();
		$('#calendar').hide();
		$('.new-button').show();
		$('.navbar').show()
		$('#text-editor').hide();

    });
});


// text-editor

// $(function() {
//     $('.new-button').click(function() {
// 		e.preventDefault();
//         $('.text-editor').show();
//         $('#timeline').hide();
// 		$('#media').hide();
// 		$('#calendar').hide();
//         $('.navbar').hide();
//         $('.new-button').hide();

//     });
// });

function formatDoc(cmd, value=null) {
	if(value) {
		document.execCommand(cmd, false, value);
	} else {
		document.execCommand(cmd);
	}
}

function addLink() {
	const url = prompt('Insert url');
	formatDoc('createLink', url);
}




const content = document.getElementById('content');

content.addEventListener('mouseenter', function () {
	const a = content.querySelectorAll('a');
	a.forEach(item=> {
		item.addEventListener('mouseenter', function () {
			content.setAttribute('contenteditable', false);
			item.target = '_blank';
		})
		item.addEventListener('mouseleave', function () {
			content.setAttribute('contenteditable', true);
		})
	})
})


const showCode = document.getElementById('show-code');
let active = false;

showCode.addEventListener('click', function () {
	showCode.dataset.active = !active;
	active = !active
	if(active) {
		content.textContent = content.innerHTML;
		content.setAttribute('contenteditable', false);
	} else {
		content.innerHTML = content.textContent;
		content.setAttribute('contenteditable', true);
	}
})



const filename = document.getElementById('filename');

function fileHandle(value) {
	if(value === 'new') {
		content.innerHTML = '';
		filename.value = 'untitled';
	} else if(value === 'txt') {
		const blob = new Blob([content.innerText])
		const url = URL.createObjectURL(blob)
		const link = document.createElement('a');
		link.href = url;
		link.download = `${filename.value}.txt`;
		link.click();
	} else if(value === 'pdf') {
		html2pdf(content).save(filename.value);
	}
}

function toggleDropdown() {
	document.getElementById("dropdown-content").style.display = 
	  document.getElementById("dropdown-content").style.display === "none" ? "block" : "none";
  }


// Profile Menu
function openSubMenu() {
    var subMenuWrap = $('.sub-menu-wrap');
    var userMenu = $('.user-menu');

    subMenuWrap.toggleClass('show');
    userMenu.toggleClass('active');
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
function openLoginForm(){

	document.body.classList.add("showLoginForm");
  }
function closeLoginForm(){
	document.body.classList.remove("showLoginForm");
  }

















