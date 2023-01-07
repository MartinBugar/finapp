<template>

        <GChart
            type="PieChart"
            :options="options"
            :data="data"
        />

</template>

<script>
import { GChart } from "vue-google-charts";
export default {
    name: "App",
    components: {
        GChart
    },
    data() {
        return {
            first : 14,
            data: [
                ['Daily Routine', 'Hours per Day'],
                ['Work',     14],
                ['Eat',      1],
                ['Reading',  2],
                ['Exercise', 2],
                ['Sleep',    5]
            ],
            options: {
                width: 970,
                height: 400,
            }
        };
    }
};
</script>
