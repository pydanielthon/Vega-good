let checkSuperAdmin = document.querySelector("#superadmin");
if (checkSuperAdmin) {
    const allPermCheckboxes = document.querySelectorAll(
        ".user-permissions input"
    );
    const permissionsContainer = document.querySelector(
        ".user-permissions"
    );
    if(checkSuperAdmin.checked){
        permissionsContainer.classList.toggle('no-events')

    }
    checkSuperAdmin.addEventListener("change", () => {
        if (checkSuperAdmin.checked) {
            allPermCheckboxes.forEach((checkbox) => {
                checkbox.checked = true;


            });

        }
        permissionsContainer.classList.toggle('no-events')

    });
}
