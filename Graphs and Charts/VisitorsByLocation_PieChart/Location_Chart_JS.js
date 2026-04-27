const canvas = document.getElementById('locationPieChart');
const ctx = canvas.getContext('2d');
const legendContainer = document.getElementById('pieLegend');

const attractions = [
    "Bambike", "Barbara's", "Casa Manila", "Fort Santiago", "Minor Basilica",
    "Museo de Intramuros", "Palacio del Gobernador", "Puerta del Parian",
    "Puerta Real", "Rizal Shrine", "Bagumbayan Museum",
    "San Agustin Church", "Silahis"
];

const dataValues = [5.0, 4.5, 8.0, 15.0, 10.0, 7.5, 6.0, 5.5, 4.0, 9.0, 6.5, 12.0, 7.0];

const colors = [
    "#FF708D", "#48BBFF", "#FFD66E", "#5CD6D6", "#B085FF", 
    "#FFB061", "#D1D5DB", "#5EEAD4", "#F87171", "#94A3B8", 
    "#475569", "#CBD5E1", "#2DD4BF"
];

let hoveredSlice = -1;
const centerX = canvas.width / 2;
const centerY = canvas.height / 2;
const radius = 220; 

function createLegend() {
    if (!legendContainer) return;
    legendContainer.innerHTML = '';
    attractions.forEach((name, i) => {
        const item = document.createElement('div');
        item.className = 'legend-item'; 
        item.innerHTML = `
            <span class="legend-box" style="background-color: ${colors[i]}; border: 1px solid rgba(0,0,0,0.1);"></span>
            <span class="legend-label">${name}</span>
        `;
        legendContainer.appendChild(item);
    });
}

function drawChart() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    let startAngle = -0.5 * Math.PI;

    const chartRadius = 200; 

    dataValues.forEach((val, i) => {
        const sliceAngle = (val / 100) * (2 * Math.PI);
        const middleAngle = startAngle + sliceAngle / 2;
        const isHovered = hoveredSlice === i;

        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, isHovered ? chartRadius + 12 : chartRadius, startAngle, startAngle + sliceAngle);
        ctx.closePath();
        ctx.fillStyle = colors[i];
        ctx.fill();
        ctx.strokeStyle = 'white';
        ctx.lineWidth = 2;
        ctx.stroke();

        const labelRadius = chartRadius * 0.75;
        const tx = centerX + Math.cos(middleAngle) * labelRadius;
        const ty = centerY + Math.sin(middleAngle) * labelRadius;

        ctx.fillStyle = "white"; 
        ctx.shadowColor = "rgba(0,0,0,0.3)";
        ctx.shadowBlur = 4;
        ctx.font = "bold 13px 'Segoe UI'";
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        
        if (val > 3) {
            ctx.fillText(`${val.toFixed(1)}%`, tx, ty);
        }
        ctx.shadowBlur = 0; 

        if (isHovered) {
            const tooltipRadius = chartRadius + 80; 
            let tipX = centerX + Math.cos(middleAngle) * tooltipRadius;
            let tipY = centerY + Math.sin(middleAngle) * tooltipRadius;
        
            tipY += 10;
            if (attractions[i] === "Bagumbayan Museum") {
            tipX -= 50; 
            }
            drawTooltip(tipX, tipY, attractions[i]);
        }

        startAngle += sliceAngle;
    });
}

function drawTooltip(x, y, name) {
    ctx.font = 'bold 13px Arial';
    const textWidth = ctx.measureText(name).width;
    const paddingH = 12;
    const rectWidth = textWidth + (paddingH * 2);
    const rectHeight = 30;

    let rectX = x - (rectWidth / 2);
    let rectY = y - (rectHeight / 2);

    if (rectY < 10) rectY = 10; 
    if (rectY + rectHeight > canvas.height - 10) rectY = canvas.height - rectHeight - 10;
    if (rectX < 10) rectX = 10;
    if (rectX + rectWidth > canvas.width - 10) rectX = canvas.width - rectWidth - 10;

    ctx.fillStyle = 'rgba(20, 20, 20, 0.9)';
    ctx.beginPath();
    ctx.roundRect(rectX, rectY, rectWidth, rectHeight, 6);
    ctx.fill();

    ctx.fillStyle = 'white';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(name, rectX + rectWidth / 2, rectY + rectHeight / 2);
}

canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const dx = x - centerX;
    const dy = y - centerY;
    const distance = Math.sqrt(dx * dx + dy * dy);
    
    let found = -1;
    if (distance < radius) {
        let angle = Math.atan2(dy, dx);
        if (angle < -0.5 * Math.PI) angle += 2 * Math.PI;
        let currentAngle = -0.5 * Math.PI;
        for (let i = 0; i < dataValues.length; i++) {
            const sliceAngle = (dataValues[i] / 100) * (2 * Math.PI);
            if (angle >= currentAngle && angle <= currentAngle + sliceAngle) {
                found = i;
                break;
            }
            currentAngle += sliceAngle;
        }
    }
    if (found !== hoveredSlice) {
        hoveredSlice = found;
        canvas.style.cursor = found !== -1 ? 'pointer' : 'default';
        drawChart();
    }
});

createLegend();
drawChart();