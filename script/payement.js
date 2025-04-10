async function loadStats() {
    try {
        const response = await fetch("../php/stats.php", {
            credentials: 'include'
        });
        
        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            return;
        }

        new Chart(document.getElementById('monthlyExpensesChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Dépenses Mensuelles (CHF)',
                    data: data.monthlyExpenses,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Évolution des Dépenses Mensuelles'
                    }
                }
            }
        });

        new Chart(document.getElementById('expenseBreakdownChart'), {
            type: 'doughnut',
            data: {
                labels: data.expenseTypes.map(e => e.Type),
                datasets: [{
                    data: data.expenseTypes.map(e => e.total),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56',
                        '#4BC0C0', '#9966FF', '#FF9F40', '#8BC34A'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Répartition des Dépenses'
                    }
                }
            }
        });

    } catch (err) {
        console.error("Erreur de chargement des statistiques :", err);
    }
}

window.onload = loadStats;