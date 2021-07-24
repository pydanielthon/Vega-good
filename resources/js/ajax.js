// start

const billingsBtn = document.getElementById("billingsButton");
if (billingsBtn) {
    var getUrl = window.location;
    var baseUrl = getUrl.protocol + "//" + getUrl.host;
    // let dataHidden = document.querySelector("#data");
    let frompdf = document.querySelector("#frompdf");
    let topdf = document.querySelector("#topdf");
    let categorypdf = document.querySelector("#categorypdf");
    let notespdf = document.querySelector("#notespdf");
    let pdfBtn = document.querySelector(".print-pdf");
    const hasPermissionEdit = document.querySelector("#hasPermissionEdit");
    billingsBtn.addEventListener("click", function () {
        const dateFrom = document.querySelector("#dateFrom").value;
        const dateTo = document.querySelector("#dateTo").value;
        const inNotes = document.querySelector("#inNotes").value;
        const cat = document.querySelector("#cat").value;
        const show = document.querySelector("#showurl").value;
        // const edit = document.querySelector("#editurl").value;
        const deleteUrl = document.querySelector("#deleteurl").value;
        let prevCat = "4";

        let data = {
            dateFrom: dateFrom,
            dateTo: dateTo,
            inNotes: inNotes,
            cat: cat,
            show: show,
            deleteUrl: deleteUrl,
        };

        topdf.value = dateTo;
        frompdf.value = dateFrom;
        categorypdf.value = cat;
        notespdf.value = inNotes;

        // dataHidden.value = JSON.stringify(data);

        let xhttp = new XMLHttpRequest();
        let urlPost = baseUrl + "/ajax-billings-get";
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let all_items = JSON.parse(this.responseText);
                const size = Object.keys(all_items).length;
                let sum = 0;
                let workersSum = 0;
                // let size = Object.keys(all_items).length;
                let output = "";

                if (size > 0 && !all_items.error && cat != "s") {
                    output = `<table class="table table-responsive">
                    <tr class="table-headers">
                    <th>Data</th>
                    <th>Imię i nazwisko pracownika</th>
                    <th>Nazwa kontrahenta</th>
                    <th>Kategoria</th>
                    <th>Kwota</th>
                    <th></th>
                </tr>
                    `;
                    for (let i = 0; i < size; i++) {
                        sum += parseFloat(all_items[i].price);
                        console.log(hasPermissionEdit.value);
                        output += `


                    <tr>
                        <td>${all_items[i].date}</td>
                        <td>${
                            all_items[i].workers_name
                                ? all_items[i].workers_name
                                : "-"
                        }</td>
                        <td>${
                            all_items[i].contrahents_name
                                ? all_items[i].contrahents_name
                                : "-"
                        }</td>
                        <td>${all_items[i].category_name}</td>
                        <td>${all_items[i].price}</td>
                        <td>
                            `;
                        if (
                            all_items[i].status_of_billings != 1 &&
                            hasPermissionEdit.value != 0
                        ) {
                            output += `  <a class="nav-link button-border" href="${all_items[i].edit_link}">Edytuj</a>

                                <button class="nav-link btn-danger button-border button-border-del delete-billing-btn" data-url="${all_items[i].delete_link}">Usuń</button>`;
                        }
                        output += `
                        </td>
                    </tr>
                    `;
                    }

                    `</table>
                    `;
                    pdfBtn.classList.add("active-pdf");
                } else if (cat === "s") {
                    output = `<table class="table table-responsive">
                    <tr class="table-headers">
                    <th>Data</th>
                    <th>Kogo dotyczy</th>
                    <th>Kwota</th>
                    <th>Suma</th>
                </tr>
                <td><strong>Pracownicy</strong></td><td></td><td></td><td></td>
                    `;
                    for (let i = 0; i < size; i++) {
                        if (!(prevCat === all_items[i].category_id)) {
                            output += `<td></td><td></td><td></td><td>${sum}</td>`;
                            output += `<tr><td><strong>Kontrahenci</strong></td><td></td><td></td><td></td></tr>`;

                            workersSum = sum;
                        } else {
                        }
                        sum += parseFloat(all_items[i].price);

                        output += `


                    <tr>
                        <td>${all_items[i].date}</td>
                        `;
                        if (all_items[i].category_id === "4") {
                            output += `<td>${
                                all_items[i].workers_name ?? ""
                            }</td>`;
                        } else {
                            output += ` <td>${
                                all_items[i].contrahents_name ?? ""
                            }</td>`;
                        }
                        `

                     `;
                        output += `   <td>${all_items[i].price}</td><td></td>
                      </tr>
                    `;
                        if (i === size - 1) {
                            output += `<tr><td></td><td></td><td></td><td>${
                                sum - workersSum
                            }</td></tr>`;
                            output += `<td><strong>Łącznie</strong></td><td></td><td></td><td>${
                                sum - workersSum - workersSum
                            }</td>`;
                        }
                        prevCat = all_items[i].category_id;
                    }

                    `</table>
                    `;
                    pdfBtn.classList.add("active-pdf");
                } else if (all_items.error) {
                    output = `<p>${all_items.error}</p>`;
                } else {
                    output = "<h2>Brak wyników dla podanych parametrów</h2>";
                }
                output += `<div class="unpaid_hours-container summary-price">Łącznie:<span> ${sum}<span></div>
 `;
                document.getElementById("result-billings").innerHTML = output;
                const deleteBillingBtns = document.querySelectorAll(
                    ".delete-billing-btn"
                );

                deleteBillingBtns.forEach((btn) => {
                    btn.addEventListener("click", () => {
                        deleteBilling(btn);
                    });
                });
            }
        };

        xhttp.open("POST", urlPost, true);
        xhttp.setRequestHeader(
            "X-CSRF-TOKEN",
            document.querySelector("#csrftoken").value
        );
        xhttp.setRequestHeader("Content-type", "application/json");

        xhttp.send(JSON.stringify(data));
    });

    // pdfBtn.addEventListener("click", pdfGenerate);
}

