function addMoreBills() {
    // Create a clone of element with id ddl_1:
    $(".first-single").find('#exampleFormControlSelect2').select2('destroy')
    $(".first-single").find('#exampleFormControlSelect22').select2('destroy')

    let clone = document
        .querySelector(".single-billing")
        .cloneNode(true);
        clone.classList.remove('first-single')
    clone.querySelector('#exampleFormControlSelect2').removeAttribute('id');
    clone.querySelector('#exampleFormControlSelect22').removeAttribute('id');
        $(".first-single").find('#exampleFormControlSelect2').select2({})
        $(".first-single").find('#exampleFormControlSelect22').select2({})
    let before = document.querySelector(".container-billing .add-billing-btn");
    // Append the newly created element on element p
    //  console.log(clone);

    let allInputs = clone.querySelectorAll("input");
    allInputs.forEach((input) => {
        input.value = null;
    });
    let removeBtn = document.createElement("button");
    removeBtn.classList.add( "remove-hour-btn");
    removeBtn.innerHTML = "<img src='../images/iconmonstr-plus-6-red.svg' alt='usuń godzinę'>";
    removeBtn.setAttribute("type", "button");


    clone.appendChild(removeBtn);
    removeBtn.addEventListener("click", () => {
        removeBilling(removeBtn.parentNode);
    });
    before.parentNode.insertBefore(clone, before);
// console.log()
    // $(".single-billing:last-of-type").find('select').select2()
    $(".single-billing:last-of-type").find('select:not(#categoryID)').select2()
    // $(".single-billing:last-of-type").find('#exampleFormControlSelect22').select2()

}

function removeBilling(parentOfBtn) {
    parentOfBtn.remove();
}
const wrap = document.querySelector(".container-billing");
if (wrap) {
    let addBillBtn = document.querySelector(".add-billing-btn");
    let removeHoursBtns = null;
    addBillBtn.addEventListener("click", () => {
        addMoreBills();
    });
}

//button aktualizuj

const editBtn = document.querySelector(".able-to-edit ");
const updateBtn = document.querySelector(".update-billing-btn");
let readonlyInputs = document.querySelectorAll(".container-fluid *[readonly]");
// console.log(readonlyBtns);
if (editBtn) {
    editBtn.addEventListener("click", () => {
        readonlyInputs.forEach((input) => {
            input.removeAttribute("readonly");
            input.removeAttribute("disabled");
        });
        updateBtn.classList.add("active-update");
    });
}
