let issue_list = document.querySelector(".issue-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    issue_list.parentElement.parentElement.classList.add("showMenu");
    issue_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}


let issue_list_label = document.querySelector('#issue_list_label');
issue_list_label.addEventListener("click", (e) => {
    issue_list_label.classList.toggle('show');
    let issue_list_container = document.querySelector('.issue_list_container');
    issue_list_container.classList.toggle('show_container');
});