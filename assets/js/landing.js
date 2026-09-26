AOS.init({

    duration:900,
    once:true,
    easing:"ease-in-out"

});

window.addEventListener("scroll",function(){
    const navbar=document.querySelector(".navbar-custom");
    if(window.scrollY>60){
        navbar.classList.add("navbar-scrolled");
    }else{
        navbar.classList.remove("navbar-scrolled");
    }
});

const topBtn=document.getElementById("backTop");

window.addEventListener("scroll",()=>{
    if(window.scrollY>400){
        topBtn.classList.add("show");
    }else{
        topBtn.classList.remove("show");
    }
});

topBtn.onclick=()=>{
    window.scrollTo({
        top:0,
        behavior:"smooth"
    });
};

const sections=document.querySelectorAll("section");

const navLinks=document.querySelectorAll(".nav-link");

window.addEventListener("scroll",()=>{
    let current="";

    sections.forEach(section=>{
        const top=section.offsetTop-150;
        const height=section.clientHeight;
        if(pageYOffset>=top){
            current=section.getAttribute("id");
        }
    });

    navLinks.forEach(link=>{
        link.classList.remove("active");
        if(link.getAttribute("href")==="#"+current){
            link.classList.add("active");
        }
    });

});