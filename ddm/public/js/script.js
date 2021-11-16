var form_row = "";

// Load Home page content i.e Dining page content initializing
$("document").ready(function () {
    $.ajax({
        url: "/api/home",
        type: "get",
    }).done(function (res) {
        $("#content").html("").append(res);
    })
})

// Add new meal consumed 
// OR record your weight
$(document).on("click", "#button-add-meal", function () {
    if ($("#meal-input-box").val() != "") {
        $.ajax({
            url: "/api/dailydite/add_dite",
            type: "post",
            data: { "dite": $("#meal-input-box").val() },
        }).done(function (res) {
            if (typeof (res.status != 'undefined') && res.status == 'error') {
                alertError(res);
            }
            else {
                if (typeof (res.weight) != 'undefined') {
                    //success message for user weight record
                    weightAdded(res);
                } else {
                    //success message for new consumed meal added
                    mealAdded(res);
                }
            }
        });
    }
    else {
        alertInputData();
    }
});



$(document).on("click", "#button-edit-meal", function () {
    if (confirm("Are you sure to update meal")) {
        $.ajax({
            url: "/api/dailydite/update_dite",
            type: "post",
            data: { "created_at": $("#created_at").val(), "dite": $("#meal-edit-input-box").val() },
        }).done(function (res) {
            msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';
            msg += '<strong>Success!</strong> Meal successfully Updated</div>'
            $("#content").html("").append(res);
            alert(msg)
            $("#myModal").modal("hide");
        });
    }

});



$(document).on("click", ".btn-edit-item", function (e) {

    e.preventDefault();

    $.ajax({

        url: "/api/dailydite/dite_defaults",

        type: "post",

        data: { id: $(this).attr("id"), defaults: $(this).prev("input").val() },

    }).done(function (res) {

        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += ' <span class="mx-auto"><strong>Success!</strong> item default successfully updated </span></div>'

        alert(msg);

    });

});

// $(document).on("change blur", ".library-input input:not(.intro input)", function() {
//     $(this).next("a").trigger("click");
// })

$(document).on("click", ".btn-delete-item", function (e) {

    e.preventDefault();
    if(confirm("Are you sure to delete Food item")){
    $.ajax({

        url: "/api/dailydite/delete_food_item",

        type: "post",

        data: { id: $(this).attr("id") },

    }).done(function (res) {
        $(this).parent("li").remove();
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += ' <span class="mx-auto"><strong>Success!</strong> Food item successfully Deleted </span></div>'

        alert(msg);

    }.bind(this));
}

});

// $(document).on("focus", "#monthpicker", function () {
//     $("#monthpicker").datepicker({
//         changeMonth: true,
//         changeYear: true,
//         showButtonPanel: true,
//         dateFormat: 'm-yy',
//         onClose: function (dateText, inst) {
//             $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
//             $(this).trigger("change");
//         }
//     });
// });
$(document).on('change','select[name="month"]', function () {
  var monthValue = $("select[name=month]").val();
  var yearValue = $("input[name=year]").val();

  url = $(this).data("url");
  $.ajax({

    url: url,

    type: "get",

    data: { month: monthValue +"-"+ yearValue },

  }).done(function (res) {
    $("#content").html("").append(res);
  })
});
// $(document).on("click","#monthpicker",function(){
//     url = $(this).data("url");
//     $.ajax({
//
//         url: url,
//
//         type: "get",
//
//         data: { month: $("select[name=month]").val()+"-"+$("input[name=year]").val() },
//
//     }).done(function (res) {
//         $("#content").html("").append(res);
//     })
// });


$(document).on("click", "#btn-add-item", function (e) {

    e.preventDefault();

    $.ajax({

        url: "/api/dailydite/dite_defaults",

        type: "post",

        data: { defaults: $(this).prev("input").val() },

    }).done(function (res) {

        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += '<strong>Success!</strong> item default successfully added</div>'


        $("#content").html("").append(res);

        alert(msg);



    });

});

$(document).on("submit", "#dite-item-default-frm", function (e) {

    e.preventDefault();



});

