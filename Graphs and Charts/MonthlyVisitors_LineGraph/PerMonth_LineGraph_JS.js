const canvas = document.getElementById('tourChart');
const ctx = canvas.getContext('2d');

const dataPoints = [38, 88, 13, 18, 38, 38, 78, 78, 18, 88, 68, 58];
const padding = 50;
const chartWidth = canvas.width - padding * 2;
const chartHeight = canvas.height - padding * 2;
const maxVal = 100;

let hoverIndex = -1;

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

    ctx.beginPath();
    ctx.strokeStyle = '#4169E1';
    ctx.lineWidth = 2;
    dataPoints.forEach((val, i) => {
        const x = padding + (i / 11) * chartWidth;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;
        if (i === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
    });
    ctx.stroke();

    dataPoints.forEach((val, i) => {
        const x = padding + (i / 11) * chartWidth;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;

        ctx.beginPath();
        ctx.arc(x, y, i === hoverIndex ? 6 : 4, 0, Math.PI * 2);
        ctx.fillStyle = i === hoverIndex ? '#4169E1' : 'white';
        ctx.fill();
        ctx.strokeStyle = '#4169E1';
        ctx.stroke();

        if (i === hoverIndex) {
            drawTooltip(x, y, val);
        }
    });

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

function drawTooltip(x, y, val) {
    const text = `${val}`;
    ctx.font = 'bold 12px Arial';
    const textWidth = ctx.measureText(text).width;
    
    ctx.fillStyle = 'rgba(0, 0, 0, 0.75)';
    ctx.fillRect(x - (textWidth/2) - 5, y - 35, textWidth + 10, 25);
    
    ctx.fillStyle = 'white';
    ctx.textAlign = 'center';
    ctx.fillText(text, x, y - 18);
}

canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;
    
    let found = -1;
    dataPoints.forEach((val, i) => {
        const x = padding + (i / 11) * chartWidth;
        const y = canvas.height - padding - (val / maxVal) * chartHeight;
        
        const dist = Math.sqrt((mouseX - x)**2 + (mouseY - y)**2);
        if (dist < 10) found = i;
    });

    if (found !== hoverIndex) {
        hoverIndex = found;
        canvas.style.cursor = found !== -1 ? 'pointer' : 'default';
        drawChart(); 
    }
});

drawChart();