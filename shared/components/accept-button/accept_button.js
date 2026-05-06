export function createAcceptButton(onClickLogic) {
    const btn = document.newElement('button'); 
    btn.innerText = 'ACCEPT';
    btn.className = '.accept-btn';

    btn.addEventListener('click', onClickLogic);
    return btn;
}