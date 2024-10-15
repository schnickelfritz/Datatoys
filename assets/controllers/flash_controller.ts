import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ['inner', 'reshow', 'flash'];

    connect() {
        this.element.classList.add('app-flash-pop');
        setTimeout(this.unpop, 150);
        setTimeout(this.minimize, 20000);
    }

    unpop() {
        const flashOuterDiv = document.getElementById('flash');
        flashOuterDiv.classList.add('app-flash-unpop');
    }

    minimize() {
        const flashOuterDiv = document.getElementById('flash');
        const flashInnerDiv = document.getElementById('flash_inner');
        const flashReshow = document.getElementById('flash_reshow');

        flashInnerDiv.style.display = 'none';
        flashReshow.classList.remove("d-none");
        flashOuterDiv.classList.add('app-flash-minimize');
    }

    reshow() {
        const flashOuterDiv = document.getElementById('flash');
        const flashInnerDiv = document.getElementById('flash_inner');
        const flashReshow = document.getElementById('flash_reshow');

        flashInnerDiv.style.display = 'block';
        flashReshow.classList.add('d-none');
        flashOuterDiv.classList.remove("app-flash-minimize");
    }
}