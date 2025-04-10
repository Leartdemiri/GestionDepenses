const monthlyExpensesData = {
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
    datasets: [{
        label: 'Dépenses Mensuelles (CHF)',
        data: [120, 90, 150, 200, 170, 180, 160, 190, 140, 130, 175, 195],
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.3,
        fill: true
    }]
};

const monthlyExpensesConfig = {
    type: 'line',
    data: monthlyExpensesData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Évolution des Dépenses Mensuelles'
            }
        }
    },
};

const expenseBreakdownData = {
    labels: ['Achats', 'Loisirs', 'Mobilité', 'Finances', 'Logement', 'Constitution de patrimoine', 'Autres'],
    datasets: [{
        label: 'Répartition des Dépenses',
        data: [5000, 60, 180, 90, 70, 50, 40],
        backgroundColor: [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#8BC34A'
        ],
        hoverOffset: 4
    }]
};

const expenseBreakdownConfig = {
    type: 'doughnut',
    data: expenseBreakdownData,
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Répartition des Dépenses'
            }
        }
    },
};

window.onload = () => {
    new Chart(document.getElementById('monthlyExpensesChart'), monthlyExpensesConfig);
    new Chart(document.getElementById('expenseBreakdownChart'), expenseBreakdownConfig);
};