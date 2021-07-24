output += `<tr>
    <td>${all_items[i].name} ${all_items[i].surname}</td>
    <td>${all_items[i].work_day}</td>
    <td>${all_items[i].hours} </td>
    <td>${all_items[i].workers_price_hour} </td>
    <td>${all_items[i].contrahent_name} </td>
    <td>${all_items[i].salary_invoice} </td>
    <td>K: ${all_items[i].salary_invoice} * ${
        all_items[i].hours
        } = <b>${sum}</b>
        <br>P: ${all_items[i].workers_price_hour} * ${
        all_items[i].hours
        } = <b>${sum2}</b>
    </td>
    <td>${sum} - ${sum2} = <b>${sum - sum2}</b></td>
</tr>`;
