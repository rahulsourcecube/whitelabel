$('#employeeform').validate({
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
        role: {
            required: true
        },

        password: {
            minlength: 8,
            maxlength: 30,
            required: true,
        },
        cpassword: {
            required: true,
            equalTo: "#password",
        },

    },
    messages: {
        fname: {
            required: "Please enter first name "
        },
        lname: {
            required: "Please enter last name "
        },
        email: {
            required: "Please enter email address"
        },
        role: {
            required: "Please select role"
        },

        password: {
            required: "Please enter password",
        },
        cpassword: {
            required: "Please enter confirm password",
            equalTo: "The password you entered does not match.",
        },
    }
});
