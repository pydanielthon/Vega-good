document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('search-by-name')
    const list = document.querySelector('.table tbody')
if(searchInput){
    function setList(group) {
        if(searchInput.classList.contains('search-contrahents')){
            clearList()
            const headers=document.createElement('tr')
            headers.classList.add('table-headers')
            headers.innerHTML=`         <tr class='table-headers'>
            <th>Firma</th>
            <th>Email</th>
            <th class="text-right">Dane i rozliczenia</th>

        </tr>`
            list.appendChild(headers)

            for (const link of group) {
                const item = document.createElement('tr')
                item.innerHTML+=`      <tr class="" >
                <td>${link.querySelector('td:first-of-type').textContent}</td>
                <td>${link.querySelector('td:nth-child(2)').textContent}</td>

                <td>
                    <a class="nav-link  button-border" href="${link.getAttribute('data-link')}">Zobacz</a>

                </td>
            </tr>`
            if(link.getAttribute('data-active')==='0'){

            item.classList.add(`inactive`)
            }else{

            }
            // item.classList.add('list-group__li')
                // const itemInner = document.createElement('a')
                // itemInner.classList.add('list-group__a')
                // itemInner.href = link.href

                //     const searchTag = document.createElement('p')
                //     searchTag.classList.add('list-group__tag')
                //     searchTag.textContent = "Branża"
                //     item.appendChild(searchTag)



                // const text = document.createTextNode(link.textContent);
                // itemInner.appendChild(text)
                // item.appendChild(itemInner)

                list.appendChild(item)

            }
            if (group.length === 0) {
                setNoResults()
            }
        }else{


        clearList()
        const headers=document.createElement('tr')
        headers.classList.add('table-headers')
        headers.innerHTML=`  <tr class="table-headers">
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Stawka</th>
        <th class="text-right">Dane i rozliczenia</th>

    </tr>`
        list.appendChild(headers)

        for (const link of group) {
            const item = document.createElement('tr')
            item.innerHTML+=`      <tr class="" >
            <td>${link.querySelector('td:first-of-type').textContent}</td>
            <td>${link.querySelector('td:nth-child(2)').textContent}</td>
            <td>${link.querySelector('td:nth-child(3)').textContent}</td>

            <td>
                <a class="nav-link  button-border" href="${link.getAttribute('data-link')}">Zobacz</a>

            </td>
        </tr>`
        if(link.getAttribute('data-active')==='0'){

        item.classList.add(`inactive`)
        }else{

        }
        // item.classList.add('list-group__li')
            // const itemInner = document.createElement('a')
            // itemInner.classList.add('list-group__a')
            // itemInner.href = link.href

            //     const searchTag = document.createElement('p')
            //     searchTag.classList.add('list-group__tag')
            //     searchTag.textContent = "Branża"
            //     item.appendChild(searchTag)



            // const text = document.createTextNode(link.textContent);
            // itemInner.appendChild(text)
            // item.appendChild(itemInner)

            list.appendChild(item)

        }
        if (group.length === 0) {
            setNoResults()
        }
    }
    }

    function clearList() {
        while (list.firstChild) {
            list.removeChild(list.firstChild)
        }
    }

    function setNoResults() {
        const item = document.createElement('h4')
        item.classList.add('list-group__li', 'no-results')
        // const itemInner = document.createElement('p')
        const text = document.createTextNode("Brak wyników");
        item.appendChild(text)
        list.appendChild(item)
    }

    function getRelevency(value, searchTerm) {
        if (value === searchTerm) {
            return 2;
        } else if (value.startsWith(searchTerm)) {
            return 1
        } else if (value.includes(searchTerm)) {
            return 0
        } else {
            return -1
        }

    }


    // const placesLinks = [...document.querySelectorAll('.places_menu .menu__link')]
    // const tradesLinks = [...document.querySelectorAll('.trade_menu .menu__link')]
    const allSearch = [...document.querySelectorAll('.single-table-element')]
    // console.log(allSearch)
    searchInput.addEventListener('input', (event) => {
        let value = event.target.value
        if (value && value.trim().length > 0) {
            value = value.trim().toLowerCase()
            setList(allSearch.filter(single => {
                return single.getAttribute('data-info').toLowerCase().includes(value)

            }).sort((dataA, dataB) => {
                return getRelevency(dataB.textContent, value) - getRelevency(dataA.textContent, value)
            }))
        } else {
            setList(allSearch.filter(single => {
                return single.getAttribute('data-info').toLowerCase()

            }).sort((dataA, dataB) => {
                return getRelevency(dataB.textContent, value) - getRelevency(dataA.textContent, value)
            }))
        }
    })




}
})
