import { Controller } from '@hotwired/stimulus';

const REQUEST_READY = 4;
const HTTP_OK = 200;

export default class extends Controller {
    static targets = ['files', 'input', 'progresscontainer', 'progress', 'bar', 'filefeedback', 'allowreplace', 'finished'];
    static values = {
        tableId: String
    }

    async click() {
        const filesCount = this.filesTarget.files.length;
        if (filesCount < 1) {
            this.noFilesSelected();
            return;
        }
        this.initOutput();
        for (let i = 0; i < filesCount; i++) {
            this.updateProgressBar(i, filesCount);
            await this.upload(this.filesTarget.files[i]);
        }
        this.updateProgressBar(filesCount, filesCount);
        this.uploadFinished();
    }

    noFilesSelected() {
        this.filefeedbackTarget.innerHTML = 'Keine Datei ausgewählt';
    }

    initOutput() {
        this.inputTarget.classList.add('d-none');
        this.finishedTarget.classList.add('d-none');
        this.progresscontainerTarget.classList.remove('d-none');
        this.filefeedbackTarget.innerHTML = '';
    }

    updateProgressBar(fileIndex, fileCount) {
        const percent = (fileIndex / fileCount) * 100;
        this.progressTarget.setAttribute('aria-valuenow', percent);
        this.barTarget.innerHTML = `${fileIndex}/${fileCount}`;
        this.barTarget.style.width = `${percent}%`;
    }

    async upload(file) {
        const filename = file.name;
        console.log(filename, this.tableIdIdValue, this.allowreplaceTarget.checked);
        const request = new XMLHttpRequest();
        request.addEventListener("readystatechange", () => {
            if (request.readyState === REQUEST_READY && request.status !== HTTP_OK) {
                this.filefeedbackTarget.innerHTML += `${filename} : ${request.response}<br>`;
            }
        });
        const formData = new FormData();
        formData.append('file', file);
        formData.append('tableId', this.tableIdValue);
        formData.append('allowReplace', this.allowreplaceTarget.checked);
        request.open("POST", '/grid/fileupload', true);
        request.send(formData);
    }

    uploadFinished() {
        this.finishedTarget.classList.remove('d-none');
    }
}