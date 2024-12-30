// مثال على حركة بسيطة يمكن تعديلها لاحقًا للتخصيص
document.addEventListener('DOMContentLoaded', () => {
    const body = document.querySelector('body');

    // تغيير ألوان الخلفية بلمسة كل 20 ثانية
    setInterval(() => {
        body.style.setProperty('--color1', `#${Math.floor(Math.random()*16777215).toString(16)}`);
        body.style.setProperty('--color2', `#${Math.floor(Math.random()*16777215).toString(16)}`);
    }, 20000);
});
