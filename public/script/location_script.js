let location_list = document.querySelector(".location-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    location_list.parentElement.parentElement.classList.add("showMenu");
}


let city_list_container = document.querySelector('.city_list_container');
if (localStorage.getItem('showCityListContainer')) {
    city_list_container.classList.add("show_container");
}

let township_list_container = document.querySelector('.township_list_container');
if (localStorage.getItem('showTownshipListContainer')) {
    township_list_container.classList.add("show_container");
}

let city_list_label = document.querySelector('#city_list_label');
city_list_label.addEventListener("click", (e) => {
    city_list_label.classList.toggle('show');
    let city_list_container = document.querySelector('.city_list_container');
    city_list_container.classList.toggle('show_container');
    if (city_list_container.classList.contains('show_container')) {
        localStorage.setItem('showCityListContainer', 'true');
    } else {
        localStorage.removeItem('showCityListContainer');
    }
});

let township_list_label = document.querySelector('#township_list_label');
township_list_label.addEventListener("click", (e) => {
    township_list_label.classList.toggle('show');
    let township_list_container = document.querySelector('.township_list_container');
    township_list_container.classList.toggle('show_container');
    if (township_list_container.classList.contains('show_container')) {
        localStorage.setItem('showTownshipListContainer', 'true');
    } else {
        localStorage.removeItem('showTownshipListContainer');
    }
});
new DataTable('#city', {
    scrollX: true
});
new DataTable('#township', {
    scrollX: true
});

$(function() {
    $("#cityFormCreate").validate({
        rules: {
            city_name: {
                required: true,
                remote: {
                    url: "/admin/city/checkCityName",
                    type: "GET",
                    data: {
                        city_name: function() {
                            return $("#city_name").val();
                        },
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    dataFilter: function(data) {
                        var json = JSON.parse(data);
                        return json.exists ? false : true;
                    }
                }
            }
        },
        messages: {
            city_name: {
                required: "City Name ဖြည့်ရန်လိုအပ်ပါသည်",
                remote: "City Name တူနေပါသည်"
            }
        }
    });
});

$(document).on("click", ".edit_city_modal_dialog", function() {
    var city_id = $(this).data('city_id');
    var city_name = $(this).data('city_name');
    var is_discontinued = $(this).data('is_discontinued');
    $(".modal-body #edit_city_id").val(city_id);
    $(".modal-body #edit_city_name").val(city_name);
    if (is_discontinued == 1) {
        document.getElementById("edit_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_is_discontinued").checked = false;
    }
});

$(function() {
    $("#cityFormEdit").validate({
        rules: {
            city_name: {
                required: true,
                remote: {
                    url: "/admin/city/checkCityName",
                    type: "GET",
                    data: {
                        city_name: function() {
                            return $("#city_name").val();
                        },
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    dataFilter: function(data) {
                        var json = JSON.parse(data);
                        return json.exists ? false : true;
                    }
                }
            }
        },
        messages: {
            city_name: {
                required: "City Name ဖြည့်ရန်လိုအပ်ပါသည်",
                remote: "City Name တူနေပါသည်"
            }
        }
    });

});

$(document).on("click", ".delete_city_modal_dialog", function() {
    var city_id = $(this).data('city_id');
    var city_name = $(this).data('city_name');
    $(".modal-header #delete_modal_header").text("Delete '" + city_name + "'")
    $(".modal-body #delete_city_id").val(city_id);
});

$(function() {
    $("#townshipFormCreate").validate({
        rules: {
            township_name: {
                required: true,
                remote: {
                    url: "/admin/township/checkTownshipName",
                    type: "GET",
                    data: {
                        township_name: function() {
                            return $("#township_name").val();
                        },
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    dataFilter: function(data) {
                        var json = JSON.parse(data);
                        return json.exists ? false : true;
                    }
                }
            }
        },
        messages: {
            township_name: {
                required: "Township Name ဖြည့်ရန်လိုအပ်ပါသည်",
                remote: "Township Name တူနေပါသည်"
            }
        }
    });

});

$(document).on("click", ".edit_township_modal_dialog", function() {
    var township_id = $(this).data('township_id');
    var township_name = $(this).data('township_name');
    var city_id = $(this).data('city_id');
    var is_discontinued = $(this).data('is_discontinued');


    $(".modal-body #edit_township_id").val(township_id);
    $(".modal-body #edit_township_name").val(township_name);
    $(".modal-body #city").val(city_id);

    if (is_discontinued == 1) {
        console.log("IN 1");
        document.getElementById("edit_township_is_discontinued").checked = true;
    } else {
        console.log("IN O");
        document.getElementById("edit_township_is_discontinued").checked = false;
    }
});

$(function() {
    $("#townshipFormEdit").validate({
        rules: {
            township_name: {
                required: true,
                remote: {
                    url: "/admin/township/checkTownshipName",
                    type: "GET",
                    data: {
                        township_name: function() {
                            return $("#township_name").val();
                        },
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    dataFilter: function(data) {
                        var json = JSON.parse(data);
                        return json.exists ? false : true;
                    }
                }
            }
        },
        messages: {
            township_name: {
                required: "Township Name ဖြည့်ရန်လိုအပ်ပါသည်",
                remote: "Township Name တူနေပါသည်"
            }
        }
    });

});

$(document).on("click", ".delete_township_modal_dialog", function() {
    var township_id = $(this).data('township_id');
    var township_name = $(this).data('township_name');
    $(".modal-header #delete_modal_header").text("Delete '" + township_name + "'")
    $(".modal-body #delete_township_id").val(township_id);
});
