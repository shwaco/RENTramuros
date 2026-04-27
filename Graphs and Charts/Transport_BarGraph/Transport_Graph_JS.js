const canvas = document.getElementById('transportBarChart');
const ctx = canvas.getContext('2d');

const methods = ["Tranvia", "Tuktuk", "Kalesa"];
const dataValues = [52, 92, 75]; 

const colors = ["#FF6384", "#36A2EB", "#FFCE56"]; 

let hoveredBar = -1;

const padding = 60;
const chartWidth = canvas.width - (padding * 2);
const chartHeight = canvas.height - (padding * 2);
const maxValue = 100; 

function drawChart() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    const barWidth = 140; 
    const totalBarArea = chartWidth / methods.length;

    ctx.fillStyle = "#f8f9fa"; 
    ctx.fillRect(0, 0, padding, canvas.height - padding); 
    
    ctx.fillRect(0, canvas.height - padding, canvas.width, padding);

    ctx.strokeStyle = "#d1d1d1";
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding, 10); 
    ctx.lineTo(padding, canvas.height - padding); 
    ctx.lineTo(canvas.width - 10, canvas.height - padding); 
    ctx.stroke();

    ctx.strokeStyle = "#e0e0e0";
    ctx.lineWidth = 1;
    ctx.textAlign = "right";
    ctx.textBaseline = "middle";
    ctx.fillStyle = "#444"; 
    ctx.font = "bold 12px Arial";

    for (let i = 20; i <= 100; i += 20) {
        const y = canvas.height - padding - (i / maxValue) * chartHeight;
        
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(canvas.width - padding, y);
        ctx.stroke();
        
        ctx.fillText(i, padding - 15, y);
    }

    methods.forEach((name, i) => {
        const isHovered = hoveredBar === i;
        const val = dataValues[i];
        const bHeight = (val / maxValue) * chartHeight;
        
        const x = padding + (i * totalBarArea) + (totalBarArea - barWidth) / 2;
        const y = canvas.height - padding - bHeight;

        if (isHovered) {
            ctx.shadowColor = "rgba(0,0,0,0.3)";
            ctx.shadowBlur = 12;
            ctx.shadowOffsetX = 4;
            
            ctx.fillStyle = colors[i] + "CC"; 
            
            ctx.strokeStyle = colors[i];
            ctx.lineWidth = 2;
        } else {
            ctx.shadowColor = "rgba(0,0,0,0.1)";
            ctx.shadowBlur = 4;
            ctx.shadowOffsetX = 2;
            ctx.fillStyle = colors[i];
        }

        ctx.beginPath();
        const barX = isHovered ? x - 2 : x;
        const barW = isHovered ? barWidth + 4 : barWidth;
        const barY = isHovered ? y - 4 : y; // Lift it up slightly
        const barH = isHovered ? bHeight + 4 : bHeight;

        ctx.roundRect(barX, barY, barW, barH, [4, 4, 0, 0]);
        ctx.fill();
        if (isHovered) ctx.stroke();

        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;

        ctx.fillStyle = "#333";
        ctx.font = isHovered ? "bold 15px 'Segoe UI'" : "bold 14px 'Segoe UI'";
        ctx.textAlign = "center";
        ctx.fillText(name, x + barWidth / 2, canvas.height - padding + 32);

        if (isHovered) {
            drawTooltip(x + barWidth / 2, barY - 25, `${val}`);
        }
    });
}

function drawTooltip(x, y, text) {
    ctx.font = 'bold 12px Arial';
    const textWidth = ctx.measureText(text).width;
    const paddingH = 12;
    const rectWidth = textWidth + (paddingH * 2);
    const rectHeight = 26;

    let rectX = x - (rectWidth / 2);
    let rectY = y - (rectHeight / 2);

    if (rectY < 5) rectY = 5; 
    if (rectY + rectHeight > canvas.height - 5) rectY = canvas.height - rectHeight - 5;
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

    let found = -1;
    const totalBarArea = chartWidth / methods.length;

    methods.forEach((_, i) => {
        const barWidth = 140;
        const xStart = padding + (i * totalBarArea) + (totalBarArea - barWidth) / 2;
        const xEnd = xStart + barWidth;
        
        const bHeight = (dataValues[i] / maxValue) * chartHeight;
        const yTop = canvas.height - padding - bHeight;

        if (mouseX >= xStart && mouseX <= xEnd && mouseY >= yTop && mouseY <= canvas.height - padding) {
            found = i;
        }
    });

    if (found !== hoveredBar) {
        hoveredBar = found;
        canvas.style.cursor = found !== -1 ? 'pointer' : 'default';
        drawChart();
    }
});

drawChart();