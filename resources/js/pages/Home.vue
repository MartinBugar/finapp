<template>
    <div class="bg-main-page-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <p class="main-title pt-4"> Spravte si prehľad</p>
                    <ul class="main-title-list">
                        <li>Kde míňate peniaze</li>
                        <li>Príjmov a výdajov</li>
                    </ul>
                </div>

                <div class="col-lg-6 chart">
                    <Pie :data="this.chartData" :options="this.chartOptions" />
                </div>

            </div>
        </div>
    </div>


</template>

<script>

import {Chart as ChartJS, ArcElement, Tooltip, Legend} from 'chart.js'
import {Pie} from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: "Home",
    components: {
        Pie
    },
    data() {
        return {
            chartData: {
                labels: ["Potraviny", "Hobby", "Hypotéka", "Oblečenie", "Elektronika", "Veci pre psa"],
                datasets: [
                    {
                        backgroundColor: [
                            "#da1515",
                            "#fff300",
                            "#4c57e5",
                            "#238500",
                            "#00fff7",
                            "#c600ff"
                        ],
                        data: [100, 50, 350, 120, 200, 100],
                    }
                ]
            },
            chartOptions: '',
        }
    },
    created() {
        this.chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        }
    },
    methods: {
    }
}
</script>

<style>

.bg-main-page-top {
    background: linear-gradient(90deg, #3A92C1 0%, #3A92C1 24%, #3A92C1 34%, #fff 100%);
    width: 100%;
    height: 400px;
}

.main-title {
    font-family: "Oswald", serif;
    text-transform: uppercase;
    /*max-width: 730px;*/
    color: #fff;
    font-size: 3.75rem;
}

.main-title-list {
    font-family: "Oswald", serif;
    /*max-width: 730px;*/
    color: #fff;
    font-size: 2rem;
}

.chart {
    max-height: 400px;
    padding-left: 70px;
    padding-top: 50px;
}

</style>
