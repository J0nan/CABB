const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function submitForm(event) {
    hideServerError();
    event.preventDefault();
    if (validateForm()) {
        uploadFile();
    }
}

function uploadFile() {
    let uploadFileButton = document.getElementById("uploadFileButton");
    let file = document.getElementById("file").files[0];
    setButtonLoading(uploadFileButton);
    try {
        let form = new FormData();
        form.append('file', file);
        const address = '/api/file/verify';
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
                if (status == 200) {
                    document.getElementById('fileName').innerHTML=data.fileInfo[0].fileName;
                    document.getElementById('fileSize').innerHTML=data.fileInfo[0].fileSize.value;
                    document.getElementById('fileHash').innerHTML=data.fileInfo[1].fileHash;
                    date = new Date(parseInt(data.fileInfo[0].fileCreatedAt.value)*1000);
                    dateString = days[date.getDay()]+" "+date.getDate()+" of "+months[date.getMonth()]+" at "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
                    document.getElementById('uploadedDate').innerHTML = dateString;
                    document.getElementById('authName').innerHTML=data.fileInfo[0].authName;
                    document.getElementById('authEmail').innerHTML=data.fileInfo[0].authEmail;
                    document.getElementById('authWallet').innerHTML=data.fileInfo[0].authWallet;
                    // document.getElementById('numberCurrentOwners').innerHTML=data.fileInfo.numberCurrentOwners.value;
                    // document.getElementById('numberOldOwners').innerHTML=data.fileInfo[0].numberOldOwners.value;
                    setCurrentOwners(data.fileInfo[1].fileHash, data.fileInfo[0].numberCurrentOwners.value)
                    if( data.fileInfo[0].numberOldOwners.value > 0){
                        setOldOwners(data.fileInfo[1].fileHash, data.fileInfo[0].numberOldOwners.value)
                    } else {
                        document.getElementById('oldOwners').innerHTML=data.fileInfo[0].numberOldOwners.value;
                    }
                    hideElement(document.getElementById("fileVerification"));
                    showElement(document.getElementById("fileInformation"));
                } else if(status == 422) {
                    formError(data);
                } else if(status == 413){
                    fileToBig();
                } else {
                    console.error(data);
                    showServerError(status,data.error);
                }
                unSetButtonLoading(uploadFileButton);
            })
            .catch(err =>{
                console.error(err);
                unSetButtonLoading(uploadFileButton);
            });
    } catch (err) {
        console.error(err);
        unSetButtonLoading(uploadFileButton);
    }
}

function setCurrentOwners(hash, number) {
    try {
        let form = new FormData();
        form.append('fileHash', hash);
        form.append('numberOwners', number);
        const address = '/api/file/verify/currentOwners';
        var status = 500;
        fetch(address, {
            method: 'POST',
            body: form
            })
            .then(response => {
                status = response.status;
                return response.json();
            })
            .then(data => {
                if (status == 200) {
                    let html = "";
                    data.currentOwners.forEach(owner => {
                        html += getCurrentOwnerAccordion(owner);
                    });
                    document.getElementById('currentOwners').innerHTML=html;
                } else {
                    console.error(data);
                    showServerError(status,data.error);
                }
            })
            .catch(err =>{
                console.error(err);
                unSetButtonLoading(uploadFileButton);
            });
    } catch (err) {
        console.error(err);
        unSetButtonLoading(uploadFileButton);
    }
}

function getCurrentOwnerAccordion(owner) {
    let id = Math.floor(Math.random() * 10000);
    let html = "<div class=\"accordion\" id=\"accordionCurrentOwner\"></div>";
        html +=   "<div class=\"accordion-item\">";
        html +=      "<h2 class=\"accordion-header\" id=\"heading"+owner.legalId+id+"\">";
        html +=         "<button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse"+owner.legalId+id+"\" aria-expanded=\"false\" aria-controls=\"collapse"+owner.legalId+id+"\" data-bcup-haslogintext=\"no\">";
        html +=             owner.name;
        html +=         "</button>";
        html +=      "</h2>";
        html +=      "<div id=\"collapse"+owner.legalId+id+"\" class=\"accordion-collapse collapse\" aria-labelledby=\"heading"+owner.legalId+id+"\" data-bs-parent=\"#accordionExample\">";
        html +=         "<div class=\"accordion-body\">";
        html +=            "<p>Name: "+owner.name+"</p>"
        html +=            "<p>Surname: "+owner.surname+"</p>"
        html +=            "<p>Legal ID: "+owner.legalId+"</p>"
        date = new Date(parseInt(owner.startDate.value)*1000);
        dateString = days[date.getDay()]+" "+date.getDate()+" of "+months[date.getMonth()]+" at "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        html +=            "<p>Owner Since: "+dateString+"</p>"
        html +=            "<p>Authenticator Name: "+owner.authName+"</p>"
        html +=            "<p>Authenticator Email: "+owner.authEmail+"</p>"
        html +=            "<p>Authenticator Wallet: "+owner.authWallet+"</p>"
        html +=         "</div>";
        html +=      "</div>";
        html +=   "</div>";
        html += "</div>";
    return html;
}

