$("document").ready(function() {

    $.ajax({

        url: "/home",

        type: "get",

    }).done(function(res) {

        $("#content").html("").append(res);

    })

})

$(document).on("click", "#button-add-meal", function() {

    $.ajax({

        url: "/api/dailydite/add_dite",

        type: "post",

        data: { "dite": $("#meal-input-box").val() },

    }).done(function(res) {
        if (typeof(res.weight) != 'undefined') {
            weightAdded(res);
        } else {
            mealAdded(res);
        }

    });

});



$(document).on("click", "#button-edit-meal", function() {

    if (confirm("Are you sure to update meal")) {
        $.ajax({

            url: "/api/dailydite/update_dite",

            type: "post",

            data: { "created_at": $("#created_at").val(), "dite": $("#meal-edit-input-box").val() },

        }).done(function(res) {

            msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

            msg += '<strong>Success!</strong> Meal successfully Updated</div>'

            $("#content").html("").append(res);

            alert(msg)
            $("#myModal").modal("hide");

        });
    }

});



$(document).on("click", ".btn-edit-item", function(e) {

    e.preventDefault();

    $.ajax({

        url: "/api/dailydite/dite_defaults",

        type: "post",

        data: { id: $(this).attr("id"), defaults: $(this).prev("input").val() },

    }).done(function(res) {

        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += ' <span class="mx-auto"><strong>Success!</strong> item default successfully updated </span></div>'

        alert(msg);

    });

});

// $(document).on("change blur", ".library-input input:not(.intro input)", function() {
//     $(this).next("a").trigger("click");
// })

$(document).on("click", ".btn-delete-item", function(e) {

    e.preventDefault();
    $.ajax({

        url: "/api/dailydite/delete_food_item",

        type: "post",

        data: { id: $(this).attr("id") },

    }).done(function(res) {
        $(this).parent("li").remove();
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += ' <span class="mx-auto"><strong>Success!</strong> Food item successfully Deleted </span></div>'

        alert(msg);

    }.bind(this));

});




$(document).on("click", "#btn-add-item", function(e) {

    e.preventDefault();

    $.ajax({

        url: "/api/dailydite/dite_defaults",

        type: "post",

        data: { defaults: $(this).prev("input").val() },

    }).done(function(res) {

        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += '<strong>Success!</strong> item default successfully added</div>'


        $("#content").html("").append(res);

        alert(msg);



    });

});

$(document).on("submit", "#dite-item-default-frm", function(e) {

    e.preventDefault();



});


$(document).on("click", ".text-add-btn", function() {
    input = $(this).data('input');
    val = $("#" + input).val();
    if (val != "") {
        val = val + ", ";
    }
    str = $(this).data("text");
    val += str;
    $("#" + input).val(val).trigger('focus');

});

$(document).on('keypress', ".data-input-box", function(event) {

    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {

        $(this).next("button").trigger("click"); // $("#button-buy-meal").trigger("click");

    }

});

$(document).on("click", ".upload-image", function() {

    $(this).parent("a").find("input").trigger("click");

});

$(document).on('change', ".upload-image-input", function() {

    $(this).parents("form").trigger("submit");

});

