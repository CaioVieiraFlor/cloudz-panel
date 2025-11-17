const modal = new bootstrap.Modal(document.getElementById('modal'))
modal.show()

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

const toggleBtn = document.getElementById('toggle-form-btn')
const formContainer = document.getElementById('form-container')
const attachmentsContainer = document.getElementById('attachments')

toggleBtn.addEventListener('click', () => {
    if (formContainer.classList.contains('d-none')) {
        formContainer.classList.remove('d-none')
        attachmentsContainer.classList.add('d-none')
    } else {
        formContainer.classList.add('d-none')
        attachmentsContainer.classList.remove('d-none')
    }
})

const form = document.querySelector('form')

const saveFormToLocalStorage = () => {
    const tabs = document.querySelectorAll('.tab-pane')

    const inputServiceType = document.querySelector('input[name="service"]');
    const serviceRadio = document.querySelector('input[name="service"]:checked')
    const service = serviceRadio.value

    const data = {}
    data.general = {}

    data.general[inputServiceType.name] = service

    tabs.forEach(tab => {
        const tabName = tab.getAttribute('data-tab')
        const inputs = tab.querySelectorAll('input')
        data[tabName] = {}

        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                data[tabName][input.name] = input.checked
            } else {
                data[tabName][input.name] = input.value
            }
        })
    })

    const generalFields = document.querySelectorAll('#div-path input, #div-utility-path input, #checkbox-utility-path, #input-encrypt-name, #input-delete-after-upload')
    generalFields.forEach(input => {
        if (input.type === 'checkbox') {
            data.general[input.name] = input.checked
        } else {
            data.general[input.name] = input.value
        }
    })

    localStorage.setItem('formData', JSON.stringify(data))
}

const loadFormFromLocalStorage = () => {
    const storedData = localStorage.getItem('formData')
    if (!storedData) return

    const data = JSON.parse(storedData)

    for (const tabName in data) {
        if (tabName === 'general') continue
        const tab = document.querySelector(`.tab-pane[data-tab="${tabName}"]`)
        if (!tab) continue

        for (const inputName in data[tabName]) {
            const input = tab.querySelector(`[name="${inputName}"]`)
            if (!input) continue

            if (input.type === 'checkbox') {
                input.checked = data[tabName][inputName]
            } else {
                input.value = data[tabName][inputName]
            }
        }
    }

    if (data.general) {
        for (const inputName in data.general) {
            const input = document.querySelector(`[name="${inputName}"]`)
            if (!input) continue

            const value = data.general[inputName]

            if (input.type === 'checkbox') {
                input.checked = !!value
            } else if (input.type === 'radio') {
                const radioToSelect = document.querySelector(`[name="${inputName}"][value="${value}"]`)
                if (radioToSelect) {
                    radioToSelect.checked = true
                }
            } else {
                input.value = value
            }
        }

    }
}

loadFormFromLocalStorage()
form.addEventListener('input', saveFormToLocalStorage)

document.addEventListener('input', (event) => {
    if (event.target.matches('input[type="radio"]')) {
        saveFormToLocalStorage()
    }
})

const paintAttachmentsFromLocalStorage = (files) => {
    const attachmentsView = document.getElementById('attachments-view')
    const serviceRadio = document.querySelector('input[name="service"]:checked').value

    files.forEach(file => {
        attachmentsView.innerHTML += `
            <div class="file-card w-100" data-url="${file.url}">
                <img src="/assets/icons/docs_24dp_2B579A_FILL0_wght400_GRAD0_opsz24.svg" alt="Bootstrap" style="width: 5%;">
                <div class="w-100">
                    <div class="file-info d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="file-name mb-0">${file.name}</p>
                            </div>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="${file.url}">Baixar</a>
                    </div>
                </div>
                <img class="delete cursor-pointer" src="/assets/icons/delete_24dp_C80909_FILL0_wght400_GRAD0_opsz24.svg" alt="Bootstrap" style="width: 3%;" data-url="${file.url}" data-service="${serviceRadio}">
            </div>
        `
    })
}

