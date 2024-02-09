$(document).on("click","#addUser",function(){
    $.validator.addMethod("email", function(value, element) {
        return this.optional(element) ||
            /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
            .test(value);
    }, "Please enter a valid email id");
$('#userform').validate({
    rules: {
        fname: {
            required: true
        },
        lname: {
            required: true
        },
        email: {
            required: true,
            email: true,
            remote: {
                url: emailCheckUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                },
            }
        },
        number: {
            required: true,
            digits: true,
            remote: {
                url: numberCheckUrl,
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": token
                },
            }
        },
        password: {
            minlength: 8,
            maxlength: 50,
            required: true,
        },
        password_confirmation: {
            required: true,
            equalTo: "#password",
        },
        image: {
            fileExtension: true,
            fileSize: true,
        },
    },
    messages: {
        fname: {
            required: "Please enter first name  "
        },
        lname: {
            required: "Please enter last name "
        },
        email: {
            required: "Please enter email address",
            email: "Please enter valid email address.",
            remote: "Email already registered."
        },
        number: {
            required: "Please mobile number address",
            digits: "Please enter valid contact number",
            remote: " Mobile Number already registered."
        },
        password: {
            required: "Please enter password",
        },
        password_confirmation: {
            required: "Please enter confirm password",
            equalTo: "The password you entered does not match.",
        },
    }
});
});

$(document).on("click","#updateUser",function(){
    $.validator.addMethod("email", function(value, element) {
        return this.optional(element) ||
            /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
            .test(value);
    }, "Please enter a valid email id");
$('#userUpdateform').validate({
    rules: {
        fname: {
            required: true
        },
        lname: {
            required: true
        },
        email: {
            required: true,
            email: true,
            remote: {
                url: emailCheckUrl,
                type: "post",
                data:{'email':$("#email").val(),'id':$("#id").val()},
                headers: {
                    "X-CSRF-TOKEN": token
                },
            }
        },
        number: {
            required: true,
            remote: {
                url: numberCheckUrl,
                type: "post",
                data:{'number':$("#number").val(),'id':$("#id").val()},
                headers: {
                    "X-CSRF-TOKEN": token
                },
            }
        },
        password: {
            minlength: 8,
            maxlength: 50,
        },
        password_confirmation: {
            equalTo: "#password",
            required: function() {
                if ($('#password').val() !== "") {
                    return true;
                } else {
                    return false;
                }

            }
        },
        image: {
            fileExtension: true,
            fileSize: true,
        },
    },
    messages: {
        fname: {
            required: "Please enter first name  "
        },
        lname: {
            required: "Please enter last name "
        },
        email: {
            required: "Please enter email address",
            email: "Please enter valid email address.",
            remote: "Email already registered."
        },
        number: {
            required: "Please mobile number address",
            remote: "Mobile Number already registered."
        },
        password: {
            required: "Please enter password",
        },
        password_confirmation: {
            required: "Please enter confirm password",
            equalTo: "The password you entered does not match.",
        },
    }
});
});


isFreePackage();

$(document).on("change", '#inputype', function() {
    type = $(this).val();
    isFreePackage();

    if (type == '1') {
        $('.day_title').html('No Of Day');
        $(".day_place").attr("placeholder", "No Of Day").placeholder();
    } else if (type == '2') {
        $('.day_title').html('No Of Month');
        $(".day_place").attr("placeholder", "No Of Month").placeholder();

    } else {
        $('.day_title').html('No Of Year');
        $(".day_place").attr("placeholder", "No Of Year").placeholder();
    }
})

function isFreePackage() {

    if ($("#inputype option:selected").val() == '1') {
        $("#price-section").hide();
        $("#price").val("0");
    } else {
        $("#price-section").show();
        $("#price").val("");
    }
}
