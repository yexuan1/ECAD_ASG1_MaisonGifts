const btn = document.querySelector('.btn');
const star = document.querySelector('.star');
const wrp = document.querySelector('.wrapper');

btn.addEventListener('click',(e)=>{
    e.preventDefault();

    wrp.style.display = "block";
    star.style.display = "none";

});
