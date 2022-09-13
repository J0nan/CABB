var AUTHENTICATED;

function getCookie(cname) {
    var name = cname + "=";
    var ca = decodeURIComponent(document.cookie).split(';');
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return false;
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function redirectLogIn() {
    window.location.replace(URI_LOGIN);
}

function checkAuthentication(redirect) {
    if (!getCookie(AUTH_COOKIE)) {
        if (redirect) {
            redirectLogIn()
        } else {
            console.log("No user logged in")
            AUTHENTICATED=false;
        }
    } else {
        console.log("User logged in")
        AUTHENTICATED=true
    }
}

function logOut() {
    const address = '/api/logout';
    fetch(address, {
        method: 'GET'
        })
        .then(response => {
            status = response.status;
            if (status == 200) {
                redirectLogIn();
            } else if (status == 401){
                deleteAllCookies();
                redirectLogIn();
            } else {
                console.error(response);
            }
        });
}