var i = 0;
$(document).on("click", ".text-add-btn", function () {
    i++;
    var nameValue = $(this).attr("data-name");
    var weightValue = $(this).attr("data-weight");
    var qtyValue = $(this).attr("data-qty");
    var priceValue = $(this).attr("data-price");
    var quantityValue = $(this).attr("data-quantity");

    var _form_row = $(form_row).clone();
    $(_form_row).find("a.btn-danger").removeClass('d-none');
    $(_form_row).find('input[name="name[]"]').val(nameValue);
    if (qtyValue != "") {
        $(_form_row).find('input[name="weight[]"]').val(qtyValue);
        $(_form_row).find('select[name="unit[]"]').val("pcs");
        $(_form_row).find('option[value=pcs]').attr('selected', 'selected');
        $(_form_row).find('#radio_pcs').prop("checked", true);
    }
    else {
        $(_form_row).find('input[name="weight[]"]').val(weightValue);
        $(_form_row).find('select[name="unit[]"]').val("g");
        $(_form_row).find('option[value=g]').attr('selected', 'selected');
        $(_form_row).find('#radio_g').prop("checked", true);
        $(_form_row).find('input[name="weight[]"]').parents('.form-row').addClass('weight')
    }
    $(_form_row).find('input[name="count[]"]').val(quantityValue);
    $(_form_row).find('input[name="price[]"]').val(priceValue);
    $(_form_row).find(':radio').attr("name", "radio" + i);
    $(_form_row).find('#radio_pcs').attr("id", "radio_pcs" + i);
    $(_form_row).find('#radio_g').attr("id", "radio_g" + i);

    $(_form_row).find('#radio_pcs_for').attr("for", "radio_pcs" + i);
    $(_form_row).find('#radio_g_for').attr("for", "radio_g" + i);
    $(_form_row).find("a.btn-danger").removeClass('d-none');
    $('#buy_meal_form .text-end').before(_form_row);
    if ($(_form_row).find('.form-row.weight')) {
        $('.form-row.weight').find('select[name="unit[]"]').val('g').trigger('change');
    }

});

$(document.body).on("click", "input.unit", function (e) {
    $(this).parent("div").find("select").val($(this).val())
    console.log($(this).parent("div").find("select").val())
});

$(document).on('keypress', ".data-input-box", function (event) {

    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {

        $(this).next("button").trigger("click"); // $("#button-buy-meal").trigger("click");

    }

});

$(document).on("click", ".upload-image", function () {

    $(this).parent("a").find("input").trigger("click");

});

$(document).on('change', ".upload-image-input", function () {

    $(this).parents("form").trigger("submit");

});

$(document).on("submit", ".upload-image-form", function (e) {

    e.preventDefault();

    var fd = new FormData(this);

    var files = $(this).find('input[name=image]')[0].files;

    var id = $(this).find('input[name=id]').val();

    if (files.length > 0) {

        $.ajax({

            url: $(this).attr('action'),

            type: 'post',

            data: fd,

            contentType: false,

            processData: false,

            success: function (response) {

                fooditem = $("#image-container-" + id).parent("div").find("div"); //.closest("div").find("div");

                h2 = $(fooditem).find("h2");
                h5 = $(fooditem).find("h5");
                h6 = $(fooditem).find("h6");

                if (response != 0) {

                    if (h6) {
                        $(h6).addClass("mb-auto");
                    }

                    image = '<img src="' + response.image + '" id="image' + response.id + '" alt="Food" class="' + response.class + '" />'

                    $("#image-container-" + id).html(image);

                    $(fooditem).html("").append(h6);
                    if (h5) {
                        $(fooditem).append(h5);
                    }

                    $(fooditem).append(h2);

                } else {

                    alert('file not uploaded');

                }

            },

        });

    } else {

        alert("Please select a file.");

    }

});



$(".nav-menu-link").on("click", function (e) {

    e.preventDefault();

    $(".nav-menu-link").removeClass("active");

    $(this).addClass("active");

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function (res) {

        $("#content").html("").append(res);
        form_row = $(".form-row:nth-child(2)").clone();


    })

});

$(document).on("click", ".btn-edit-food", function () {

    $.ajax({

        url: $(this).data('href'),

        data: { created_at: $(this).data("created_at") },

        type: "get",

    }).done(function (res) {

        res = JSON.parse(res);

        $("#meal-edit-input-box").val(res.food);
        $(".modal-title").text("Update Meal");

        date = $('<input type="hidden" id="created_at">');
        meal = $('<input type="text" class="form-control me-2" placeholder="egg 2, beef 15g" aria-label="egg 2, beef 15g" id="meal-edit-input-box"></input>');
        button = '<button class="btn btn-success d-flex align-items-center" type="button" id="button-edit-meal">';
        button += '<img src="images/right-arrow.png" width="10" class="img-fluid"></button>'
        buttion = $(button);
        date.val(res.created_at);
        meal.val(res.food);
        html = $("<tr></tr>");
        $(html).append($("<td class='d-flex'></td>").append(date).append(meal).append(buttion));
        $("#myModalBody").html($(html));
        $("#myModal").modal("show");
    }.bind(this))

})

