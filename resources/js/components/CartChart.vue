<template>
    <div>
        <div>
            <select v-model="selectedDateRange" @change="fetchData">
                <option value="all">All Time</option>
                <option value="last_week">Last Week</option>
                <option value="this_month">This Month</option>
                <option value="this_year">This Year</option>
                <option value="today">Today</option>
            </select>

        </div>
        <span>Total: {{total}}</span>

        <DoughnutChart :chartData="chartData" />
    </div>
</template>

<script>
import { DoughnutChart } from 'vue-chart-3';
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);
export default {
    name:'CartChart',
    components: { DoughnutChart },
    data() {
        return {
            selectedDateRange: 'all',
            total: 0,
            chartData: {
                labels: ['Orders','Draft','Rejected'],
                datasets: [
                    {
                        data: [],
                        backgroundColor: ["#77CEFF", "#0079AF", "#123E6B",]
                    },

                ],
            },
            chartOptions: {
                responsive:true,
                maintainAspectRatio: true,
            },
        };
    },
    methods: {
        async fetchData() {
            try {
                const response = await this.$axios.get('/api/cartChart', {
                    params: {
                        selectedDateRange: this.selectedDateRange,
                    },
                });

                const currentData = response.data.data ;
                this.total = response.data.data.total ?? 0;

                    // this.chartData.labels = currentData.map(item => item.key);
                    this.chartData.datasets[0].data[0] = currentData.confirmedOrder;
                    this.chartData.datasets[0].data[1] = currentData.draftOrder;
                    this.chartData.datasets[0].data[2] = currentData.rejectedOrder;



            } catch (e) {
                handleError(e, this.$toast);
            }
        },
    },
    async mounted() {
        await this.fetchData();
    },
};
</script>
