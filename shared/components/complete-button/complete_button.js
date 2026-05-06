export function createCompleteButton(onClickLogic) {
    const btn = document.createElement('button');
    btn.className = '.btn-complete';
    btn.innerText = 'COMPLETE';

    btn.addEventListener('click', onClickLogic);
    return btn;
}