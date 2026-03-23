var screenWidth = window.innerWidth;
// console.log(screenWidth);
if (screenWidth < 1152) {

    let sidebar = document.querySelector('.sidebar');
    sidebar.classList.add('close');

}

window.onresize = function() {
    let sidebar = document.querySelector('.sidebar');
    if (screen.width < 1140) {
        sidebar.classList.add('close');
    } else {
        sidebar.classList.remove('close');
    }
    // document.querySelector('.home-section').style.height = screen.height + "px";
}



let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement;
        arrowParent.classList.toggle("showMenu");

        if (arrowParent.classList.contains('showMenu')) {
            localStorage.setItem('showMenu', 'true');
        } else {
            localStorage.removeItem('showMenu');
        }
    });
}

let icon_link = document.querySelectorAll(".link_name");
for (var i = 0; i < icon_link.length; i++) {
    icon_link[i].addEventListener("click", (e) => {
        let icon_link_parent = e.target.parentElement.parentElement.parentElement;

        icon_link_parent.classList.toggle("showMenu");

        if (icon_link_parent.classList.contains('showMenu')) {
            localStorage.setItem('showMenu', 'true');
        } else {
            localStorage.removeItem('showMenu');
        }

    });
}

let sub_arrow = document.querySelectorAll(".sub-arrow");
for (var i = 0; i < sub_arrow.length; i++) {
    sub_arrow[i].addEventListener("click", (e) => {
        let sub_arrow_parent = e.target.parentElement.parentElement;
        sub_arrow_parent.classList.toggle("showMenu");

        if (sub_arrow_parent.classList.contains('showMenu')) {
            localStorage.setItem('showMenu', 'true');
        } else {
            localStorage.removeItem('showMenu');
        }
    })
}

let sub_sub_arrow = document.querySelectorAll(".sub-sub-arrow");
for (var i = 0; i < sub_sub_arrow.length; i++) {
    sub_sub_arrow[i].addEventListener("click", (e) => {
        let sub_sub_arrow_parent = e.target.parentElement.parentElement;
        sub_sub_arrow_parent.classList.toggle("showMenu");

        if (sub_sub_arrow_parent.classList.contains('showMenu')) {
            localStorage.setItem('showMenu', 'true');
        } else {
            localStorage.removeItem('showMenu');
        }
    })
}

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu");
sidebarBtn.addEventListener("click", () => {

    if (sidebar.classList.contains('close')) {
        sidebar.classList.remove("close");
        localStorage.removeItem('sidebar_close');
    } else {
        sidebar.classList.add("close");
        localStorage.setItem('sidebar_close', 'true');
    }
})

let profileImgBtn = document.querySelector('.profile_img');
profileImgBtn.addEventListener("click", () => {
    if (sidebar.classList.contains('close')) {
        sidebar.classList.remove("close");
        localStorage.removeItem('sidebar_close');
    } else {
        sidebar.classList.add("close");
        localStorage.setItem('sidebar_close', 'true');
    }
})



if (localStorage.getItem('sidebar_close')) {
    sidebar.classList.add("close");
}

setTimeout(function() {
    const message = document.getElementById('flash-message');
    if (message) {
        message.style.transition = "opacity 0.5s ease";
        message.style.opacity = "0";

        // Remove from DOM after fade out is done
        setTimeout(() => message.remove(), 500); 
    }
}, 4000);