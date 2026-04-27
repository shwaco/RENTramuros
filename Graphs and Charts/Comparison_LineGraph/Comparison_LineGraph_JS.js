const canvas = document.getElementById('tourChart');
const ctx = canvas.getContext('2d');

const packagesData = [26, 92, 94, 12, 58, 71, 69, 66, 27, 100, 80, 81];
const customData = [88, 38, 21, 21, 58, 48, 22, 95, 90, 87, 26, 51];

const padding = 50;
const chartWidth = canvas.width - padding * 2;
const chartHeight = canvas.height - padding * 2;
const maxVal = 100;

let activeHover = null; 

function drawChart() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.strokeStyle = '#e0e0e0';
    ctx.lineWidth = 1;
    ctx.font = '14px Arial';
    ctx.fillStyle = '#333';
    
    for (let i = 0; i <= 4; i++) {
        const val = i * 25;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(canvas.width - padding, y);
        ctx.stroke();
        ctx.textAlign = 'right';
        ctx.fillText(val, padding - 10, y + 5);
    }

    ctx.textAlign = 'center';
    for (let i = 0; i < 12; i++) {
        const x = padding + (i / 11) * chartWidth;
        ctx.beginPath();
        ctx.moveTo(x, padding);
        ctx.lineTo(x, canvas.height - padding);
        ctx.stroke();
        ctx.fillText(i + 1, x, canvas.height - padding + 25);
    }

    drawLine(packagesData, '#ef1d38'); 
    drawLine(customData, '#5c2e0e');   

    drawPoints(packagesData, '#ef1d38', 'packages');
    drawPoints(customData, '#5c2e0e', 'custom');

    ctx.strokeStyle = '#444';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, canvas.height - padding);
    ctx.lineTo(canvas.width - padding, canvas.height - padding);
    ctx.lineTo(canvas.width - padding, padding);
    ctx.lineTo(padding, padding);
    ctx.stroke();
}

function drawLine(data, color) {
    ctx.beginPath();
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    data.forEach((val, i) => {
        const x = padding + (i / 11) * chartWidth;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;
        if (i === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
    });
    ctx.stroke();
}

function drawPoints(data, color, type) {
    data.forEach((val, i) => {
        const x = padding + (i / 11) * chartWidth;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;
        const isHovered = activeHover && activeHover.type === type && activeHover.index === i;

        ctx.beginPath();
        ctx.arc(x, y, isHovered ? 6 : 4, 0, Math.PI * 2);
        ctx.fillStyle = isHovered ? color : 'white';
        ctx.fill();
        ctx.strokeStyle = color;
        ctx.lineWidth = 1.5;
        ctx.stroke();

if (isHovered) {
    drawTooltip(x, y - 50, val, type);
}
    });
}

function drawTooltip(x, y, val, type) {
    const text = `${val}`; 
    ctx.font = 'bold 12px Arial';
    const textWidth = ctx.measureText(text).width;
    const rectWidth = textWidth + 20;
    const rectHeight = 25;

    let rectX = x - (rectWidth / 2);
    let rectY = y - (rectHeight / 2); 

    if (rectY < 5) rectY = 5; 
    if (rectX < 5) rectX = 5;
    if (rectX + rectWidth > canvas.width - 5) rectX = canvas.width - rectWidth - 5;

    ctx.fillStyle = 'rgba(0, 0, 0, 0.85)';
    ctx.beginPath();
    ctx.roundRect(rectX, rectY, rectWidth, rectHeight, 4);
    ctx.fill();

    ctx.fillStyle = 'white';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(text, rectX + rectWidth / 2, rectY + rectHeight / 2);
}

canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;
    
    let found = null;

    [
        { data: packagesData, type: 'packages' },
        { data: customData, type: 'custom' }
    ].forEach(set => {
        set.data.forEach((val, i) => {
            const x = padding + (i / 11) * chartWidth;
            const y = canvas.height - padding - (val / maxVal) * chartHeight;
            const dist = Math.sqrt((mouseX - x)**2 + (mouseY - y)**2);
            if (dist < 10) found = { type: set.type, index: i };
        });
    });

    if (JSON.stringify(found) !== JSON.stringify(activeHover)) {
        activeHover = found;
        canvas.style.cursor = found ? 'pointer' : 'default';
        drawChart();
    }
});

drawChart();