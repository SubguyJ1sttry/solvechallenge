// navigation bar
var header = document.querySelector('header');
window.onscroll = function () { 
   if (window.scrollY > 0) {
      header.classList.add('fixed');
   } 
   else {
      header.classList.remove('fixed');
   }
};

// mobile navigation
var burger = document.querySelector('#burger');
var menu = document.querySelector('#navbar');
var overlay = document.querySelector('#overlay');

function toggleMobileNav() {

   if (!burger.classList.contains('active')) {
      burger.classList.add('active');
      menu.classList.add('open');
      overlay.classList.add('show');
      document.body.classList.add('overflow_hidden');
   } 
   else {
      burger.classList.remove('active');
      menu.classList.remove('open');
      overlay.classList.remove('show');
      document.body.classList.remove('overflow_hidden');
   }
}

burger.onclick = function () {
   toggleMobileNav();
}

overlay.onclick = function () {
   toggleMobileNav();
}

async function copyToClipboard(id, prefix="", suffix="") {
   var text = prefix + document.getElementById(id).innerHTML + suffix;
   try {
      await navigator.clipboard.writeText(text);
   } catch (err) {
      console.error('Failed to copy: ', err);
   }
}

function redirectToPath(location) {
   switch (location) {
      case "payment-search":
         const payCode = document.getElementById("payCode").value;
         window.location.href = `/pay/${payCode}`;
         return true;
      default:
         return false;
   }
}