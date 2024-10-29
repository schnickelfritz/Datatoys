import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['radio', 'detailsStart', 'detailsHours'];

    connect() {
        this.handleVisibility();
    }

    onChange() {
        this.handleVisibility();
    }

    handleVisibility() {
        const optionValue = this.radioTargets.find((element) => element.checked === true).value;
        if (optionValue === 'none' || optionValue === 'away') {
            this.hideDetails();
        } else {
            this.showDetails();
        }
    }

    showDetails() {
        this.detailsStartTarget.classList.remove('d-none');
        this.detailsHoursTarget.classList.remove('d-none');
    }

    hideDetails() {
        this.detailsStartTarget.classList.add('d-none');
        this.detailsHoursTarget.classList.add('d-none');
    }
}