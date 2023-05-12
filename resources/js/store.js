const fp = require('@fingerprintjs/fingerprintjs');

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return 0;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function createButton(imgSrc) {
    let outerDiv = document.createElement('div');
    outerDiv.style.position = 'fixed';
    outerDiv.style.bottom = '2%';
    outerDiv.style.left = '3%';
    outerDiv.style.zIndex = '1000';
    let button = document.createElement('button');
    button.addEventListener('focus',function (){
        this.style.outline = 'none'
    });
    button.style.width = '120px';
    button.style.height = '120px';
    button.style.color = 'white';
    // button.style.borderRadius = '49% 49% 49% 10px';
    button.style.border = '0';
    button.style.backgroundColor = 'transparent'; //#035AA7
    button.style.fontSize = '12px';
    button.style.fontWeight = 'bold';
    button.style.borderColor = 'none'; //rgb(3 90 167 / 0%)
    let image = document.createElement('img');

    image.src = imgSrc;
    image.style.width = '100px';
    // image.innerHTML = `<svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16"> <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" fill="white"></path>` +
    //     `<br>أشتري الآن`;
    let paragraph = document.createElement('p');
    paragraph.style.color = 'black';
    paragraph.style.fontWeight = 'bold';
    paragraph.style.fontSize = '12px';
    paragraph.style.margin = '0';
    paragraph.style.textAlign = 'center';
    paragraph.textContent = 'Powered By eCart';
    button.append(image);

    outerDiv.append(button);

    outerDiv.append(paragraph);

    return {
        button,
        outerDiv,
        paragraph,
    }

}


window.Sallaty = {
    BASE_INIT_URL: new URL('/api/init',process.env.MIX_APP_URL).href,
    BASE_SHOP_URL: new URL('/store-init',process.env.MIX_APP_URL).href,
    init(opts = {
        token: undefined,
    }) {
        if (!opts.token) throw "Missing options";
        const self = this;
        const fpPromise = fp.load();

        // Get the visitor identifier when you need it.
        fpPromise
            .then(fp => fp.get())
            .then(result => {
                // This is the visitor identifier:
                const visitorId = result.visitorId;
                let url = new URL(self.BASE_INIT_URL);
                url.searchParams.append('token', opts.token);
                url.searchParams.append('user_id', visitorId);
                url.searchParams.append('user_token', readCookie('User-Id'));
                let {outerDiv, button, paragraph} = createButton();

                fetch(url)
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            console.log(response);
                            createCookie('User-Id', response.user_id);
                            let btn = document.createElement("button");
                            const {outerDiv, button, paragraph} = createButton(response.button_style);
                            button.onclick =
                            button.onclick = function () {
                                let url = new URL(self.BASE_SHOP_URL);
                                url.searchParams.append('guest', response.user_id);
                                let width = 800;
                                let height = 800;
                                var left = (screen.width / 2) - (width / 2);
                                var top = (screen.height / 2) - (height / 2);
                                let newWindow = window.open(url, 'eCart', `height=${height},width=${width},top=${top},left=${left},toolbar=no, location=no,dialog=yes,resizable=no`);
                                if (window.focus)
                                    newWindow.focus();
                            };
                            let element = document.querySelector('body');
                            if (element) {
                                element.append(outerDiv);
                            }
                            // document.querySelector(opts.container).append(btn);
                        }
                    });
            })
    }
};