$(document).on("click", ".btn-delete-food", function (e) {

    if (confirm("Are you sure to delete Food")) {

        $.ajax({

            url: $(this).data('href'),

            data: { created_at: $(this).data("created_at") },

            type: "get",

        }).done(function (res) {

            $(this).parent("div").remove();

            $(".ajax-page-link").trigger("click");

            msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

            msg += ' <span class="mx-auto"><strong>Success! </strong> food deleted successfully</span></div>'

            alert(msg)

        }.bind(this))

    }

});

$("#content").on("click", ".ajax-page-load-more", function (e) {

    e.preventDefault();

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function (res) {

        html = $(res).filter(".recent-meals");

        loadmore = $(res).filter("#load_more");

        $("#content").find("#load_more").remove();

        $("#content").find(".recent-meals").append(html.html());

        $("#content").append(loadmore);

    })



})


$("#content").on("click", ".ajax-page-link", function (e) {

    e.preventDefault();

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function (res) {

        $("#content").html("").append(res);


    })



});



$(document).on("click", ".meal_item_history", function (e) {

    e.preventDefault();

    myModalHeadHtml = modalHeadHtml(['Items', 'g/pcs', 'Counts'])
    $("#myModalHead").html($(myModalHeadHtml));

    $.ajax({

        url: $(this).data('href'),

        data: {
            month: $(this).data("month"),
            food_item: $(this).data('item'),

        },

        type: "post",

    }).done(function (res) {

        myModalBodyHtml = '';



        res.data.forEach(function (element) {

            if (element.qty != 0) {

                total = element.qty + "pcs";

            } else {

                total = element.weight + "g";

            }



            myModalBodyHtml += '<tr>';

            myModalBodyHtml += '<th><a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal"';

            myModalBodyHtml += 'data-bs-target="#exampleModal">' + element.created_at.substr(0, 10) + '</a></th>';

            myModalBodyHtml += '<td>' + total + '</td><td>' + element.weight / 100 * (element.cal.replace("cal", "")) + "cal" + '</td>';

            myModalBodyHtml += '</tr>';



        });



        $(".modal-title").text("Meal Item Report")
        $("#myModalBody").html($(myModalBodyHtml));

    })

    $('#myModal').modal('show');

});

$(document).on("click", ".person-weight", function (e) {
    e.preventDefault();
    myModalHeadHtml = '<tr><th scope="col">Date</th><th scope="col">weight</th></tr>';
    myModalHeadHtml = modalHeadHtml(['Date', 'Weight'])

    $("#myModalHead").html($(myModalHeadHtml));

    $.ajax({

        url: $(this).data('href'),
        data:{month: $(this).data("month")},
        type: "post",

    }).done(function (res) {

        myModalBodyHtml = '';



        res.data.forEach(function (element) {

            myModalBodyHtml += '<tr>';

            myModalBodyHtml += '<th><a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal"';

            myModalBodyHtml += 'data-bs-target="#exampleModal">' + element.created_at.substr(0, 10) + '</a></th>';

            myModalBodyHtml += '<td>' + element.weight + '</td>';

            myModalBodyHtml += '</tr>';



        });


        $(".modal-title").text("User Weight Report")

        $("#myModalBody").html($(myModalBodyHtml));

    })

    $('#myModal').modal('show');

})

// buying events

$(document).on('submit', '#buy_meal_form', function (e) {
    e.preventDefault();
    var validated = true;
    $('#buy_meal_form input').not('.quantity').filter(function () {
        if (!$(this).val()) {
            $(this).parents('.form-col').addClass('has-error');
            validated = false;
        }
    });
    if (!validated)
        return;
    data = $(this).serializeArray();

    $.ajax({

        url: "/api/buying/buy_meal",

        type: "post",

        data: data,

    }).done(function (res) {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += '<strong>Success!</strong>New Purchase successfully Added</div>'

        $("#content").html("").append(res);

        alert(msg);


    });

});

