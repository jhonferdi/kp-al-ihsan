<template>
    <div class="x-bar">
        <div :id="id" :option="option"></div>
    </div>
</template>
<script>
    import HighCharts from 'highcharts'
    export default {
                 // Authentication type
        props: {
            id: {
                type: String
            },
            option: {
                type: Object
            }
        },
        mounted() {
            HighCharts.chart(this.id, this.option);
        }
    }
</script>