const attachmentsSaved = localStorage.getItem('attachments')
if (attachmentsSaved) {
    paintAttachmentsFromLocalStorage(JSON.parse(attachmentsSaved).files || [])
}

const requiredFields = {
    'AWS-S3': ['aws-s3-key', 'aws-s3-secret-key', 'aws-s3-region', 'aws-s3-bucket-name'],
    'FTP': ['host', 'user', 'password', 'port'],
    'GOOGLE-DRIVE': ['client-id', 'client-secret', 'refresh-token']
}

const uploadAttachments = async (formData) => {
    try {
        const response = await fetch('index.php', {
            method: 'POST',
            body: formData
        })

        if (!response.ok) throw new Error('Erro ao enviar arquivos')

        const result = await response.text()

        inputAttachments.value = ''

        return result
    } catch (err) {
        alert('Erro: ' + err.message)
        inputAttachments.value = ''
    }
}

const deleteAttachment = async (url, formData) => {
    try {
        const response = await fetch(`index.php?url=${url}`, {
            method: 'POST',
            body: formData
        })

        if (!response.ok) throw new Error('Erro ao deletar arquivo')

        const result = await response.text()

        inputAttachments.value = ''
    } catch (err) {
        alert('Erro: ' + err.message)
        inputAttachments.value = ''
    }
}

const inputAttachments = document.getElementById('input-attachments')
inputAttachments.addEventListener('change', (event) => {
    const files = Array.from(event.target.files)
    if (files.length === 0) return

    const serviceRadio = document.querySelector('input[name="service"]:checked')
    if (!serviceRadio) {
        alert('Selecione um serviço antes de enviar os arquivos.')
        inputAttachments.value = ''
        return
    }

    const service = serviceRadio.value

    const storedData = localStorage.getItem('formData')
    if (!storedData) {
        alert('Nenhuma configuração encontrada no Local Storage. Preencha os dados do serviço antes.')
        inputAttachments.value = ''
        return
    }

    const data = JSON.parse(storedData)[service] || {}

    const missingFields = requiredFields[service].filter(field => !data[field] || data[field].trim() === '')
    if (missingFields.length > 0) {
        alert('Campos obrigatórios faltando para o serviço ' + service + ': ' + missingFields.join(', '))
        inputAttachments.value = ''
        return
    }

    const formData = new FormData()
    files.forEach(file => formData.append('attachments[]', file))
    formData.append('service', service)

    for (const key of Object.keys(data)) {
        formData.append(key, data[key])
    }

    uploadAttachments(formData)
        .then((result) => {
            const attachmentsView = document.getElementById('attachments-view')

            localStorage.setItem('attachments', result)

            const files = JSON.parse(result).files;
            paintAttachmentsFromLocalStorage(files)
        })
})

const btnsDelete = document.querySelectorAll('.delete')
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('delete')) {
        const confirmDeletion = confirm('Deseja mesmo excluir esse arquivo?');
        if (confirmDeletion) {
            const fileUrl = event.target.dataset.url

            const storedData = localStorage.getItem('formData')
            if (!storedData) {
                alert('Nenhuma configuração encontrada no Local Storage. Preencha os dados do serviço antes.')
                inputAttachments.value = ''
                return
            }

            const service = event.target.dataset.service
            const data = JSON.parse(storedData)[service] || {}

            const formData = new FormData()
            formData.append('service', service)

            for (const key of Object.keys(data)) {
                formData.append(key, data[key])
            }

            deleteAttachment(fileUrl, formData)
                .then(() => {
                    const filesSaved = JSON.parse(localStorage.getItem('attachments')).files
                    const files = filesSaved.filter(file => file.url == fileUrl)

                    localStorage.setItem('attachments', JSON.stringify(files))

                    const divAttachment = document.querySelector(`.file-card[data-url="${fileUrl}"`)
                    divAttachment.classList.add('d-none')
                })
        }
    }
})
