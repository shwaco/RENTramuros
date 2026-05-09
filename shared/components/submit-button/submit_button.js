export function createSubmitButton(onClickLogic) {
    const btn = document.createElement('button');
    btn.innerText = 'SUBMIT';
    btn.className = '.btn-submit-final';

    btn.addEventListener('click', onClickLogic);
    return btn;
}