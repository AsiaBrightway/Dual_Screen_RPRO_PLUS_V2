    // Voucher Info Section Toggle
    let voucher_info_label = document.querySelector('#voucher_info_label');
    let voucher_info_container = document.querySelector('.voucher_info_container');

    // Restore state on page load
    if (localStorage.getItem('showVoucherInfoContainer')) {
        voucher_info_container.classList.add('show_container');
        voucher_info_label.classList.add('show');
    }

    voucher_info_label.addEventListener("click", (e) => {
        voucher_info_label.classList.toggle('show');
        voucher_info_container.classList.toggle('show_container');
        
        if (voucher_info_container.classList.contains('show_container')) {
            localStorage.setItem('showVoucherInfoContainer', 'true');
        } else {
            localStorage.removeItem('showVoucherInfoContainer');
        }
    });

    // Order Lists Section Toggle
    let sale_order_details_list_label = document.querySelector('#sale_order_details_list_label');
    let sale_order_details_list_container = document.querySelector('.sale_order_details_list_container');

    // Restore state on page load
    if (localStorage.getItem('showOrderListContainer')) {
        sale_order_details_list_container.classList.add('show_container');
        sale_order_details_list_label.classList.add('show');
    }

    sale_order_details_list_label.addEventListener("click", (e) => {
        sale_order_details_list_label.classList.toggle('show');
        sale_order_details_list_container.classList.toggle('show_container');
        
        if (sale_order_details_list_container.classList.contains('show_container')) {
            localStorage.setItem('showOrderListContainer', 'true');
        } else {
            localStorage.removeItem('showOrderListContainer');
        }
    });

    new DataTable("#sale_order_details_list", {
        scrollX: true,
        paging: false,        // Disable pagination
        searching: false,     // Disable search box
        info: false,          // Hide "Showing X of Y entries"
        lengthChange: false,  // Hide "Show entries" dropdown
        ordering: false       // Disable sorting/ordering
    });