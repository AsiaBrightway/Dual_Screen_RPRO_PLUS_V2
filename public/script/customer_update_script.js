let customer_list = document.querySelector(".customer-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    customer_list.classList.add("showMenu");
}


$('#edit_city').change(function() {
    var city_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: "/admin/city/getTownship",
        data: { 'cityID': city_id },
        success: function(data) {
            $('#edit_township').empty();
            $.each(data, function(key, value) {

                $('#edit_township').append('<option value="' + value
                    .township_id +
                    '">' +
                    value.township_name + '</option>');
            });
        }
    });
});