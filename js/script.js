// Hashes sensitive data
async function hashData(data) {
    const encoder = new TextEncoder();
    const hashBuffer = await crypto.subtle.digest('SHA-512', encoder.encode(data));
    return Array.from(new Uint8Array(hashBuffer))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
}

// Submits the data sent by forms.
async function submitFormData(prefix,formData) {
    var sendpath = 'http://localhost/ProjectoAPISOSLink/users/' + prefix;
    var object = {};
    formData.forEach(function(value, key){
        object[key] = value;
    });
    var json = JSON.stringify(object);
    $.ajax({
        type: "POST",
        url: sendpath,
        data: json,
        processData: false,
        contentType: 'application/json',
        success: function(response) {
            console.log(response)
            if(response.status == 'success') {
                $('#display').removeClass().addClass('display_success');
                $('#display').text(response.message);
            } else {
                $('#display').removeClass().addClass('display_error');
                $('#display').text("Error: " + response.message);
            }
        }
    })
}

// Checks for the sending of the form SignIn (from the frontend).
$(document).on('submit', '#signIn_form', async function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    submitFormData('signIn',formData);
})

// Checks for the sending of the form SignUp (from the frontend).
$(document).on('submit', '#signUp_form', async function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    submitFormData('signUp',formData);
})

// Checks for the established limits of the input data and whitespace in passwords.
function limitLength(event,element,maxLength) {
    if(element.value.length > maxLength) {
        event.preventDefault();
        window.alert("Input limit was achieved.");
    }

    var key = event.keyCode;
    if(key===32 && element.type == 'password') {
        event.preventDefault();
        window.alert("Passwords can only have alphanumeric and symbolic characters.");
    }
}