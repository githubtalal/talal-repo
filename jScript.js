window.onscroll = function () {
    let tag = document.querySelectorAll('.container3 .tag').forEach(function (elem, index, arr) {
        if (window.scrollY >= elem.offsetTop - elem.clientHeight - 100) {
            elem.firstChild.nextSibling.classList.add('rotate')
        } else if (window.scrollY <= elem.offsetTop - elem.clientHeight){
            elem.firstChild.nextSibling.classList.remove('rotate')
        }
    })
}


var navBtns = document.querySelectorAll("nav .btn-container button").forEach(function (elem, index, arr) {
    elem.addEventListener('click', function () {
        document.querySelectorAll("nav .btn-container button").forEach(function (elem) {
            if (elem.classList.contains('active'))
                elem.classList.remove('active')
        })
        this.classList.add('active')
    })
})

document.querySelectorAll('.third-container .container > div:last-of-type img').forEach(function (elem, index, arr) {
    elem.addEventListener('click', function () {
        document.getElementById(this.getAttribute('href').substr(1)).classList.toggle('d-none')
        if (this.getAttribute('src') === 'images/minus.png')
            this.setAttribute('src', 'images/plus.png')
        else
            this.setAttribute('src', 'images/minus.png')
    })
})

document.querySelector('button.contact-us').addEventListener("click", function () {
    console.log('clicked')
    document.location.pathname = "E:\\الاستاذ احمد دهده\\task3\\contactUs.html"
})

