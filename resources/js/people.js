const { xor } = require("lodash")

const activeBtns= document.querySelectorAll('.slider-category__button')
const statusInput=document.querySelector('#status')
if(statusInput){
    activeBtns.forEach(btn=>{
        btn.addEventListener('click',()=>{
            const active = document.querySelector('.slider-category-active')
            active.classList.remove('slider-category-active')
            btn.classList.add('slider-category-active')
            if(btn.getAttribute('data-status') === 'active'){
                statusInput.value='1'
            }else{
                statusInput.value='0'
            }
        })
    })

}
