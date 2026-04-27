const canvas = document.getElementById('packagesBarChart');
const ctx = canvas.getContext('2d');

const packages = [
    "Sacred Route", 
    "Cultural Combo", 
    "Bastions and Walls", 
    "Hero's Trail", 
    "Walled City Grand Tour"
];

const dataValues = [79, 30, 86, 61, 97]; 

const colors = [
    "#6b3a0d", 
    "#3b59ed", 
    "#ff85a2", 
    "#ff8e51", 
    "#0ca678"  
];

let hoveredBar = -1;

const paddingLeft = 180; 
const paddingTop = 40;
const paddingRight = 40;
const paddingBottom = 40;

const chartWidth = canvas.width - paddingLeft - paddingRight;
const chartHeight = canvas.height - paddingTop - paddingBottom;
const maxValue = 100;

function drawChart() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    const barHeight = 45; 
    const totalBarArea = chartHeight / packages.length;

    ctx.fillStyle = "#f8f9fa"; 
    ctx.fillRect(0, 0, paddingLeft, canvas.height); 
    ctx.fillRect(paddingLeft, 0, canvas.width - paddingLeft, paddingTop);

    ctx.strokeStyle = "#d1d1d1";
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(paddingLeft, canvas.height - paddingBottom); 
    ctx.lineTo(paddingLeft, paddingTop); 
    ctx.lineTo(canvas.width - 10, paddingTop); 
    ctx.stroke();

    ctx.strokeStyle = "#e0e0e0";
    ctx.lineWidth = 1;
    ctx.textAlign = "center";
    ctx.textBaseline = "bottom";
    ctx.fillStyle = "#444"; 
    ctx.font = "bold 14px Arial";

    for (let i = 20; i <= 100; i += 20) {
        const x = paddingLeft + (i / maxValue) * chartWidth;
        
        ctx.beginPath();
        ctx.moveTo(x, paddingTop);
        ctx.lineTo(x, canvas.height - paddingBottom);
        ctx.stroke();
        
        ctx.fillText(i, x, paddingTop - 5);
    }

    packages.forEach((name, i) => {
        const isHovered = hoveredBar === i;
        const val = dataValues[i];
        const bWidth = (val / maxValue) * chartWidth;
        
        const x = paddingLeft;
        const y = paddingTop + (i * totalBarArea) + (totalBarArea - barHeight) / 2;


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
        const drawY = isHovered ? y - 2 : y;
        const drawH = isHovered ? barHeight + 4 : barHeight;
        const drawW = isHovered ? bWidth + 4 : bWidth; // Pushes "out" to the right

        ctx.roundRect(x, drawY, drawW, drawH, [0, 4, 4, 0]);
        ctx.fill();
        if (isHovered) ctx.stroke();

        ctx.shadowBlur = 0;
        ctx.shadowOffsetX = 0;

        ctx.fillStyle = "#333";
        ctx.font = isHovered ? "bold 14px 'Segoe UI'" : "bold 13px 'Segoe UI'";
        ctx.textAlign = "right";
        ctx.textBaseline = "middle";
        ctx.fillText(name, paddingLeft - 15, y + barHeight / 2);

        if (isHovered) {
            const tooltipX = paddingLeft + drawW + 25; 
            const tooltipY = y + barHeight / 2;
            
            drawTooltip(tooltipX, tooltipY, `${val}`);
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
    const totalBarArea = chartHeight / packages.length;

    packages.forEach((_, i) => {
        const barHeight = 45;
        const yStart = paddingTop + (i * totalBarArea) + (totalBarArea - barHeight) / 2;
        const yEnd = yStart + barHeight;
        
        const bWidth = (dataValues[i] / maxValue) * chartWidth;
        const xEnd = paddingLeft + bWidth;

        if (mouseX >= paddingLeft && mouseX <= xEnd && mouseY >= yStart && mouseY <= yEnd) {
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