function setOldOwners(hash, number) {
    try {
        let form = new FormData();
        form.append('fileHash', hash);
        form.append('numberOwners', number);
        const address = '/api/file/verify/oldOwners';
        var status = 500;
        fetch(address, {
            method: 'POST',
            body: form
            })
            .then(response => {
                status = response.status;
                return response.json();
            })
            .then(data => {
                if (status == 200) {
                    let html = "";
                    data.oldOwners.forEach(owner => {
                        html += getOldOwnerAccordion(owner);
                    });
                    document.getElementById('oldOwners').innerHTML=html;
                } else {
                    console.error(data);
                    showServerError(status,data.error);
                }
            })
            .catch(err =>{
                console.error(err);
                unSetButtonLoading(uploadFileButton);
            });
    } catch (err) {
        console.error(err);
        unSetButtonLoading(uploadFileButton);
    }
}

function getOldOwnerAccordion(owner) {
    let id = Math.floor(Math.random() * 10000);
    let html = "<div class=\"accordion\" id=\"accordionOldOwners\"></div>";
        html +=   "<div class=\"accordion-item\">";
        html +=      "<h2 class=\"accordion-header\" id=\"heading"+owner.legalId+id+"\">";
        html +=         "<button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse"+owner.legalId+id+"\" aria-expanded=\"false\" aria-controls=\"collapse"+owner.legalId+id+"\" data-bcup-haslogintext=\"no\">";
        html +=             owner.name;
        html +=         "</button>";
        html +=      "</h2>";
        html +=      "<div id=\"collapse"+owner.legalId+id+"\" class=\"accordion-collapse collapse\" aria-labelledby=\"heading"+owner.legalId+id+"\" data-bs-parent=\"#accordionExample\">";
        html +=         "<div class=\"accordion-body\">";
        html +=            "<p>Name: "+owner.name+"</p>"
        html +=            "<p>Surname: "+owner.surname+"</p>"
        html +=            "<p>Legal ID: "+owner.legalId+"</p>"
        date = new Date(parseInt(owner.startDate.value)*1000);
        dateString = days[date.getDay()]+" "+date.getDate()+" of "+months[date.getMonth()]+" at "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        html +=            "<p>Owner From: "+dateString+"</p>"
        date = new Date(parseInt(owner.endDate.value)*1000);
        dateString = days[date.getDay()]+" "+date.getDate()+" of "+months[date.getMonth()]+" at "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        html +=            "<p>Owner To: "+dateString+"</p>"
        html +=            "<p>Authenticator Name: "+owner.authName+"</p>"
        html +=            "<p>Authenticator Email: "+owner.authEmail+"</p>"
        html +=            "<p>Authenticator Wallet: "+owner.authWallet+"</p>"
        html +=         "</div>";
        html +=      "</div>";
        html +=   "</div>";
        html += "</div>";
    return html;
}

function validateForm() {
    let file = document.getElementById("file");
    let valid = true;
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

function showServerError(status, error) {
    document.getElementById("statusError").innerHTML = "Error "+status;
    document.getElementById("textError").innerHTML = error;
    document.getElementById("serverError").style.display = "block";
}

function hideServerError() {
    document.getElementById("serverError").style.display = "none";
}

function verifyNewFile() {
    document.getElementById("file").value="";
    document.getElementById("currentOwners").innerHTML="<div class=\"spinner-grow\" role=\"status\"> <span class=\"sr-only\"></span> </div>";
    document.getElementById("oldOwners").innerHTML="<div class=\"spinner-grow\" role=\"status\"> <span class=\"sr-only\"></span> </div>";
    hideElement(document.getElementById("fileInformation"));
    showElement(document.getElementById("fileVerification"));
}

function hideElement(element) {
    element.style.display = "none";
}

function showElement(element) {
    element.style.display = "block";
}
