function slideDown(el, duration = 200) {
    el.style.display = 'block';
    el.style.height = '0px';
    el.style.overflow = 'hidden';

    const targetHeight = el.scrollHeight + 'px';
    el.offsetHeight;
    el.style.transition = `height ${duration}ms ease`;
    el.style.height = targetHeight;

    setTimeout(() => {
        el.style.height = '';
        el.style.transition = '';
        el.style.overflow = '';
    }, duration);
}

function slideUp(el, duration = 200) {
    el.style.height = el.scrollHeight + 'px';
    el.offsetHeight;
    el.style.transition = `height ${duration}ms ease`;
    el.style.height = '0px';
    el.style.overflow = 'hidden';

    setTimeout(() => {
        el.style.display = 'none';
        el.style.height = '';
        el.style.transition = '';
        el.style.overflow = '';
    }, duration);
}

async function loadStats() {
    try {
        const response = await fetch("../php/stats.php", {
            credentials: 'include'
        });

        const data = await response.json();
        if (data.error) return;

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

        const doughnutCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        new Chart(doughnutCtx, {
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
                },
                onClick: function (event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const clickedCategory = this.data.labels[index];
                        window.location.href = `?type=${encodeURIComponent(clickedCategory)}`;
                    }
                }
            }
        });

        const latestTable = document.querySelector(".latest-expenses tbody");
        const totalDisplay = document.getElementById("totalAmount");

        if (latestTable && !window.location.search.includes("type=")) {
            latestTable.innerHTML = "";
            let total = 0;

            data.latestExpenses.forEach(exp => {
                const row = document.createElement("tr");

                const typeCell = document.createElement("td");
                typeCell.textContent = exp.type || exp.title;

                const amountCell = document.createElement("td");
                const amount = parseFloat(exp.amount);
                total += amount;
                amountCell.textContent = `${amount.toFixed(2)} ${exp.currency}`;
                amountCell.style.fontWeight = "bold";

                const dateCell = document.createElement("td");
                dateCell.textContent = exp.date;

                // ✅ Ajoute une cellule pour le bouton Supprimer
                const deleteCell = document.createElement("td");
                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Supprimer";
                deleteButton.className = "btn-delete";
                deleteButton.addEventListener("click", async () => {
                    if (confirm("Êtes-vous sûr de vouloir supprimer cette dépense ?")) {
                        try {
                            const response = await fetch("../php/functions.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: new URLSearchParams({
                                    action: "deleteExpense",
                                    expenseId: exp.idSpending // Assurez-vous que `exp.idSpending` contient l'ID correct
                                })
                            });
                
                            const result = await response.json();
                            if (response.ok && result.success) {
                                alert("Dépense supprimée avec succès.");
                                location.reload(); // Recharge la page pour mettre à jour la liste
                            } else {
                                alert(result.error || "Une erreur est survenue lors de la suppression.");
                            }
                        } catch (err) {
                            console.error("Erreur :", err);
                            alert("Une erreur est survenue lors de la suppression.");
                        }
                    }
                });
                deleteCell.appendChild(deleteButton);


                row.appendChild(typeCell);
                row.appendChild(amountCell);
                row.appendChild(dateCell);
                row.appendChild(deleteCell);
                latestTable.appendChild(row);
            });

            // ✅ Ajout du total global
            if (totalDisplay) {
                totalDisplay.textContent = `Total : ${total.toFixed(2)} CHF`;
            }
        }

    } catch (err) {
        console.error("Erreur de chargement des statistiques :", err);
    }
}

async function loadCategoryExpensesOnly() {
    const params = new URLSearchParams(window.location.search);
    const type = params.get("type");
    if (!type) return;

    try {
        const response = await fetch("../php/stats.php", {
            credentials: 'include'
        });

        const data = await response.json();
        if (data.error) return;

        const filteredByType = data.latestExpenses.filter(exp => exp.title === type);

        const monthSelect = document.getElementById("monthFilter");
        const monthIcon = document.getElementById("monthFilterIcon");
        const totalDisplay = document.getElementById("totalAmount");

        function renderTable(monthFilter = "") {
            const tbody = document.querySelector(".expenses-table tbody");
            tbody.innerHTML = "";
            let total = 0;

            const displayed = filteredByType.filter(exp => {
                if (!monthFilter) return true;
                const month = new Date(exp.date).getMonth() + 1;
                return month.toString().padStart(2, "0") === monthFilter;
            });

            if (displayed.length === 0) {
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 3;
                cell.textContent = "Aucune dépense trouvée.";
                row.appendChild(cell);
                tbody.appendChild(row);
            }

            displayed.forEach(exp => {
                const row = document.createElement("tr");

                const typeCell = document.createElement("td");
                typeCell.textContent = exp.title;

                const amountCell = document.createElement("td");
                const amount = parseFloat(exp.amount);
                total += amount;
                amountCell.textContent = `${amount.toFixed(2)} ${exp.currency}`;
                amountCell.style.fontWeight = "bold";

                const dateCell = document.createElement("td");
                dateCell.textContent = exp.date;

                row.appendChild(typeCell);
                row.appendChild(amountCell);
                row.appendChild(dateCell);
                tbody.appendChild(row);
            });

            totalDisplay.textContent = `Total : ${total.toFixed(2)} CHF`;
        }

        renderTable();

        if (monthSelect && monthIcon && totalDisplay) {
            monthIcon.addEventListener("click", () => {
                if (monthSelect.style.display === "none" || getComputedStyle(monthSelect).display === "none") {
                    slideDown(monthSelect);
                } else {
                    slideUp(monthSelect);
                }
            });

            monthSelect.addEventListener("change", () => {
                renderTable(monthSelect.value);
            });
        }

        const sectionTitle = document.querySelector(".section-title");
        if (sectionTitle) sectionTitle.textContent = `Dépenses pour : ${type}`;

    } catch (e) {
        console.error("Erreur :", e);
    }
}

function toggleExpenseType() {
    const actionType = document.getElementById('actionType');
    const expenseTypeGroup = document.getElementById('expenseTypeGroup');
    if (!actionType || !expenseTypeGroup) return;

    expenseTypeGroup.style.display = actionType.value === 'addMoney' ? 'none' : 'block';
}

window.onload = () => {
    loadStats();
    loadCategoryExpensesOnly();
    toggleExpenseType();

    const icon = document.getElementById("monthFilterIcon");
    const select = document.getElementById("monthFilter");
    if (icon && select) {
        icon.addEventListener("click", () => {
            if (select.style.display === "none" || getComputedStyle(select).display === "none") {
                slideDown(select);
            } else {
                slideUp(select);
            }
        });
    }
};