$(document).on("submit", ".update-food-form", function (e) {
    e.preventDefault();
    url = $(".update-food-form").data("action");
    formdata = $(this).serializeArray();
    console.log(formdata);
    $.ajax({

        url: url,

        data: formdata,

        type: "post",

    }).done(function (res) {
        if (res.status != "error") {
            item = $(res).attr("id");
            console.log(item);
            $("#" + item).html("").append($($(res).html()))
            msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';
            msg += '<strong>Success!</strong> Successfully Updated';
            msg += '</div>';
            alert(msg);
        }
        $('#myModal').modal('hide');

    }.bind(this));
})

$(document).on("click", ".edit-item-btn", function (e) {
    e.preventDefault();
    $.ajax({

        url: $(this).data('href'),

        data: { id: $(this).data("id") },

        type: "get",

    }).done(function (res) {
        $("#myModalHead").html("");
        $(".modal-title").text("Update Food Item");
        td = $("<td></td>");
        td.append($(res));
        $("#myModalBody").html("").append($(td));
        $('#myModal').modal('show');

    }.bind(this));
})

$(document).on("click", ".buying-details", function (e) {
    e.preventDefault();
    myModalHeadHtml = modalHeadHtml(['Date', 'Weight', 'Price Paid', 'Price/Kg', 'shop', 'brand'])
    $("#myModalHead").html($(myModalHeadHtml));

    $.ajax({

        url: $(this).data('href'),

        data: {

            response_type: "json",
            source: $(this).data('item'),

        },

        type: "post",

    }).done(function (res) {

        myModalBodyHtml = '';



        lowestPrice = 0;
        res.data.forEach(function (element) {


            if (element.weight != null)
                unitPrice = Math.round((element.price / element.weight * 1000 + Number.EPSILON) * 100) / 100
            else
                unitPrice = Math.round((element.price / element.qty + Number.EPSILON) * 100) / 100

            if (lowestPrice == 0) {
                lowestPrice = unitPrice;
            }
            if (lowestPrice > unitPrice) {
                lowestPrice = unitPrice;
            }

            if (lowestPrice == unitPrice)
                myModalBodyHtml += '<tr data-id="' + lowestPrice + '">';
            else
                myModalBodyHtml += '<tr>';

            myModalBodyHtml += '<th><a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal"';
            myModalBodyHtml += 'data-bs-target="#exampleModal">' + element.created_at.substr(0, 10) + '</a></th>';
            if (element.weight != null)
                myModalBodyHtml += '<td>' + element.weight + 'g</td>';
            else
                myModalBodyHtml += '<td>' + element.qty + 'pcs</td>';
            myModalBodyHtml += '<td>RM' + element.price + '</td>';
            if (element.weight != "")
                myModalBodyHtml += '<td>RM' + unitPrice + '/Kg</td>';
            else
                myModalBodyHtml += '<td>RM' + unitPrice + '/pcs</td>';
            myModalBodyHtml += '<td>' + element.shop_name + '</td>';
            myModalBodyHtml += '<td>' + element.brand_name + '</td>';
            myModalBodyHtml += '</tr>';


        });

        const today = currentMonthYear();
        $(".modal-title").text("Meal Item Report " + today);
        $("#myModalBody").html($(myModalBodyHtml));
        $(document).find('[data-id="' + lowestPrice + '"]').addClass("lowest-price")
        $('#myModal').modal("show");


    })


});

$(document).on("click", ".delete-card", function (e) {
    id = $(this).data("id");
    if (confirm("are you sure to delete")) {
        $.ajax({
            url: $(this).data('href'),
            type: "get",
        }).done(function (res) {
            $("#item-" + id).remove();
        });
    }

});



/*
Settings and Configrations
*/
// Shop events


$(document).on("click", "#button-add-shop", function () {
    if ($("#" + $(this).data('input')).val() == "") {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-error" id="success-alert">';
        msg += '<strong>Error!</strong> Please Input Shop list';
        msg += '</div>';
        alert(msg);
        return;
    }
    submitInputData(this);
});


$(document).on("click", ".shop-delete-btn", function () {
    url = $(this).data("href");
    if (confirm("Are you sure to delete Shop")) {
        $.ajax({
            url: url,
            type: "get",
        }).done(function (res) {
            $(this).parent().parent().parent().parent().remove();
        }.bind(this));
    }
});