//ajaxowe usuwanie rozliczen

function deleteBilling(btn) {
    let xhttp1 = new XMLHttpRequest();
    let urlPost1 = btn.getAttribute("data-url");
    xhttp1.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let firstParent = btn.parentElement;
            let secondParent = firstParent.parentElement;
            document.querySelector(".summary-price span").textContent =
                parseInt(
                    document.querySelector(".summary-price span").textContent
                ) - parseFloat(firstParent.previousElementSibling.textContent);

            secondParent.remove();
        }
    };

    xhttp1.open("GET", urlPost1, true);

    xhttp1.setRequestHeader("Content-type", "application/json");

    xhttp1.send();
}

//pdf

// function pdfGenerate() {
//     let xhttp = new XMLHttpRequest();
//     let urlPost = this.getAttribute("data-url");
//     let data = JSON.parse(document.querySelector("#data").value);
//     console.log(data);
//     xhttp.onreadystatechange = function () {
//         if (this.readyState == 4 && this.status == 200) {
//             // var blob = new Blob([this.responseText], {
//             //     type: "application/pdf",
//             // });
//             // var link = document.createElement("a");
//             // link.href = window.URL.createObjectURL(blob);
//             // link.download = "test.pdf";
//             // link.click();
//             // window.open("data:application/pdf," + this.responseText);
//             // var reader = new FileReader();
//             // reader.readAsArrayBuffer(blob);
//         }
//     };

//     xhttp.open("POST", urlPost, true);
//     xhttp.setRequestHeader(
//         "X-CSRF-TOKEN",
//         document.querySelector("#csrftoken").value
//     );
//     xhttp.setRequestHeader("Content-type", "application/json");

//     xhttp.send(JSON.stringify(data));
// }

//ajaxowe pobieranie godzin dla kontrahentów i pracowników

