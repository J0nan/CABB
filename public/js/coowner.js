var uploadFileModal

function submitForm(event) {
    hideServerError();
    event.preventDefault();
    if (validateForm()) {
        showConfirmation()
    }
}

function uploadFile() {
    let uploadFileModalPrimaryBtn = document.getElementById("uploadFileModalPrimaryBtn");
    let file = document.getElementById("file").files[0];
    let name = document.getElementById("name").value;
    let surname = document.getElementById("surname").value;
    let legalId = document.getElementById("legalId").value;

    setButtonLoading(uploadFileModalPrimaryBtn);
    try {
        let form = new FormData();
        form.append('file', file);
        form.append('name', name.toString());
        form.append('surname', surname.toString());
        form.append('legalId', legalId.toString());
        const address = '/api/file/coOwner';
        var status = 500;
        fetch(address, {
            method: 'POST',
            body: form
            })
            .then(response => {
                status = response.status;
                if (status != 413) {
                    return response.json();
                } else {
                    return response;
                }
            })
            .then(data => {
                unSetButtonLoading(uploadFileModalPrimaryBtn);
                uploadFileModal.hide();
                if (status == 200) {
                    document.getElementById('fileUploadForm').style.display='none'
                    document.getElementById('spinner').style.display='block'
                    window.location.replace(data.data.links.successful.uri);
                } else if(status == 422) {
                    formError(data);
                } else if(status == 413){
                    fileToBig();
                } else if(status == 401){
                    deleteAllCookies();
                    alert("User not logged in. \n\nPlease Log In again.")
                    redirectLogIn();
                } else {
                    console.error(data);
                    showServerError(status,data.error);
                }
            })
            .catch(err =>{
                console.error(err);
                unSetButtonLoading(uploadFileModalPrimaryBtn);
                uploadFileModal.hide();
                showServerError(500, "Internal server error");
            });
    } catch (err) {
        console.error(err);
        unSetButtonLoading(uploadFileModalPrimaryBtn);
        uploadFileModal.hide();
        showServerError(500, "Internal server error");
    }
}

function showConfirmation() {
    let modelBody = "<p><b>Owner name:</b> " + document.getElementById("name").value + "</p>";
    modelBody += "<p><b>Owner surname:</b> " + document.getElementById("surname").value + "</p>";
    modelBody += "<p><b>Owner legal ID:</b> " + document.getElementById("legalId").value + "</p>";
    modelBody += "<p><b>File name:</b> " + document.getElementById("file").files[0].name + "</p>";
    showUploadFileModal('Confirm transaction',modelBody);

}

function validateForm() {
    let file = document.getElementById("file");
    let name = document.getElementById("name");
    let surname = document.getElementById("surname");
    let legalId = document.getElementById("legalId");
    let valid = true;
    if (!legalId.value) {
        setInvalid(legalId, 'legalId');
        legalId.focus();
        valid = false;
    } else {
        unSetInvalid(legalId);
    }
    if (!surname.value) {
        setInvalid(surname, 'surname');
        surname.focus();
        valid = false;
    } else {
        unSetInvalid(surname);
    }
    if (!name.value) {
        setInvalid(name, 'name');
        name.focus();
        valid = false;
    } else {
        unSetInvalid(name);
    }
    if (!file.files.length) {
        setInvalid(file, 'file', 'One file must be selected');
        file.focus();
        valid = false;
    } else {
        unSetInvalid(file);
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

function fileToBig() {
    element = document.getElementById('file');
    setInvalid(element, 'file', 'File is to big, it must be lower than 10 MB');
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
    button.innerHTML = "Submit";
    button.disabled = false;
}

function showUploadFileModal(title, body, primaryBtn = 'Confirm', secondaryBtn = 'Cancel') {
    document.getElementById('uploadFileModalTitle').innerHTML=title;
    document.getElementById('uploadFileModalBody').innerHTML=body;
    document.getElementById('uploadFileModalSecondaryBtn').innerHTML=secondaryBtn;
    document.getElementById('uploadFileModalPrimaryBtn').innerHTML=primaryBtn;
    uploadFileModal = new bootstrap.Modal(document.getElementById("uploadFileModal"));
    uploadFileModal.show();
}

function showServerError(status, error) {
    document.getElementById("statusError").innerHTML = "Error "+status;
    document.getElementById("textError").innerHTML = error;
    document.getElementById("serverError").style.display = "block";
}

function hideServerError() {
    document.getElementById("serverError").style.display = "none";
}