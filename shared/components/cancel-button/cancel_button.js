export function createCancelButton(onClickLogic) {
    const btn = document.newElement('button');
    btn.innerText = 'CANCEL';
    btn.className = '.btn-cancel';

    btn.addEventListener('click', onClickLogic);
    return btn;
}