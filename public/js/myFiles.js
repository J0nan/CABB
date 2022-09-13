const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function getInfo() {
    try {
        const address = '/api/user/files';
        var status = 500;
        fetch(address, {
            method: 'GET'
            })
            .then(response => {
                status = response.status;
                return response.json();
            })
            .then(data => {
                if (status == 200) {
                    setFiles(data.data.files)
                } else {
                    console.error(data);
                }
            })
            .catch(err =>{
                console.error(err);
            });
    } catch (err) {
        console.error(err);
    }
}

function setFiles(files) {
    html = "";
    console.log(files)
    files.forEach(file => {
        html += getFileAccordion(file)
    });
    if (html) {
        document.getElementById('myFiles').innerHTML=html;
    } else {
        document.getElementById('myFiles').innerHTML="<legend>No Files Uploaded</legend>";
    }
}

function getFileAccordion(file) {
    // let id = Math.floor(Math.random() * 10000);
    let html = "<div class=\"accordion\" id=\"accordionFile\"></div>";
        html +=   "<div class=\"accordion-item\">";
        html +=      "<h2 class=\"accordion-header\" id=\"heading"+file.id+"\">";
        html +=         "<button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse"+file.id+"\" aria-expanded=\"false\" aria-controls=\"collapse"+file.id+"\" data-bcup-haslogintext=\"no\">";
        html +=             file.file_name;
        html +=         "</button>";
        html +=      "</h2>";
        html +=      "<div id=\"collapse"+file.id+"\" class=\"accordion-collapse collapse\" aria-labelledby=\"heading"+file.id+"\" data-bs-parent=\"#accordionExample\">";
        html +=         "<div class=\"accordion-body\">";
        html +=            "<p>Name: "+file.file_name+"</p>"
        html +=            "<p>File Size: "+file.file_size+"</p>"
        html +=            "<p>File Hash: "+file.hash+"</p>"
        html +=            "<p>Created at: "+file.created_at+"</p>"
        html +=         "</div>";
        html +=      "</div>";
        html +=   "</div>";
        html += "</div>";
    return html;
}