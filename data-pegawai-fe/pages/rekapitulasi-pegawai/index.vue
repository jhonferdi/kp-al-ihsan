<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      { text: $metaInfo.title, active: true },
    ]" />
    <div class="card">
      <div class="card-body">
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">I. Pegawai PNS, CPNS, Tetap dan Kontrak</div>
            </div>
          </b-col>
        </b-row>
        <b-table-simple ref="table" responsive hover bordered no-border-collapse>
          <col style="width: 2%">
          <b-thead class="text-center" head-variant="dark">
            <b-tr>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                No
              </b-th>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                Golongan Profesi
              </b-th>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                Pendidikan
              </b-th>
              <b-th colspan="12" style="vertical-align: middle;" sticky-column>
                Status Pegawai
              </b-th>
              <b-th colspan="3" rowspan="2" style="vertical-align: middle;" sticky-column>
                Total Pegawai
              </b-th>
              <b-th colspan="1" rowspan="3" style="vertical-align: middle;" sticky-column>
                %
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="3">
                PNS
              </b-th>
              <b-th colspan="3">
                CPNS
              </b-th>
              <b-th colspan="3">
                Pegawai Tetap
              </b-th>
              <b-th colspan="3">
                Pegawai Kontrak
              </b-th>
            </b-tr>
            <b-tr>
              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>

              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>

              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>

              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>

              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>
            </b-tr>
          </b-thead>
          <b-tbody class="text-left">
            <b-tr v-for="(data, i) in dataTable1">
              <b-td>{{ i + 1 }}</b-td>
              <b-td>{{ data.tenaga_kerja_nama }}</b-td>
              <b-td>{{ data.jenis_tenaga_kerja_nama }}</b-td>
              <b-td>{{ data.lpns }}</b-td>
              <b-td>{{ data.ppns }}</b-td>
              <b-td>{{ data.jmlpns }}</b-td>
              <b-td>{{ data.lcpns }}</b-td>
              <b-td>{{ data.pcpns }}</b-td>
              <b-td>{{ data.jmlcpns }}</b-td>
              <b-td>{{ data.ltetap }}</b-td>
              <b-td>{{ data.ptetap }}</b-td>
              <b-td>{{ data.jmltetap }}</b-td>
              <b-td>{{ data.lkontrak }}</b-td>
              <b-td>{{ data.pkontrak }}</b-td>
              <b-td>{{ data.jmlkontrak }}</b-td>
              <b-td>{{ data.lpns + data.lcpns + data.ltetap + data.lkontrak }}</b-td>
              <b-td>{{ data.ppns + data.pcpns + data.ptetap + data.pkontrak }}</b-td>
              <b-td>{{ data.jumlah_total }}</b-td>
              <b-td>{{ ((data.jumlah_total / jmlTotal) * 100).toFixed(2) }}</b-td>
            </b-tr>
            <b-tr v-if="dataTable1.length == 0">
              <b-td colspan="19" class="text-center">Tidak Ada Data</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">II. Dokter Tamu/Outsourcing</div>
            </div>
          </b-col>
        </b-row>
        <b-table-simple ref="table" responsive hover bordered no-border-collapse>
          <col style="width: 2%">
          <b-thead class="text-center" head-variant="dark">
            <b-tr>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                No
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="1" style="vertical-align: middle;" sticky-column>
                Uraian
              </b-th>
              <b-th colspan="3" style="vertical-align: middle;" sticky-column>
                Jumlah
              </b-th>
            </b-tr>
            <b-tr>
              <b-th style="top: 93px">
                I. Dokter Spesialis
              </b-th>
              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>
            </b-tr>
          </b-thead>
          <b-tbody class="text-left">
            <b-tr v-for="(data, i) in dataTable2">
              <td>{{ i + 1 }}</td>
              <td>{{ data.spesialis_nama }}</td>
              <td>{{ data.pria }}</td>
              <td>{{ data.wanita }}</td>
              <td>{{ data.jumlah }}</td>
            </b-tr>
            <b-tr v-if="dataTable2.length == 0">
              <b-td colspan="5" class="text-center">Tidak Ada Data</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">III. Dokter Spesialis PNS, Tetap, Kontrak</div>
            </div>
          </b-col>
        </b-row>
        <b-table-simple ref="table" responsive hover bordered no-border-collapse>
          <col style="width: 2%">
          <b-thead class="text-center" head-variant="dark">
            <b-tr>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                No
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="1" style="vertical-align: middle;" sticky-column>
                Uraian
              </b-th>
              <b-th colspan="3" style="vertical-align: middle;" sticky-column>
                Jumlah
              </b-th>
            </b-tr>
            <b-tr>
              <b-th style="top: 93px">
                I. Dokter Spesialis
              </b-th>
              <b-th style="top: 93px">
                L
              </b-th>
              <b-th style="top: 93px">
                P
              </b-th>
              <b-th style="top: 93px">
                JML
              </b-th>
            </b-tr>
          </b-thead>
          <b-tbody class="text-left">
            <b-tr v-for="(data, i) in dataTable3">
              <td>{{ i + 1 }}</td>
              <td>{{ data.spesialis_nama }}</td>
              <td>{{ data.pria }}</td>
              <td>{{ data.wanita }}</td>
              <td>{{ data.jumlah }}</td>
            </b-tr>
            <b-tr v-if="dataTable3.length == 0">
              <b-td colspan="5" class="text-center">Tidak Ada Data</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">IV. Rekapitulasi Jumlah Pegawai</div>
            </div>
          </b-col>
        </b-row>
        <b-table-simple ref="table" responsive hover bordered no-border-collapse>
          <col style="width: 2%">
          <b-thead class="text-center" head-variant="dark">
            <b-tr>
              <b-th rowspan="3" style="vertical-align: middle;" sticky-column>
                No
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="1" style="vertical-align: middle;" sticky-column>
                I. Status Pegawai
              </b-th>
              <b-th rowspan="2" style="vertical-align: middle;" sticky-column>
                Jumlah
              </b-th>
              <b-th rowspan="2" style="vertical-align: middle;" sticky-column>
                %
              </b-th>
            </b-tr>
          </b-thead>
          <b-tbody class="text-left">
            <b-tr>
              <td>1</td>
              <td>PNS</td>
              <td>{{ jmlTotalPns }}</td>
              <td>{{ ((jmlTotalPns / jmlTotal) * 100).toFixed(2) }}</td>
            </b-tr>
            <b-tr>
              <td>2</td>
              <td>CPNS</td>
              <td>{{ jmlTotalCpns }}</td>
              <td>{{ ((jmlTotalCpns / jmlTotal) * 100).toFixed(2) }}</td>
            </b-tr>
            <b-tr>
              <td>3</td>
              <td>Tetap</td>
              <td>{{ jmlTotalTetap }}</td>
              <td>{{ ((jmlTotalTetap / jmlTotal) * 100).toFixed(2) }}</td>
            </b-tr>
            <b-tr>
              <td>4</td>
              <td>Kontrak</td>
              <td>{{ jmlTotalKontrak }}</td>
              <td>{{ ((jmlTotalKontrak / jmlTotal) * 100).toFixed(2) }}</td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ['auth'],


  head() {
    return { title: 'Rekapitulasi Pegawai' }
  },

  async asyncData() {
    let f1resp = (await axios.get('/rekapitulasi-pegawai?')).data
    let dataTable1 = f1resp.dataTable1
    let dataTable2 = f1resp.dataTable2
    let dataTable3 = f1resp.dataTable3
    let jmlTotal = f1resp.jmlTotal
    let jmlTotalPns = f1resp.jmlTotalPns
    let jmlTotalCpns = f1resp.jmlTotalCpns
    let jmlTotalTetap = f1resp.jmlTotalTetap
    let jmlTotalKontrak = f1resp.jmlTotalKontrak

    return {
      dataTable1,
      dataTable2,
      dataTable3,
      jmlTotal,
      jmlTotalPns,
      jmlTotalCpns,
      jmlTotalTetap,
      jmlTotalKontrak,
    }
  },

  data: () => ({
  }),

  created: function () {
  },

  watch: {
  },

  methods: {
    refreshTable() {
      this.$refs.table.refresh()
    }
  }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