$(document).on("click", ".shop-edit-btn", function () {
    id = $(this).data("id");
    text = $(this).data("text");
    input = $(this).data("input");
    $("#" + input).val(text).data("id", id)
});


// Brand events


$(document).on("click", "#button-add-brand", function () {
    if ($("#" + $(this).data('input')).val() == "") {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-error" id="success-alert">';
        msg += '<strong>Error!</strong> Please Input brand list';
        msg += '</div>';
        alert(msg);
        return;
    }
    submitInputData(this);
});

$(document).on("click", ".brand-delete-btn", function () {
    url = $(this).data("href");
    if (confirm("Are you sure to delete brand")) {
        $.ajax({
            url: url,
            type: "get",

        }).done(function (res) {
            $(this).parent().parent().parent().parent().remove();
        }.bind(this));
    }
});

$(document).on("click", ".brand-edit-btn", function () {
    id = $(this).data("id");
    text = $(this).data("text");
    input = $(this).data("input");
    $("#" + input).val(text).data("id", id)
});
/// Functions

function submitInputData(element) {
    url = $(element).data("href");
    input = $(element).data("input");
    $.ajax({

        url: url,

        type: "post",

        data: { "input": $("#" + input).val(), id: $("#" + input).data("id") },

    }).done(function (res) {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-' + res.status + '" id="success-alert">';
        msg += res.msg;
        msg += '</div>';
        $(element).data("id", "");
        alert(msg);

        $.ajax({

            url: url,

            type: "get",

        }).done(function (res) {
            $("#content").html("").append(res);
        });


    });
}

function modalHeadHtml(cols) {
    myModalHeadHtml = '<tr>';
    cols.forEach(function (col) {
        myModalHeadHtml += '<th scope="col">' + col + '</th>'
    })
    myModalHeadHtml += '</tr>';
    return myModalHeadHtml;
}


function weightAdded(res) {
    msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

    msg += res.msg + '</div>'

    alert(msg);
}

function mealAdded(res) {
    msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

    msg += '<strong>Success!</strong> Meal successfully added</div>'

    $("#content").html("").append(res);
    alert(msg);
}

function alertError() {
    msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-danger" id="success-alert">';

    msg += '<strong>Error!</strong> Unknow error occured.</div>'

    alert(msg);
}

function alertInputData() {
    msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-danger" id="success-alert">';

    msg += '<strong>Error!</strong> Please input Data.</div>'

    alert(msg);
}

function alert(msg) {
    $(document.body).prepend(msg);

    $("#success-alert").alert();

    window.setTimeout(function () {

        $("#success-alert").alert('close');

    }, 2000);
}

var i = 0;
$(document).on('click', '.add_buy_meal_form_row', function (e) {
    i++;
    e.preventDefault();
    var _form_row = $(form_row).clone();
    $(_form_row).find(':radio').attr("name", "radio" + i);
    $(_form_row).find('#radio_pcs').attr("id", "radio_pcs" + i);
    $(_form_row).find('#radio_g').attr("id", "radio_g" + i);

    $(_form_row).find('#radio_pcs_for').attr("for", "radio_pcs" + i);
    $(_form_row).find('#radio_g_for').attr("for", "radio_g" + i);
    $(_form_row).find("a.btn-danger").removeClass('d-none');
    $('#buy_meal_form .text-end').before(_form_row);
});

$(document).on('click', '.remove_buy_meal_form_row', function (e) {
    e.preventDefault();
    $(this).parents('.form-row:not(:first-child)').remove();
});


$(document).on('keyup', '#buy_meal_form input', function () {
    $(this).parent('.form-col').removeClass('has-error');
});

function currentMonthYear() {
    const month = new Array();
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";

    const d = new Date();
    return month[d.getMonth()] + " " + d.getFullYear();
}

$(document).on('click','#radio_pcs:radio',function(){
  var weightVal = $(document).find('input[name="weight[]').val();
  if($("#radio_pcs").is(':checked')){
    if (!Number.isInteger(weightVal)) {
      intNum = Math.round(weightVal);
    }
    $(document).find('input[name="weight[]').val(intNum);
  }
});
$(document).on('keyup','input[name="weight[]"]',function(){
  var weightVal = $(this).val();
  if($("#radio_pcs").is(':checked')){
    if (!Number.isInteger(weightVal)) {
      intNum = Math.round(weightVal);
    }
    $(document).find('input[name="weight[]').val(intNum);
  }
});
// override jquery validate plugin defaults


