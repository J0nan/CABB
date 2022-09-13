function submitForm(event) {
    event.preventDefault();
    setButtonLoading(document.getElementById("registerButton"));
    hideServerError();
    if (validateForm() && validatePasswords()) {
        register();
    } else {
        unSetButtonLoading(document.getElementById("registerButton"));
    }
}

function register() {
    let name = document.getElementById("name");
    let wallet = document.getElementById("wallet");
    let email = document.getElementById("email");
    let password = document.getElementById("password");

    try {
        let form = new FormData();
        form.append('name', name.value);
        form.append('wallet', wallet.value);
        form.append('email', email.value);
        form.append('password', password.value);
        const address = '/api/register';
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
                if (status == 201) {
                    console.log(data)
                    window.location.replace(URI_POST_REGISTER);
                } else if(status == 422) {
                    formError(data);
                    unSetButtonLoading(document.getElementById("registerButton"));
                } else {
                    showServerError(status,data.statusText);
                    unSetButtonLoading(document.getElementById("registerButton"));
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
    let name = document.getElementById("name");
    let wallet = document.getElementById("wallet");
    let email = document.getElementById("email");
    let password = document.getElementById("password");
    let password2 = document.getElementById("password2");
    let valid = true;
    if (!password2.value) {
        setInvalid(password2, 'password2');
        password2.focus();
        valid = false;
    } else {
        unSetInvalid(password2);
    }
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
    if (!wallet.value) {
        setInvalid(wallet, 'wallet');
        wallet.focus();
        valid = false;
    } else {
        unSetInvalid(wallet);
    }
    if (!name.value) {
        setInvalid(name, 'name');
        name.focus();
        valid = false;
    } else {
        unSetInvalid(name);
    }
    return valid;
}

function validatePasswords() {
    let password = document.getElementById("password");
    let password2 = document.getElementById("password2");
    let valid = true;
    if (password2.value != password.value) {
        console.log(password2.value);
        console.log(password.value);
        setInvalid(password2, 'password2', 'Passwords must match');
        setInvalid(password, 'password', 'Passwords must match');
        password.focus();
        valid = false;
    } else {
        unSetInvalid(password);
        unSetInvalid(password2);
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