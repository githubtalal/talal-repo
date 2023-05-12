

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" w3-red", "");
  }
  x[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " w3-red";
}
var slideIndex2 = 1;
      showDivs2(slideIndex2);

      function plusDivs2(n2) {
        showDivs2((slideIndex2 += n2));
      }

      function showDivs2(n2) {
        var i2;
        var x2 = document.getElementsByClassName("mySlides2");
        if (n2 > x2.length) {
          slideIndex2 = 1;
        }
        if (n2 < 1) {
          slideIndex2 = x2.length;
        }
        for (i2 = 0; i2 < x2.length; i2++) {
          x2[i2].style.display = "none";
        }
        x2[slideIndex2 - 1].style.display = "block";
      }
      var myIndex = 0;
      carousel();

      function carousel() {
        var i3;
        var x3 = document.getElementsByClassName("mySlides3");
        for (i3 = 0; i3 < x3.length; i3++) {
          x3[i3].style.display = "none";
        }
        myIndex++;
        if (myIndex > x3.length) {
          myIndex = 1;
        }
        x3[myIndex - 1].style.display = "block";
        setTimeout(carousel, 3500); // Change image every 2 seconds
      }

var myIndex2 = 0;
carousel2();

function carousel2() {
    var i4;
    var x4 = document.getElementsByClassName("mySlides4");
    for (i4 = 0; i4 < x4.length; i4++) {
        x4[i4].style.display = "none";
    }
    myIndex2++;
    if (myIndex2 > x4.length) {
        myIndex2 = 1;
    }
    x4[myIndex2 - 1].style.display = "block";
    setTimeout(carousel2, 3500); // Change image every 2 seconds
}
var slideIndex3 = 0;
showSlides3();

function showSlides3() {
    var i5;
    var slides5 = document.getElementsByClassName("mySlides5");
    for (i5 = 0; i5 < slides5.length; i5++) {
        slides5[i5].style.display = "none";
    }
    slideIndex3++;
    if (slideIndex3 > slides5.length) {
        slideIndex3 = 1;
    }
    slides5[slideIndex3 - 1].style.display = "block";
    setTimeout(showSlides3, 3500); // Change image every 2 seconds
}

      var slideIndexM = 1;
showDivsM(slideIndexM);

function plusDivsM(nM) {
  showDivsM(slideIndexM += nM);
}

function currentDivM(nM) {
  showDivsM(slideIndexM = nM);
}

function showDivsM(nM) {
  var iM;
  var xM = document.getElementsByClassName("mySlidesM");
  var dots = document.getElementsByClassName("demoM");
  if (nM > xM.length) {slideIndexM = 1}
  if (nM < 1) {slideIndexM = xM.length}
  for (iM = 0; iM < xM.length; iM++) {
    xM[iM].style.display = "none";
  }
  for (iM = 0; iM < dots.length; iM++) {
    dots[iM].className = dots[iM].className.replace(" w3-red", "");
  }
  xM[slideIndexM-1].style.display = "block";
  dots[slideIndexM-1].className += " w3-red";
}
window.addEventListener('load', () => {
  AOS.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  })
});
