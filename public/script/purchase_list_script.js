let purchase_list = document.querySelector(".purchase-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    purchase_list.parentElement.parentElement.classList.add("showMenu");
    purchase_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}


let purchase_list_label = document.querySelector('#purchase_list_label');
purchase_list_label.addEventListener("click", (e) => {
    purchase_list_label.classList.toggle('show');
    let purchase_list_container = document.querySelector('.purchase_list_container');
    purchase_list_container.classList.toggle('show_container');
});