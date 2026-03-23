let receive = document.querySelector(".receive");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    receive.parentElement.parentElement.classList.add("showMenu");
    receive.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

// let receive_info_label = document.querySelector('#receive_info_label');
// receive_info_label.addEventListener("click", (e) => {
//     receive_info_label.classList.toggle('show');
//     let receive_info_container = document.querySelector('.receive_info_container');
//     receive_info_container.classList.toggle('show_container');
// });

let item_details_info_label = document.querySelector('#item_details_info_label');
item_details_info_label.addEventListener("click", (e) => {
    item_details_info_label.classList.toggle('show');
    let item_details_info_container = document.querySelector('.item_details_info_container');
    item_details_info_container.classList.toggle('show_container');
});