const hoursAllBtn = document.getElementById("hoursAll");
if (hoursAllBtn) {
    var getUrl = window.location;
    var baseUrl = getUrl.protocol + "//" + getUrl.host;
    // let dataHidden = document.querySelector("#data");
    let frompdf = document.querySelector("#frompdf");
    let topdf = document.querySelector("#topdf");

    let categorypdf = document.querySelector("#categorypdf");
    let notespdf = document.querySelector("#notespdf");
    let pdfBtn = document.querySelector(".print-pdf");
    hoursAllBtn.addEventListener("click", function () {
        const dateFrom = document.querySelector("#dateFrom").value;
        const dateTo = document.querySelector("#dateTo").value;
        const cat = document.querySelector("#cat").value;
        const show = document.querySelector("#showurl").value;
        today = new Date();
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
        console.log(today);
        let data = {
            dateFrom: dateFrom ? dateFrom : "1970-01-01",
            dateTo: dateTo ? dateTo : today,
            cat: cat,
            show: show,
        };

        if (cat == 1) {
            let xhttp = new XMLHttpRequest();
            let urlPost = baseUrl + "/ajax-hours-all-get";
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let all_items = JSON.parse(this.responseText);
                    const size = Object.keys(all_items).length;
                    let sum = 0;
                    let output = "";
                    let hoursTotal = 0;

                    if (size > 0 && !all_items.error) {
                        output = `<table class="table">
                    <tr class="table-headers">
                    <th>Pracownik</th>
                    <th>Godziny</th>
                    <th></th>

                </tr>
                    `;
                        for (let i = 0; i < size; i++) {

                            console.log(all_items[i]);
                            sum += parseFloat(all_items[i].price);
                            hoursTotal += parseFloat(all_items[i].total_hours);
                            output += `<tr>
                        <td>${all_items[i].name} ${all_items[i].surname}</td>
                        <td>${all_items[i].total_hours} </td>
                        <td>
                            <a class="nav-link button-border send-data" href="${baseUrl}/hours/workers-generate/${all_items[i].id}/${data.dateFrom}/${data.dateTo}">Drukuj PDF</a>
                        </td>
                    </tr>`;
                         if(i === size -1){
                        output += `<tr><td>Podsumowanie</td><td><b>${hoursTotal}</b></td><td></td></tr>`;
                    }
                        }

                        `</table>
                    `;
                    } else if (all_items.error) {
                        output = `<p>${all_items.error}</p>`;
                    } else {
                        output =
                            "<h2>Brak wyników dla podanych parametrów</h2>";
                    }

                    document.getElementById("result-billings").innerHTML =
                        output;
                    // const deleteBillingBtns =
                    //     document.querySelectorAll(".send-data");

                    // deleteBillingBtns.forEach((btn) => {
                    //     btn.addEventListener("click", () => {
                    //         generatePdfHoursAll(btn);
                    //     });
                    // });
                }
            };

            xhttp.open("POST", urlPost, true);
            xhttp.setRequestHeader(
                "X-CSRF-TOKEN",
                document.querySelector("#csrftoken").value
            );
            xhttp.setRequestHeader("Content-type", "application/json");

            xhttp.send(JSON.stringify(data));
        } else {
            let xhttp = new XMLHttpRequest();
            let urlPost = baseUrl + "/ajax-hours-all-get-contrahents";
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let all_items = JSON.parse(this.responseText);
                    const size = Object.keys(all_items).length;
                    let sum = 0;
                    let output = "";
                    let fromDate = document.querySelector("#dateFrom").value;
                    let toDate = document.querySelector("#dateTo").value;
                    console.log(fromDate);
                    console.log(toDate);
                    let hoursTotal = 0;

                    if (size > 0 && !all_items.error) {
                        output = `<table class="table">
                    <tr class="table-headers">
                    <th>Kontrahent</th>
                    <th>Godziny</th>
                    <th></th>

                </tr>
                    `;
                        for (let i = 0; i < size; i++) {
                            console.log(all_items[i]);
                            sum += parseFloat(all_items[i].price);
                            hoursTotal += parseFloat(all_items[i].total_hours);
                            output += `<tr>
                        <td>${all_items[i].name} </td>
                        <td>${all_items[i].total_hours} </td>
                        <td>
                        <a class="nav-link button-border send-data" href="${baseUrl}/hours/contrahents-generate/${all_items[i].id}/${data.dateFrom}/${data.dateTo}">Drukuj PDF</a>
                        </td>
                    </tr>`;

                    if(i === size -1){
                        output += `<tr><td>Podsumowanie</td><td><b>${hoursTotal}</b></td><td></td></tr>`;
                    }
                        }

                        `</table>
                    `;
                    } else if (all_items.error) {
                        output = `<p>${all_items.error}</p>`;
                    } else {
                        output =
                            "<h2>Brak wyników dla podanych parametrów</h2>";
                    }

                    document.getElementById("result-billings").innerHTML =
                        output;
                    const deleteBillingBtns = document.querySelectorAll(
                        ".delete-billing-btn"
                    );

                    deleteBillingBtns.forEach((btn) => {
                        btn.addEventListener("click", () => {
                            deleteBilling(btn);
                        });
                    });
                }
            };

            xhttp.open("POST", urlPost, true);
            xhttp.setRequestHeader(
                "X-CSRF-TOKEN",
                document.querySelector("#csrftoken").value
            );
            xhttp.setRequestHeader("Content-type", "application/json");

            xhttp.send(JSON.stringify(data));
        }
    });

    // pdfBtn.addEventListener("click", pdfGenerate);
}

// function generatePdfHoursAll(btn) {
//     let xhttp1 = new XMLHttpRequest();
//     let urlPost1 = btn.getAttribute("data-url") + "/workers-pdf-hours-all";
//     let data1 = {
//         dateFrom: btn.getAttribute("data-from"),
//         dateTo: btn.getAttribute("data-to"),
//         dataId: btn.getAttribute("data-id"),
//     };

//     xhttp1.type = "arraybuffer";

