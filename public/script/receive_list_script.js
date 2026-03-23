let receive_list = document.querySelector(".receive-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    receive_list.parentElement.parentElement.classList.add("showMenu");
    receive_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}


let receive_list_label = document.querySelector('#receive_list_label');
receive_list_label.addEventListener("click", (e) => {
    receive_list_label.classList.toggle('show');
    let receive_list_container = document.querySelector('.receive_list_container');
    receive_list_container.classList.toggle('show_container');
});