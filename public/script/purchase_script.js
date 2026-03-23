let purchase = document.querySelector(".purchase");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    purchase.parentElement.parentElement.classList.add("showMenu");
    purchase.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let voucher_info_label = document.querySelector('#voucher_info_label');
voucher_info_label.addEventListener("click", (e) => {
    voucher_info_label.classList.toggle('show');
    let voucher_info_container = document.querySelector('.voucher_info_container');
    voucher_info_container.classList.toggle('show_container');
});

let item_details_info_label = document.querySelector('#item_details_info_label');
item_details_info_label.addEventListener("click", (e) => {
    item_details_info_label.classList.toggle('show');
    let item_details_info_container = document.querySelector('.item_details_info_container');
    item_details_info_container.classList.toggle('show_container');
});

let voucher_details_info_label = document.querySelector('#voucher_details_info_label');
voucher_details_info_label.addEventListener("click", (e) => {
    voucher_details_info_label.classList.toggle('show');
    let voucher_details_info_container = document.querySelector('.voucher_details_info_container');
    voucher_details_info_container.classList.toggle('show_container');
});