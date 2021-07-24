const { remove, cloneDeep } = require("lodash");
document.addEventListener("DOMContentLoaded", () => {
    function addMoreHours() {
        $(".first-single").find("select").select2("destroy");

        // Create a clone of element with id ddl_1:
        //   $(".single-hour:first-of-type").find("span.select2 ").remove();
        let clone = document.querySelector(".single-hour").cloneNode(true);
        //  t=Math.random()*(10000-5)+5
        //  clone.querySelector('.stest').id='id'+Math.floor(t)
        clone.classList.remove("first-single");
        //  console.log(clone);
        $(".first-single").find("select").select2({});

        let before = document.querySelector(".container-hour .add-hours-btn");
        // Append the newly created element on element p
        let allInputs = clone.querySelectorAll("input");
        allInputs.forEach((input) => {
            if (input.type === "date") {
                let today = null;
                if (
                    document.querySelector(
                        ".single-hour:nth-last-child(2) #inputPrice"
                    )
                ) {
                    today = new Date(
                        document.querySelector(
                            ".single-hour:nth-last-child(2) #inputPrice"
                        ).value
                    );
                } else {
                    today = new Date(
                        document.querySelector(
                            ".single-hour:nth-last-of-type(1) #inputPrice"
                        ).value
                    );
                }
                //   console.log(document.querySelector('.single-hour:last-of-type'))
                //  console.log(today)
                today = today.getTime() + 86400000;
                today = new Date(today);
                let dd = today.getDate();
                let mm = today.getMonth() + 1; //January is 0!
                let yyyy = today.getFullYear();
                if (dd < 10) {
                    dd = "0" + dd;
                }
                if (mm < 10) {
                    mm = "0" + mm;
                }
                today = yyyy + "-" + mm + "-" + dd;
                //     inputDate= new Date(input.value);
                //  nextDay=new Date(inputDate.getTime() + 86400000)
                //     console.log(nextDay)
                input.value = today + "";
            } else {
            }
        });
        let removeBtn = document.createElement("button");
        removeBtn.classList.add("remove-hour-btn");
        removeBtn.innerHTML =
            "<img src='../images/iconmonstr-plus-6-red.svg' alt='usuń godzinę'>";
        removeBtn.setAttribute("type", "button");
        clone.appendChild(removeBtn);
        removeBtn.addEventListener("click", () => {
            removeHours(removeBtn.parentNode);
        });

        before.parentNode.insertBefore(clone, before);
        $(".single-hour:last-of-type").find("select").select2();
        //  $(".single-hour:not(:first-of-type)").find('select').select2()

        //  clone.querySelector('select').classList.add('testy')
    }
    function removeHours(parentOfBtn) {
        parentOfBtn.remove();
    }
    const wrap = document.querySelector(".container-hour");
    if (wrap) {
        let addHoursBtn = document.querySelector(".add-hours-btn");
        let removeHoursBtns = null;
        addHoursBtn.addEventListener("click", () => {
            addMoreHours();
        });
    }

    //detecting current page
    var url = window.location.href;

    let navLinks = document.querySelectorAll(".sidebar nav a");

    navLinks.forEach((link) => {
        if (url.includes(link.href)) {
            link.classList.add("current-page");
        }
    });
});
