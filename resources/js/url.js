import {Tooltip} from 'bootstrap';

const urlInput = document.getElementById('url');
const urlButton = document.getElementById('url-button');

if (urlInput !== null && urlButton !== null) {
    const tooltip = new Tooltip(urlButton, {
        title: 'Gekopieerd!',
        trigger: 'manual'
    });

    let tooltipTimeout = 0;

    urlButton.addEventListener('click', () => {
        urlInput.select();
        urlInput.setSelectionRange(0, 1000);
        navigator.clipboard.writeText(urlInput.value);
        tooltip.show();
        clearTimeout(tooltipTimeout);
        tooltipTimeout = setTimeout(() => {
            tooltip.hide();
        }, 500);
    });
}
