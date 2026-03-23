// Voucher Info Section Toggle
let issue_info_label = document.querySelector('#issue_info_label');
let issue_info_container = document.querySelector('.issue_info_container');

// Restore state on page load
if (localStorage.getItem('showIssueInfoContainer')) {
    issue_info_container.classList.add('show_container');
    issue_info_label.classList.add('show');
}

issue_info_label.addEventListener("click", (e) => {
    issue_info_label.classList.toggle('show');
    issue_info_container.classList.toggle('show_container');
    
    if (issue_info.classList.contains('show_container')) {
        localStorage.setItem('showIssueInfoContainer', 'true');
    } else {
        localStorage.removeItem('showIssueInfoContainer');
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