//     xhttp1.onreadystatechange = function () {
//         if (this.readyState == 4 && this.status == 200) {
//             // var blobSrc = window.URL.createObjectURL(
//             //     new Blob([this.responseText], { type: "application/pdf" })
//             // );
//             // assign to your iframe or to window.open
//             // window.open(blobSrc);
//         }
//     };
//     console.log(data1);
//     xhttp1.open("GET", urlPost1, true);
//     xhttp1.setRequestHeader(
//         "X-CSRF-TOKEN",
//         document.querySelector("#csrftoken").value
//     );
//     xhttp1.setRequestHeader("Content-type", "application/json");

//     xhttp1.send(JSON.stringify(data1));
// }
const summaryBtn = document.getElementById("billingsSummary");
if (summaryBtn) {
    let pdfBtn = document.querySelector(".print-pdf");

    var getUrl = window.location;
    var baseUrl = getUrl.protocol + "//" + getUrl.host;
    // let dataHidden = document.querySelector("#data");
    let frompdf = document.querySelector("#frompdf");
    let topdf = document.querySelector("#topdf");

    summaryBtn.addEventListener("click", function () {
        const dateFrom = document.querySelector("#dateFrom").value;
        const dateTo = document.querySelector("#dateTo").value;
        let data = {
            dateFrom: dateFrom,
            dateTo: dateTo,
        };

        topdf.value = dateTo;
        frompdf.value = dateFrom;

        let xhttp = new XMLHttpRequest();
        let urlPost = baseUrl + "/ajax-billings-summary";
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let all_items = JSON.parse(this.responseText);
                const size = Object.keys(all_items).length;
                let sum = 0;
                let sum2 = 0;
                let sum3 = 0;

                let total1 = 0;
                let total2 = 0;
                let output = "";
                let hoursTotal = 0;
                console.log(dateFrom);

                if (size > 0 && !all_items.error) {
                    output = `<table class="table">
                <tr class="table-headers">
                <th>Pracownik</th>
                <th>Dzień</th>
                <th>Godziny</th>
                <th>Stawka</th>
                <th>Kontrahent</th>
                <th>Stawka</th>
                <th>Zysk</th>
            </tr>
                `;
                    for (let i = 0; i < size; i++) {
                        sum = all_items[i].salary_invoice * all_items[i].hours;
                        total2 += sum;
                        sum2 =
                            all_items[i].workers_price_hour *
                            all_items[i].hours;

                        sum3 += sum - sum2;
                        total1 +=
                            all_items[i].workers_price_hour *
                            all_items[i].hours;

                            hoursTotal += parseInt(all_items[i].hours);

                        //                         output += `<tr>
                        //                     <td>${all_items[i].name} ${all_items[i].surname}</td>
                        //                     <td>${all_items[i].work_day}</td>
                        //                     <td>${all_items[i].hours} </td>
                        //                     <td>${all_items[i].workers_price_hour} </td>
                        //                     <td>${all_items[i].contrahent_name} </td>
                        //                     <td>${all_items[i].salary_invoice} </td>
                        //                     <td>K: ${all_items[i].salary_invoice} * ${
                        //                             all_items[i].hours
                        //                         } = <b>${sum}</b>
                        //                     <br>P: ${all_items[i].workers_price_hour} * ${
                        //                             all_items[i].hours
                        //                         } = <b>${sum2}</b>
                        //                     </td>
                        // <td>${sum} - ${sum2} = <b>${sum - sum2}</b></td>
                        //                 </tr>`;
                        output += `<tr>
<td>${all_items[i].name} ${all_items[i].surname}</td>
<td>${all_items[i].work_day}</td>
<td>${all_items[i].hours} </td>
<td>${all_items[i].workers_price_hour} </td>
<td>${all_items[i].contrahent_name} </td>
<td>${all_items[i].salary_invoice} </td>

<td> <b>${sum - sum2}</b></td>
</tr>`;
                        let total3 = total2 - total1;
                        if (i === size - 1) {
                            output += `<tr><td><b>Podsumowanie</b></td><td></td><td>${hoursTotal}</td><td></td><td></td><td></td><td> Dochód: <b>${total3}</b></td></tr>`;
                        }
                    }
                    `
                    </table>`;
                    pdfBtn.classList.add("active-pdf");
                } else if (all_items.error) {
                    output = `<p>${all_items.error}</p>`;
                } else {
                    output = "<h2>Brak wyników dla podanych parametrów</h2>";
                }
                document.getElementById("result-billings").innerHTML = output;
            }
        };
        xhttp.open("POST", urlPost, true);
        xhttp.setRequestHeader(
            "X-CSRF-TOKEN",
            document.querySelector("#csrftoken").value
        );
        xhttp.setRequestHeader("Content-type", "application/json");

        xhttp.send(JSON.stringify(data));
    });
}
