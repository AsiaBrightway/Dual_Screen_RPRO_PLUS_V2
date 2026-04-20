// let canceled_orders = document.querySelector(".canceled-orders");

let canceled_orders_label = document.querySelector("#canceled_orders_label");
canceled_orders_label.addEventListener("click", (e)=> {
    canceled_orders_label.classList.toggle("show");
    let canceled_orders_container = document.querySelector(".canceled_orders_container");
    canceled_orders_container.classList.toggle("show_container");
});

new DataTable('#canceled_orders', {
    scrollX: true,
})