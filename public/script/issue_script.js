let issue = document.querySelector(".issue");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    issue.parentElement.parentElement.classList.add("showMenu");
    issue.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let issue_info_label = document.querySelector('#issue_info_label');
issue_info_label.addEventListener("click", (e) => {
    issue_info_label.classList.toggle('show');
    let issue_info_container = document.querySelector('.issue_info_container');
    issue_info_container.classList.toggle('show_container');
});

let issue_details_list_label = document.querySelector('#issue_details_list_label');
issue_details_list_label.addEventListener("click", (e) => {
    issue_details_list_label.classList.toggle('show');
    let issue_details_list_container = document.querySelector('.issue_details_list_container');
    issue_details_list_container.classList.toggle('show_container');
});