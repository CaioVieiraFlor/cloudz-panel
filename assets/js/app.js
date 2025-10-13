const checkboxUtilityPath = document.getElementById('checkbox-utility-path')
checkboxUtilityPath.addEventListener('change', (event) => {
    const divUtilityPath = document.getElementById('div-utility-path')
    const divPath = document.getElementById('div-path')

    if (event.target.checked) {
        divUtilityPath.classList.remove('d-none')
        divPath.classList.add('d-none')
    } else {
        divUtilityPath.classList.add('d-none')
        divPath.classList.remove('d-none')
    }
})

const toggleRequired = (tabId, required) => {
    const tabPane = document.querySelector(tabId)
    const inputs = tabPane.querySelectorAll('input')
    inputs.forEach((input) => {
        if (required) {
            input.setAttribute('required', 'required')
        } else {
            input.removeAttribute('required')
        }
    })
}

toggleRequired('#aws-s3-tab-pane', true)
toggleRequired('#ftp-tab-pane', false)
toggleRequired('#google-drive-tab-panel', false)

document.querySelectorAll('button[data-bs-toggle="tab"]').forEach((btn) => {
    btn.addEventListener('shown.bs.tab', () => {
        toggleRequired('#aws-s3-tab-pane', false)
        toggleRequired('#ftp-tab-pane', false)
        toggleRequired('#google-drive-tab-panel', false)

        const target = btn.getAttribute('data-bs-target')
        toggleRequired(target, true)
    })
})