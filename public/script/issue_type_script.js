let issue_type = document.querySelector(".issue-type");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    issue_type.parentElement.parentElement.classList.add("showMenu");
}

let issue_type_info_label = document.querySelector('#issue_type_info_label');
issue_type_info_label.addEventListener("click", (e) => {
    issue_type_info_label.classList.toggle('show');
    let issue_type_info_container = document.querySelector('.issue_type_info_container');
    issue_type_info_container.classList.toggle('show_container');
});

let issue_type_list_label = document.querySelector('#issue_type_list_label');
issue_type_list_label.addEventListener("click", (e) => {
    issue_type_list_label.classList.toggle('show');
    let issue_type_list_container = document.querySelector('.issue_type_list_container');
    issue_type_list_container.classList.toggle('show_container');
});