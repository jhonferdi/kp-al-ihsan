<template>
  <div>
    <b-row>
      <b-col md="12">
        <div style="max-width:150px; margin:auto;">
          <apexchart ref="donut" type="donut" :options="chartOptions" :series="series"></apexchart>
        </div>
      </b-col>
      <b-col>
        <b-row class="mt-3">
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color: #FF4D77"></span>
              <div>
                <div class="text-muted mb-1">Perempuan</div>
                <strong class="h3 font-weight-bold">{{(jumlahPerempuan / total * 100).toFixed(2)}}%</strong>
                <div>{{jumlahPerempuan}} Orang</div>
              </div>
            </div>
          </b-col>
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color: #42A5F5"></span>
              <div>
                <div class="text-muted mb-1">Laki-laki</div>
                <strong class="h3 font-weight-bold">{{(jumlahLakilaki / total * 100).toFixed(2)}}%</strong>
                <div>{{jumlahLakilaki}} Orang</div>
              </div>
            </div>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default {
  inject: ['count_male', 'count_female'],
  data: function () {
    return {
      jumlahPerempuan: null,
      jumlahLakilaki: null,
      chartOptions: {
        colors: ['#FF4D77', '#42A5F5'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: 0
        },
        grid: {
          padding: {
            left: -5,
            bottom: -5,
          }
        },
        plotOptions: {
          pie: {
            expandOnClick: false,
            donut: {
              size: '75%',
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value, w) {
              let total = w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
              return `${value} (${(value / total * 100).toFixed(2)}%)`;
            }
          }
        },
        legend: { show: false },
        labels: ["Perempuan", "Laki-laki"],
      },
      series: []
    }
  },
  computed: {
    total() {
      return this.series.reduce((partialSum, a) => partialSum + a, 0)
    },
  },
  mounted() {
    this.updateData()
  },
  methods: {
    updateData() {
      this.jumlahPerempuan = this.count_female
      this.jumlahLakilaki = this.count_male
      this.series = [this.jumlahPerempuan, this.jumlahLakilaki]
    }
  }
}
</script>
<style scoped lang="scss">
.dots {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}
</style>