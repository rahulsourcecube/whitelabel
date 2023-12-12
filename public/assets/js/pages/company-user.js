$('#userform').validate({
    rules: {
        fname: {
            required: true
        },
        lname: {
            required: true
        },
        email: {
            required: true
        },
        number: {
            required: true
        },
        password: {
            minlength: 8,
            maxlength: 30,
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
            required: "Please enter email address"
        },
        number: {
            required: "Please mobile number address"
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