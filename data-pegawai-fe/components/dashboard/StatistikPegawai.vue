<template>
  <div>
    <b-row>
      <b-col md="12">
        <div style="max-width:150px; margin:auto">
          <apexchart ref="donut" type="donut" :options="chartOptions" :series="series"></apexchart>
        </div>
      </b-col>
      <b-col>
        <b-row class="mt-3">
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color:#069550"></span>
              <div>
                <div class="text-muted mb-1">Jumlah PNS</div>
              </div>
            </div>
          </b-col>
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color:#7A1FA2"></span>
              <div>
                <div class="text-muted mb-1">Pegawai Tetap</div>
              </div>
            </div>
          </b-col>
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color:#1976D2"></span>
              <div>
                <div class="text-muted mb-1">Jumlah CPNS</div>
              </div>
            </div>
          </b-col>
          <b-col class="mb-2" sm="6">
            <div class="d-flex">
              <span class="flex-shrink-0 my-1 dots mr-2" style="background-color:#FFA600"></span>
              <div>
                <div class="text-muted mb-1">Pegawai Kontrak</div>
              </div>
            </div>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default{
  inject: ['count_permanent', 'count_contract','count_pns','count_cpns'],
  data: function() {
    return {
      jumlahPns: null,
      jumlahCpns: null,
      jumlahTetap: null,
      jumlahKontrak: null,
      terlambat: null,
      tidakMasuk: null,
      chartOptions: {
        colors:['#069550', '#7A1FA2', '#1976D2', '#FFA600'],
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
            expandOnClick:false,
            donut: {
              size: '75%',
              // labels: {
              //   show: true,
              //   total:{
              //     show:true,
              //     label:'Total Pegawai',
              //     showAlways:true,
              //     fontSize:"10px",
              //     formatter: function(w){
              //       console.log(w)
              //       return w.globals.seriesTotals.reduce((a, b) => {return a + b}, 0).toLocaleString();
              //     }
              //   }
              // }
            }
          }
        },
        // tooltip: {
        //   y: {
        //     formatter: function(value, w) {
        //       let total = w.globals.seriesTotals.reduce((a, b) => {return a + b}, 0)
        //       return `${value} (${(value / total * 100).toFixed(2)}%)`;
        //     }
        //   }
        // },
        legend:{show: false},
        labels: ["Jumlah PNS", "Pegawai Tetap", "Jumlah CPNS","Pegawai Kontrak"],
      },
      series: []
    }
  },
  computed:{
    total(){
      return this.series.reduce((partialSum, a) => partialSum + a, 0)
    },
  },
  mounted(){
    this.updateData()
  },
  methods: {
    updateData() {
      this.jumlahPns = this.count_pns
      this.jumlahCpns = this.count_cpns
      this.jumlahTetap = this.count_permanent
      this.jumlahKontrak = this.count_contract
      this.series = [this.jumlahPns,this.jumlahCpns,this.jumlahTetap,this.jumlahKontrak]
    }
  }
}
</script>
<style scoped>
  .dots{
    width:10px;
    height: 10px;
    border-radius: 50%;
  }
</style>