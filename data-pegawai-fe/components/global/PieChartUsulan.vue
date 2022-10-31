<template>
  <high-charts width="100%" height="100%" :option="chartOptions" :id="id"></high-charts>
</template>
<script>
import HighCharts from './HighCharts'
export default {
  props:['seriesData', 'id'],
  components: { HighCharts },
  data(){
    return{
      chartOptions: {
        chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie',
          height: (9 / 16 * 100) + '%' // 16:9 ratio
        },
        title: {
          text: ''
        },
        tooltip: {
          pointFormat: ' <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
          plotOptions: {
            pie: {
              colors: [
                '#556ee6', 
                'gray', 
                '#f1b44c', 
                '#34c38f', 
                '#f46a6a', 
              ],
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  // format: '<b>{point.name}</b>: {point.percentage:.1f} %'
              }
            }
        },
        series: [{
          colorByPoint: true,
          data:[],
        }]
      }
    }
  },
  methods:{
    getData: function(){
      this.chartOptions.series[0].data = this.seriesData;
    }
  },
  created(){
    this.getData();
  }
}
</script>