$(document).on("submit", ".upload-image-form", function(e) {

    e.preventDefault();

    var fd = new FormData(this);

    var files = $(this).find('input[name=image')[0].files;

    var id = $(this).find('input[name=id').val();

    if (files.length > 0) {

        $.ajax({

            url: $(this).attr('action'),

            type: 'post',

            data: fd,

            contentType: false,

            processData: false,

            success: function(response) {

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



$(".nav-menu-link").on("click", function(e) {

    e.preventDefault();

    $(".nav-menu-link").removeClass("active");

    $(this).addClass("active");

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function(res) {

        $("#content").html("").append(res);

    })

});

$(document).on("click", ".btn-edit-food", function() {

    $.ajax({

        url: $(this).data('href'),

        data: { created_at: $(this).data("created_at") },

        type: "get",

    }).done(function(res) {

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

$(document).on("click", ".btn-delete-food", function(e) {

    if (confirm("Are you sure to delete Food")) {

        $.ajax({

            url: $(this).data('href'),

            data: { created_at: $(this).data("created_at") },

            type: "get",

        }).done(function(res) {

            $(this).parent("div").remove();

            $(".ajax-page-link").trigger("click");

            msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

            msg += ' <span class="mx-auto"><strong>Success! </strong> food deleted successfully</span></div>'

            alert(msg)

        }.bind(this))

    }

});

$("#content").on("click", ".ajax-page-load-more", function(e) {

    e.preventDefault();

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function(res) {

        html = $(res).filter(".recent-meals");

        loadmore = $(res).filter("#load_more");

        $("#content").find("#load_more").remove();

        $("#content").find(".recent-meals").append(html.html());

        $("#content").append(loadmore);

    })



})


$("#content").on("click", ".ajax-page-link", function(e) {

    e.preventDefault();

    $.ajax({

        url: $(this).data('href'),

        type: "get",

    }).done(function(res) {

        $("#content").html("").append(res);

    })



});



$(document).on("click", ".meal_item_history", function(e) {

    e.preventDefault();

    myModalHeadHtml = modalHeadHtml(['Items', 'g/pcs', 'Counts'])
    $("#myModalHead").html($(myModalHeadHtml));

    $.ajax({

        url: $(this).data('href'),

        data: {

            food_item: $(this).data('item'),

        },

        type: "post",

    }).done(function(res) {

        myModalBodyHtml = '';



        res.data.forEach(function(element) {

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

$(document).on("mouseleave", ".library-input", function() {
    $(this).find("a").addClass("d-none");
})

$(document).on("mouseenter", ".library-input", function() {
    $(this).find("a").removeClass("d-none");
})

$(document).on("click", ".person-weight", function(e) {
        e.preventDefault();
        myModalHeadHtml = '<tr><th scope="col">Date</th><th scope="col">weight</th></tr>';
        myModalHeadHtml = modalHeadHtml(['Date', 'Weight'])

        $("#myModalHead").html($(myModalHeadHtml));

        $.ajax({

            url: $(this).data('href'),

            type: "post",

        }).done(function(res) {

            myModalBodyHtml = '';



            res.data.forEach(function(element) {

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

$(document).on('submit','#buy_meal_form',function (e) {
    e.preventDefault();
    data = $(this).serialize();
   
    $.ajax({

        url: "/api/buying/buy_meal",

        type: "post",

        data: data ,

    }).done(function(res) {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-success" id="success-alert">';

        msg += '<strong>Success!</strong>New Purchase successfully Added</div>'

        $("#content").html("").append(res);

        alert(msg);


    });

});

$(document).on("submit", ".update-food-form", function(e) {
    e.preventDefault();
    url = $(".update-food-form").data("action");
    formdata = $(this).serializeArray();
    console.log(formdata);
    $.ajax({

        url: url,

        data: formdata,

        type: "post",

    }).done(function(res) {
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

$(document).on("click", ".edit-item-btn", function(e) {
    e.preventDefault();
    $.ajax({

        url: $(this).data('href'),

        data: { id: $(this).data("id") },

        type: "get",

    }).done(function(res) {
        $("#myModalHead").html("");
        $(".modal-title").text("Update Food Item");
        td = $("<td></td>");
        td.append($(res));
        $("#myModalBody").html("").append($(td));
        $('#myModal').modal('show');

    }.bind(this));
})

$(document).on("click", ".buying-details", function(e) {
    e.preventDefault();
    myModalHeadHtml = modalHeadHtml(['Date', 'Weight', 'Price Paid', 'Price/Kg', 'shop', 'brand'])
    $("#myModalHead").html($(myModalHeadHtml));

    $.ajax({

        url: $(this).data('href'),

        data: {

            food_item: $(this).data('item'),

        },

        type: "post",

    }).done(function(res) {

        myModalBodyHtml = '';



        lowestPricePerKg = 0;
        res.data.forEach(function(element) {


            pricePerKg = Math.round((element.price / element.weight * 1000 + Number.EPSILON) * 100) / 100
            if (lowestPricePerKg == 0) {
                lowestPricePerKg = pricePerKg;
            }
            if (lowestPricePerKg > pricePerKg) {
                lowestPricePerKg = pricePerKg;
            }

            if (lowestPricePerKg == pricePerKg)
                myModalBodyHtml += '<tr data-id="' + lowestPricePerKg + '">';
            else
                myModalBodyHtml += '<tr>';

            myModalBodyHtml += '<th><a href="javascript:void(0)" class="text-decoration-none" data-bs-toggle="modal"';
            myModalBodyHtml += 'data-bs-target="#exampleModal">' + element.created_at.substr(0, 10) + '</a></th>';
            myModalBodyHtml += '<td>' + element.weight + 'g</td>';
            myModalBodyHtml += '<td>RM' + element.price + '</td>';
            myModalBodyHtml += '<td>RM' + pricePerKg + '</td>';
            myModalBodyHtml += '<td>' + element.shop_name + '</td>';
            myModalBodyHtml += '<td>' + element.brand_name + '</td>';
            myModalBodyHtml += '</tr>';


        });


        $(".modal-title").text("Meal Item Report");
        $("#myModalBody").html($(myModalBodyHtml));
        $(document).find('[data-id="' + lowestPricePerKg + '"]').addClass("lowest-price")
        $('#myModal').modal({
            escapeClose: false,
            clickClose: false,
            showClose: false
        });


    })


})

$(document).on("click", ".delete-card", function(e) {
    if (confirm("are you sure to delete")) {
        $.ajax({
            url: $(this).data('href'),
            type: "get",

        }).done(function(res) {

        });
    }

})



/*
Settings and Configrations
*/
// Shop events


$(document).on("click", "#button-add-shop", function() {
    if ($("#" + $(this).data('input')).val() == "") {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-error" id="success-alert">';
        msg += '<strong>Error!</strong> Please Input Shop list';
        msg += '</div>';
        alert(msg);
        return;
    }
    submitInputData(this);
});


$(document).on("click", ".shop-delete-btn", function() {
    url = $(this).data("href");
    if (confirm("Are you sure to delete Shop")) {
        $.ajax({
            url: url,
            type: "get",
        }).done(function(res) {
            $(this).parent().parent().parent().parent().remove();
        }.bind(this));
    }
});

$(document).on("click", ".shop-edit-btn", function() {
    id = $(this).data("id");
    text = $(this).data("text");
    input = $(this).data("input");
    $("#" + input).val(text).data("id", id)
});


// Brand events


$(document).on("click", "#button-add-brand", function() {
    if ($("#" + $(this).data('input')).val() == "") {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-error" id="success-alert">';
        msg += '<strong>Error!</strong> Please Input brand list';
        msg += '</div>';
        alert(msg);
        return;
    }
    submitInputData(this);
});

$(document).on("click", ".brand-delete-btn", function() {
    url = $(this).data("href");
    if (confirm("Are you sure to delete brand")) {
        $.ajax({
            url: url,
            type: "get",

        }).done(function(res) {
            $(this).parent().parent().parent().parent().remove();
        }.bind(this));
    }
});

$(document).on("click", ".brand-edit-btn", function() {
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

    }).done(function(res) {
        msg = '<div style="z-index:9999" class="position-fixed text-center  w-100 p-3 alert alert-' + res.status + '" id="success-alert">';
        msg += res.msg;
        msg += '</div>';
        $(element).data("id", "");
        alert(msg);

        $.ajax({

            url: url,

            type: "get",

        }).done(function(res) {
            $("#content").html("").append(res);
        });


    });
}

function modalHeadHtml(cols) {
    myModalHeadHtml = '<tr>';
    cols.forEach(function(col) {
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

function alert(msg) {
    $(document.body).prepend(msg);

    $("#success-alert").alert();

    window.setTimeout(function() {

        $("#success-alert").alert('close');

    }, 2000);
}

$(document).on('click','#add_buy_meal_form_row', function (e) {
  e.preventDefault();
  var form_row = $(".form-row:first").clone();
  $(form_row).find("a.btn-danger").removeClass('d-none');
  $('#buy_meal_form .form-row:last').after(form_row);
});
$(document).on('click', '#jsRemoveFormRow', function (e) {
  e.preventDefault();
  $(this).parents('.form-row:not(:first-child)').remove();
});

// $(document).ready( function () {
//     $(document).on('click','#enterQuantityForm #submitForm',function (e) {
//         e.preventDefault();
//         var qName = $('#qName');
//         var qWeight = $('#qWeight');
//         var qPCS = $('#qPCS');
//         var qRM = $('#qRM');
//         var qQuantity = $('#qQuantity');
//         if(qName.val() === ''){
//           qName.parent('.form-col').addClass('has-error');
//         }else{
//           qName.parent('.form-col').removeClass('has-error');
//         }
//         if(qWeight.val() === ''){
//         qWeight.parent('.form-col').addClass('has-error');
//         }else{
//         qWeight.parent('.form-col').removeClass('has-error');
//         }
//         if(qPCS.val() === ''){
//           qPCS.parent('.form-col').addClass('has-error');
//         }else{
//           qPCS.parent('.form-col').removeClass('has-error');
//         }
//         if(qRM.val() === ''){
//         qRM.parent('.form-col').addClass('has-error');
//         }else{
//         qRM.parent('.form-col').removeClass('has-error');
//         }
//         if(qQuantity.val() === ''){
//         qQuantity.parent('.form-col').addClass('has-error');
//         }else{
//         qQuantity.parent('.form-col').removeClass('has-error');
//         }
//     });

//     $(document).on('keyup','#enterQuantityForm input',function(){
//         $(this).parent('.form-col').removeClass('has-error');
//     });
// } );

// override jquery validate plugin defaults

