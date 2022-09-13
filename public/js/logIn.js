function submitForm(event) {
    event.preventDefault();
    setButtonLoading(document.getElementById("logInButton"));
    hideServerError();
    if (validateForm()) {
        logIn();
    } else {
        unSetButtonLoading(document.getElementById("logInButton"));
    }
}

function logIn() {
    let email = document.getElementById("email");
    let password = document.getElementById("password");

    try {
        let form = new FormData();
        form.append('email', email.value);
        form.append('password', password.value);
        const address = '/api/login';
        var status = 500;
        fetch(address, {
            method: 'POST',
            body: form,
            })
            .then(response => {
                status = response.status;
                if (status != 500) {
                    return response.json();
                } else {
                    return response;
                }
            })
            .then(data => {
                if (status == 200) {
                    console.log(data)
                    window.location.replace(URI_POST_LOG_IN);
                } else if(status == 422) {
                    formError(data);
                    unSetButtonLoading(document.getElementById("logInButton"));
                } else if(status == 401) {
                    email = document.getElementById('email');
                    setInvalid(email, 'email', '');
                    password = document.getElementById('password');
                    setInvalid(password, 'password', '');
                    showServerError(status,'Invalid credentials');
                    unSetButtonLoading(document.getElementById("logInButton"));
                } else {
                    showServerError(status,data.statusText);
                    unSetButtonLoading(document.getElementById("logInButton"));
                }
            })
            .catch(err =>{
                console.error(err);
            });
    } catch (err) {
        console.error(err);
    }
}

function validateForm() {
    let email = document.getElementById("email");
    let password = document.getElementById("password");
    let valid = true;
    if (!password.value) {
        setInvalid(password, 'password');
        password.focus();
        valid = false;
    } else {
        unSetInvalid(password);
    }
    if (!email.value) {
        setInvalid(email, 'email');
        email.focus();
        valid = false;
    } else {
        unSetInvalid(email);
    }
    return valid;
}

function formError(data) {
    Object.keys(data).forEach(key => {
        element = document.getElementById(key);
        error = data[key][0].split('.')[1].replaceAll('_',' ');
        setInvalid(element, key, error);
    })
}

function setInvalid(element, elementName, text='Field must not be empty') {
    document.getElementById(elementName+"ErrorInfo").innerHTML=text;
    element.classList.add('is-invalid');
}

function unSetInvalid(element) {
    element.classList.remove('is-invalid');
}

function setButtonLoading(button) {
    let html = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp Loading...';
    button.innerHTML = html;
    button.disabled = true;
}

function unSetButtonLoading(button) {
    button.innerHTML = "Log In";
    button.disabled = false;
}

function showServerError(status, error) {
    document.getElementById("statusError").innerHTML = "Error "+status;
    document.getElementById("textError").innerHTML = error;
    document.getElementById("serverError").style.display = "block";
}

function hideServerError() {
    document.getElementById("serverError").style.display = "none";
}